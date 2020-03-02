# laravel-interswitch


[![Latest Stable Version](https://poser.pugx.org/ogunsakindamilola/laravel-interswitch/v/stable.svg)](https://packagist.org/packages/ogunsakindamilola/laravel-interswitch)
[![License](https://poser.pugx.org/ogunsakindamilola/laravel-interswitch/license.svg)](LICENSE.md)
[![Total Downloads](https://img.shields.io/packagist/dt/ogunsakindamilola/laravel-interswitch.svg?style=flat-square)](https://packagist.org/packages/ogunsakindamilola/laravel-interswitch)

A laravel package for Interswitch

## Installation

[PHP](https://php.net) 7.2+, and [Composer](https://getcomposer.org) are required.

To get the latest version of Laravel Interswitch, simply require it

```bash
composer require ogunsakindamilola/laravel-interswitch
```

Or add the following line to the require block of your `composer.json` file.

```json
"ogunsakindamilola/laravel-interswitch": "dev-master"
```

You'll then need to run `composer install` or `composer update` to download it and have the autoloader updated.
## Configuration

You can publish the configuration file using this command:
```bash
php artisan vendor:publish
```
Then select `ogunsakindamilola/laravel-interswitch`

A configuration-file named `interswitch.php` with some sensible defaults will be placed in your `config` directory:

```php
<?php

return [
    /**
     *  Your payment environment
     *  Accepts LIVE or TEST
     *
     */

    'env' => env('INTERSWITCH_ENV', 'TEST'),

    'currency' => 566,

    /**
     *  Interswitch payment gateway of choice
     *  Accepts WEBPAY or PAYDIRECT
     *
     */

    'gateway' => env('INTERSWITCH_GATEWAY'),

    /**
     *  Redirect URL
     *  This is the URL Interswitch redirects you to
     *  **PLEASE DO NOT CHANGE**  `The cost of using magic will be your soul`
     */

    'systemRedirectUrl' => 'interswitch-pay-redirect',


    /**
     *  This is the redirect url defined by you in your environment file
     *
     */

    'redirectUrl' => env('INTERSWITCH_REDIRECT_URL'),

    /**
     *  Live credentials as defined in your environment variables
     *
     */


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
```
Open your .env file and add the following 

```dotenv
INTERSWITCH_ENV=TEST
INTERSWITCH_GATEWAY=WEBPAY
INTERSWITCH_REDIRECT_URL=http://localhost:8000/payment-confirmation/
INTERSWITCH_REQUEST_URL=
INTERSWITCH_QUERY_URL=
INTERSWITCH_MAC_KEY=
INTERSWITCH_ITEM_ID=
INTERSWITCH_PRODUCT_ID=
````
`INTERSWITCH_ENV=` can be set to either `LIVE` or `TEST`.
`INTERSWITCH_GATEWAY=` can be set tot either `WEBPAY` or `PAYDIRECT`.
The redirect URL must be a get url declared in your route where you  wish to redirect to when a payment process is complete.
Make sure this route is defined in your web.php as a `GET` route. Make sure to replace the other env variables withe the details you received from Interswitch.

While still in your env file, make sure you set up your database environment
```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=
````
Replace the variables with your database connection variables the run `php artisan migrate`. When the migration is complete. Return to your env file and set up your mailing environment

```dotenv
MAIL_DRIVER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=null
MAIL_FROM_NAME="${APP_NAME}"
````
When this is done, then you can proceed to using this package.

## Usage
Interswitch requires you to follow some specific steps when implementing their payment gateway
###### 1 Initiate transaction
This initiate transaction sends a post request to the payment gateway and redirects to the payment page
###### 2 Customer Prepayment Notification Email
You are meant to send a prepayment notification email to your customers informaing them about the transaction they are about to make.
###### 3 Store Transaction Details
You are suppose to store all transactions in db even before they are completed
###### 4 Payment Confirmation Notification Email
You must send a payment confirmation email to your customer stating whether the transaction was successful or not and stating your reasons.
###### 5  Transaction Logs Table and Requery
Interswitch requires that you have a page where all transactions are displayed and that you have a requery button which can be used to reconfirm the status of the transaction.


Phew, life hard. Writing that out alone is exhausting, I can imagine how implementing it will feel like. 
Don't worry, I know exactly how it feels. Well, on this package, all you have to do is add this on your payment page.

```blade
<form method="post" action="{{route('InterswitchPay')}}">
  <input type="hidden" name="customer_name" value="John Doe" required />
  <input type="hidden" name="customer_id" value="1" required/>
  <input type="email" name="customer_email" required value="" placeholder="a valid email" />
  <input type="number" min="0" name="amount" required value=""/>
  <button type="Submit">Pay</button>
</form>
```

That's it, when the pay button is clicked, all the dirty work is done for you behind the scene. When the payment is complete, you will be redirected to the `INTERSWITCH_REDIRECT_URL=` you set in your .env with the following query parameters 
````php
[
  "id" => "11"
  "customer_id" => "1"
  "customer_name" => "Ogunsakin Damilola"
  "customer_email" => "ogunsakin191@gmail.com"
  "environment" => "TEST"
  "gateway" => "WEBPAY"
  "reference" => "5E5C880D78133"
  "amount" => "90000"
  "response_code" => "Z6"
  "response_description" => "Customer cancellation"
  "created_at" => "2020-03-02 04:14:08"
  "updated_at" => "2020-03-02 04:14:14"
]
````

To view your transaction logs, go to this route
```php 
url(interswitch-transactions-log);
OR
route('InterswitchTransactionsLog');
```

That's it, everything is done for. You are good to go.

## Now that you here
Thank you for taking your time with my package. 

Be generous with you stars and don't forget to follow me on IG and Twitter 
@he_is_unique

