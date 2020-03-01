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
           'reference' => 'sometimes|unique:interswitch_payments,reference'
       ]);
       $paymentData = Interswitch::generatePayment($request->all());
       return view('Interswitch::pay', compact('paymentData'));
    }

    public function redirect(){
        $response = [
            'reference' => $_POST['txnref'],
            'amount' => $_POST['amount'],
            'response_code' => $_POST['resp'],
            'response_description' => $_POST['desc']
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

    public function transactionsLog(){
        if(isset($_GET['email']) && $_GET['email'] !== ''){
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
