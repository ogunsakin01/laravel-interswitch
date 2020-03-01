@component('mail::message')

# Hi {{$payment['customer_name']}},

You are about to make a payment of <b>&#x20a6;{{number_format(($payment['amount'] / 100),2)}}</b> on our platform. Find your payment reference below
@component('mail::panel')
    <div align="center">
        {{ $data['reference']}}
    </div>
@endcomponent
This email confirms that you are the one making the payment.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
