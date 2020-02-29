<?php


namespace OgunsakinDamilola\Interswitch;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;

class InterswitchTransactionsHelper
{
    protected $env;

    protected $gateway;

    protected $productId;

    protected $itemId;

    protected $redirectUrl;

    protected $macKey;

    protected $requestUrl;

    protected $queryUrl;

    public function __construct()
    {
        $this->env = Config::get('interswitch.env');
        $this->gateway = Config::get('interswitch.gateway');
        $this->redirectUrl = Config::get('interswitch.redirectUrl');
        $this->environmentHandler();
    }

    protected function testEnvironmentHandler(): void
    {
        if ($this->gateway === 'PAYDIRECT'):
            $this->itemId = Config::get('interswitch.test.payDirect.itemId');
            $this->productId = Config::get('interswitch.test.payDirect.productId');
            $this->macKey = Config::get('interswitch.test.payDirect.macKey');
            $this->queryUrl = Config::get('interswitch.test.payDirect.queryUrl');
            $this->requestUrl = Config::get('interswitch.test.payDirect.requestUrl');
        elseif ($this->gateway === 'WEBPAY'):
            $this->itemId = Config::get('interswitch.test.webPay.itemId');
            $this->productId = Config::get('interswitch.test.webPay.productId');
            $this->macKey = Config::get('interswitch.test.webPay.macKey');
            $this->queryUrl = Config::get('interswitch.test.webPay.queryUrl');
            $this->requestUrl = Config::get('interswitch.test.webPay.requestUrl');
        endif;
    }

    protected function liveEnvironmentHandler(): void
    {
        $this->itemId = Config::get('interswitch.live.itemId');
        $this->productId = Config::get('interswitch.live.productId');
        $this->macKey = Config::get('interswitch.live.macKey');
        $this->queryUrl = Config::get('interswitch.live.queryUrl');
        $this->requestUrl = Config::get('interswitch.live.requestUrl');
    }

    protected function environmentHandler(): void
    {
        switch ($this->env) {
            case 'Test':
                $this->testEnvironmentHandler();
                break;
            case 'LIVE':
                $this->liveEnvironmentHandler();
                break;
            default:
                $this->testEnvironmentHandler();
        }
    }

    protected function transactionReferenceHandler($reference = ''): string
    {
        if ($reference == ''):
            return strtoupper(Str::random(9));
        endif;
        return $reference;
    }

    protected function initializeTransactionHash($reference, $amount): string
    {
        $hashString = $reference . $this->productId . $this->itemId . $amount . $this->redirectUrl . $this->macKey;
        return hash('SHA512', $hashString);
    }

    protected function queryTransactionHash($reference): string
    {
        $hashString = $this->productId . $reference . $this->macKey;
        return hash('SHA512', $hashString);
    }
}
