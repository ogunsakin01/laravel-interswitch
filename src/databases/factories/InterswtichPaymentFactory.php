<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use OgunsakinDamilola\Interswitch\Models\InterswitchPayment;

$factory->define(InterswitchPayment::class, function (Faker $faker) {

    return [
        'customer_id' => $faker->randomDigit,
        'customer_name' => $faker->name,
        'customer_email' => $faker->email,
        'environment' => env('INTERSWITCH_ENV'),
        'gateway' => env('INTERSWITCH_GATEWAY'),
        'reference' => $faker->unique(),
        'amount' => $faker->numerify('######'),
        'response_code' => 0,
        'response_description' => 'Pending',
        'payment_status' => 0,
        'response_full' => ''
    ];
});
