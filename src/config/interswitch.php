<?php

return [
    /**
     *  Your payment environment
     *  Accepts LIVE or TEST
     *
     */

    'env' => env('INTERSWITCH_ENV', 'TEST'),

    'currencCode' => 566,

    /**
     *  Interswitch payment gateway of choice
     *  Accepts WEBPAY or PAYDIRECT
     *
     */

    'gateway' => env('INTERSWITCH_GATEWAY'),

    'live' => [
        'requestUrl' => env('INTERSWITCH_REQUEST_URL'),
        'queryUrl' => env('INTERSWITCH_QUERY_URL'),
        'macKey' => env('INTERSWITCH_MAC_KEY'),
        'itemId' => env('INTERSWITCH_ITEM_ID'),
        'productId' => env('INTERSWITCH_PRODUCT_ID'),
    ],

    /**
     *  This are the default test credentials of interswitch for both gateways
     *
     */

    'test' => [
        'webPay' => [
            'requestUrl' => 'https://sandbox.interswitchng.com/webpay/pay',
            'queryUrl' => 'https://sandbox.interswitchng.com/webpay/api/v1/gettransaction.json',
            'macKey' => 'D3D1D05AFE42AD50818167EAC73C109168A0F108F32645C8B59E897FA930DA44F9230910DAC9E20641823799A107A02068F7BC0F4CC41D2952E249552255710F',
            'itemId' => 101,
            'productId' => 6205,
        ],
        'payDirect' => [
            'requestUrl' => 'https://sandbox.interswitchng.com/collections/w/pay',
            'queryUrl' => 'https://sandbox.interswitchng.com/collections/api/v1/gettransaction.json',
            'macKey' => '',
            'itemId' => 101,
            'productId' => 1706,
        ],
    ],

];
