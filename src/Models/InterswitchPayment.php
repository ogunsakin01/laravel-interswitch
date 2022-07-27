<?php

namespace OgunsakinDamilola\Interswitch\Models;

use Illuminate\Database\Eloquent\Model;

class InterswitchPayment extends Model
{
    protected $fillable = ['id', 'customer_id',
        'customer_name', 'customer_email',
        'environment', 'gateway', 'reference',
        'amount', 'response_code', 'response_description'];
}
