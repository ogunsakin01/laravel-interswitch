<?php
namespace OgunsakinDamilola\Interswitch;

use Illuminate\Support\Arr;
use OgunsakinDamilola\Interswitch\Models\InterswitchPayment;

class Interswitch extends InterswitchTransactionsHelper
{
    public function generatePayment($paymentData)
    {
        $amount = $paymentData['amount'] * 100;
        $reference = $this->transactionReferenceHandler(Arr::get($paymentData, 'reference', ''));
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
        InterswitchMailHandler::newPaymentNotification($paymentData['customer_email'], $payment);
        InterswitchPayment::query()->create($payment);
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

    public function queryTransaction($reference, $amount)
    {
        $headers = [
            "Hash:" . $this->queryTransactionHash($reference)
        ];
        $url = $this->queryUrl . '?productid=' . $this->productId . '&transactionreference=' . $reference . '&amount=' . $amount;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_TIMEOUT, 120);
        $response = curl_exec($ch);
        curl_close($ch);
        return $this->queryValidator($reference, $amount, $response);
    }

    private function queryValidator($reference, $amount, $response)
    {
        if (empty($response)) {
            return [
                'reference' => $reference,
                'response_code' => '--',
                'response_description' => 'Could not confirm payment status. Bad Internet Connection',
                'amount' => $amount
            ];
        }
        $response = json_decode($response, true);
        return [
            'reference' => $reference,
            'response_code' => $response['ResponseCode'],
            'response_description' => $response['ResponseDescription'],
            'amount' => $amount
        ];

    }
}
