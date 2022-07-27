<?php
namespace OgunsakinDamilola\Interswitch;


use Illuminate\Support\Facades\Mail;
use OgunsakinDamilola\Interswitch\Mail\PaymentFailed;
use OgunsakinDamilola\Interswitch\Mail\PaymentSuccessful;
use OgunsakinDamilola\Interswitch\Mail\PrePaymentNotification;

class InterswitchMailHandler
{
    public static function newPaymentNotification($email, $paymentData)
    {
        try {
            Mail::to($email)->send(new PrePaymentNotification($paymentData));
        } catch (\Exception $e) {
            abort(500, $e->getMessage());
        }
    }

    public static function paymentNotification($email, $paymentData)
    {
        try {
            if (! in_array($paymentData['response_code'], ['00', '01', '11', '10'])) {
                Mail::to($email)->send(new PaymentFailed($paymentData));
            }else {
                Mail::to($email)->send(new PaymentSuccessful($paymentData));
            }
        } catch (\Exception $e){
            abort(500, $e->getMessage());
        }
    }


}
