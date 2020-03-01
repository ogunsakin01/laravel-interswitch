<?php


namespace OgunsakinDamilola\Interswitch;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Mail;
use OgunsakinDamilola\Interswitch\Mail\PrePaymentNotification;
use OgunsakinDamilola\Interswitch\Models\InterswitchPayment;

class Interswitch extends InterswitchTransactionsHelper
{
    public function generatePayment($paymentData): array
    {
        $amount = $paymentData['amount'] * 100;
        $reference = $this->transactionReferenceHandler(Arr::has($paymentData, 'reference', ''));
        $hash = $this->initializeTransactionHash($reference, $amount);
        $payment = [
            'customer_id' => $paymentData['customer_id'],
            'customer_name' => $paymentData['customer_name'],
            'customer_email' => $paymentData['customer_email'],
            'environment' => $this->env,
            'gateway' => $this->gateway,
            'reference' => $reference,
            'amount' => $amount,
            'response_code' => 0,
            'response_description' => 'Pending',
            'payment_status' => 0,
            'response_full' => ''
        ];
        Mail::to($paymentData['customer_email'])->send(new PrePaymentNotification($payment));
        InterswitchPayment::create($payment);
        return [
            'customerId' => $paymentData['customer_id'],
            'customerName' => $paymentData['customer_name'],
            'amount' => $amount,
            'reference' => $reference,
            'systemRedirectUrl' => $this->systemRedirectUrl,
            'currency' => $this->currency,
            'productId' => $this->productId,
            'itemId' => $this->itemId,
            'requestUrl' => $this->requestUrl,
            'queryUrl' => $this->queryUrl,
            'hash' => $hash
        ];
    }

    public function queryTransaction($reference)
    {

    }
}
