<?php
namespace OgunsakinDamilola\Interswitch\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use OgunsakinDamilola\Interswitch\Facades\Interswitch;
use OgunsakinDamilola\Interswitch\Mail\PaymentFailed;
use OgunsakinDamilola\Interswitch\Mail\PaymentSuccessful;
use OgunsakinDamilola\Interswitch\Models\InterswitchPayment;

class InterswitchController extends Controller
{
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

    public function redirect(Request $request){
        $response = [
            'reference' => $request->txnref,
            'amount' => $request->amount,
            'response_code' => $request->resp,
            'response_description' => $request->desc
        ];
        $payment = InterswitchPayment::where('reference',$response['reference'])->first();
        $payment->response_code = $response['response_code'];
        $payment->response_description = $response['response_description'];
        $payment->update();
        if(in_array($response['response_code'],['00','01','11','10'])){
            Mail::to($payment->customer_email)->send(new PaymentSuccessful($payment));
        }
        Mail::to($payment->customer_email)->send(new PaymentFailed($payment));
        $redirectUrl = Interswitch::rebuildRedirectUrl($payment->toArray());
        return redirect($redirectUrl);
    }

    public function transactionsLog(Request $request){
        if(isset($request->email) && $request->email !== '' && !is_null($request->email)){
            $transactions = InterswitchPayment::where('customer_email',$_GET['customer_email'])
                ->orderBy('created_at','desc')
                ->get();
        }else{
            $transactions = InterswitchPayment::orderBy('created_at','desc')
                ->get();
        }
        return view('Interswitch::transactions_log', get_defined_vars());
    }

    public function confirm(Request $request){

    }
}
