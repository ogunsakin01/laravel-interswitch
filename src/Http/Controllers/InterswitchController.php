<?php
namespace OgunsakinDamilola\Interswitch\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use OgunsakinDamilola\Interswitch\Facades\Interswitch;
use OgunsakinDamilola\Interswitch\InterswitchMailHandler;
use OgunsakinDamilola\Interswitch\Models\InterswitchPayment;

class InterswitchController extends Controller
{
    /**
     *  This method helps to generate the payment configuration before redirecting to the Interswitch payment page
     *  The view `Interswitch::pay` handles the traditional Interswitch post which in turns redirect to the actual Interswitch payment page
     *
     * @param Request $request
     * @return \Illuminate\View\View
     * @throws \Illuminate\Validation\ValidationException
     */
    public function pay(Request $request){
       $this->validate($request, [
           'customer_name' => 'required|string',
           'customer_id' => 'required',
           'customer_email' => 'required|email',
           'amount' => 'required|numeric|gt:0',
           'reference' => 'sometimes|min:6|unique:interswitch_payments,reference'
       ]);
       $paymentData = Interswitch::generatePayment($request->all());
       return view('Interswitch::pay', compact('paymentData'));
    }

    /**
     * The point of reentry back into the application from Interswitch payment page
     *
     * @param Request $request
     * @return Redirects to the redirect URl defined by the package user with the transaction details as GET variables
     */

    public function redirect(Request $request){
        $response = [
            'reference' => $request->txnref,
            'amount' => $request->amount,
            'response_code' => $request->resp,
            'response_description' => $request->desc
        ];
        $confirmPayment = Interswitch::queryTransaction($response['reference'], $response['amount']);
        $payment = InterswitchPayment::where('reference',$response['reference'])->first();
        $payment->response_code = $confirmPayment['response_code'];
        $payment->response_description = $confirmPayment['response_description'];
        $payment->update();
        InterswitchMailHandler::paymentNotification($payment->customer_email,$payment->toArray());
        $redirectUrl = Interswitch::rebuildRedirectUrl($payment->toArray());
        return redirect($redirectUrl);
    }

    /**
     * Returns the transaction logs view
     * @param Request $request
     * @return \Illuminate\View\View
     */

    public function transactionsLog(Request $request){
        if(isset($request->email) && $request->email !== '' && !is_null($request->email)){
            $transactions = InterswitchPayment::where('customer_email',$request->email)
                ->orderBy('created_at','desc')
                ->get();
        }else{
            $transactions = InterswitchPayment::orderBy('created_at','desc')
                ->get();
        }
        return view('interswitch.transactions_log', get_defined_vars());
    }

    /**
     * Handle transaction confirmation requests
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function confirmPayment(Request $request){
        $validator = Validator::make($request->all(),[
            'reference' => 'required|string|exists:interswitch_payments,reference'
        ]);
        if($validator->fails()){
            return response()->json([
                'status' => 'failed',
                'message' => 'Validation failed',
                'errors' => $validator->getMessageBag()
            ],422);
        }
        $payment = InterswitchPayment::where('reference', $request->reference)->first();
        $response = Interswitch::queryTransaction($payment->reference, $payment->amount);
        $payment->response_code = $response['response_code'];
        $payment->response_description = $response['response_description'];
        $payment->update();
        InterswitchMailHandler::paymentNotification($payment->customer_email,$payment->toArray());
        return response()->json([
            'status' => 'success',
            'message' => $response['response_code']. '-'.$response['response_description'],
            'data' => $response
        ],200);
    }
}
