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
        $reference = $this->transactionReferenceHandler(Arr::has($paymentData, 'reference', ''));
        $hash = $this->initializeTransactionHash($reference, $paymentData['amount']);
        $payment = [
            'customer_id' => $paymentData['customer_id'],
            'customer_name' => $paymentData['customer_name'],
            'environment' => $this->env,
            'gateway' => $this->gateway,
            'reference' => $reference,
            'amount' => $paymentData['amount'],
            'response_code' => 0,
            'response_description' => 'Pending',
            'payment_status' => 0,
            'response_full' => ''
        ];
        InterswitchPayment::create($payment);
        Mail::to($paymentData['customer_email'])->send(new PrePaymentNotification($payment));
        return [
            'customerId' => $paymentData['customer_id'],
            'customerName' => $paymentData['customer_name'],
            'reference' => $reference,
            'redirectUrl' => $this->redirectUrl,
            'productId' => $this->productId,
            'itemId' => $this->itemId,
            'currency' => $this->currency,
            'requestUrl' => $this->requestUrl,
            'queryUrl' => $this->queryUrl,
            'hash' => $hash
        ];
    }

    public function queryTransaction($reference)
    {

    }
}
