<?php


namespace OgunsakinDamilola\Interswitch;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;

class Interswitch extends InterswitchTransactionsHelper
{
    public function initiateTransaction(){
        dd('I made it here');
    }

    public function queryTransaction(){

    }
}
