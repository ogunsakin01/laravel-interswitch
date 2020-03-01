<?php
namespace OgunsakinDamilola\Interswitch\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use OgunsakinDamilola\Interswitch\Facades\Interswitch;

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
       dd($_POST);
    }

    public function confirm(Request $request){

    }
}
