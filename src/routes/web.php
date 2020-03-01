<?php

Route::group(['namespace' => 'OgunsakinDamilola\Interswitch\Http\Controllers'], function(){
    Route::post('interswitch-pay', 'InterswitchController@pay')->name('InterswitchPay');
    Route::post('interswitch-pay-redirect', 'InterswitchController@redirect')->name('InterswitchPayRedirect');
    Route::post('interswitch-confirm-payment', 'InterswitchController@confirmPayment')->name('InterswitchConfirmPayment');
    Route::post('interswitch-transactions-log', 'InterswitchController@transactionsLog')->name('InterswitchTransactionsLog');
});
