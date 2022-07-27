<?php

use Faker\Generator as Faker;
use OgunsakinDamilola\Interswitch\Models\InterswitchPayment;


$factory->define(InterswitchPayment::class, function (Faker $faker) {
    return [
        'customer_id' => $faker->randomNumber(1),
        'customer_name' => $faker->name,
        'customer_email' => $faker->email,
        'environment' => 'TEST',
        'gateway' => 'WEBPAY',
        'reference' => uniqid(),
        'amount' => 20000,
        'response_code' => '00',
        'response_description' => 'Success'
    ];
});

