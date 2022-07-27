<?php
namespace OgunsakinDamilola\Interswitch;

class interswitchTransactionsHelper
{
    protected $env;

    protected $gateway;

    protected $productId;

    protected $itemId;

    protected $redirectUrl;

    protected $systemRedirectUrl;

    protected $macKey;

    protected $requestUrl;

    protected $queryUrl;

    protected $currency;

    public function __construct()
    {
        $this->env = config('interswitch.env');
        $this->gateway = config('interswitch.gateway');
        $this->redirectUrl = config('interswitch.redirectUrl');
        $this->systemRedirectUrl = url(config('interswitch.systemRedirectUrl'));
        $this->currency = config('interswitch.currency');
        $this->environmentHandler();
    }

    protected function testEnvironmentHandler(): void
    {
        if ($this->gateway === 'PAYDIRECT'):
            $this->itemId = config('interswitch.test.payDirect.itemId');
            $this->productId = config('interswitch.test.payDirect.productId');
            $this->macKey = config('interswitch.test.payDirect.macKey');
            $this->queryUrl = config('interswitch.test.payDirect.queryUrl');
            $this->requestUrl = config('interswitch.test.payDirect.requestUrl');
        elseif ($this->gateway === 'WEBPAY'):
            $this->itemId = config('interswitch.test.webPay.itemId');
            $this->productId = config('interswitch.test.webPay.productId');
            $this->macKey = config('interswitch.test.webPay.macKey');
            $this->queryUrl = config('interswitch.test.webPay.queryUrl');
            $this->requestUrl = config('interswitch.test.webPay.requestUrl');
        endif;
    }

    protected function liveEnvironmentHandler(): void
    {
        $this->itemId = config('interswitch.live.itemId');
        $this->productId = config('interswitch.live.productId');
        $this->macKey = config('interswitch.live.macKey');
        $this->queryUrl = config('interswitch.live.queryUrl');
        $this->requestUrl = config('interswitch.live.requestUrl');
    }

    protected function environmentHandler(): void
    {
        switch ($this->env) {
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
            return strtoupper(uniqid());
        endif;
        return $reference;
    }

    protected function initializeTransactionHash($reference, $amount): string
    {
        $hashString = $reference . $this->productId . $this->itemId . $amount . $this->systemRedirectUrl . $this->macKey;
        return hash('SHA512', $hashString);
    }

    protected function queryTransactionHash($reference): string
    {
        $hashString = $this->productId . $reference . $this->macKey;
        return hash('SHA512', $hashString);
    }

    public function rebuildRedirectUrl(array $parameters): string{
        $returnUrl = $this->redirectUrl.'?';
        foreach($parameters as $key => $parameter){
          $returnUrl = $returnUrl.$key.'='.$parameter.'&';
        }
        return $returnUrl;
    }

}
