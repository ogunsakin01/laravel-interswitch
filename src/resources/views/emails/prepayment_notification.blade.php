@component('mail::message')

# Hi {{$payment['customer_name']}},

You are about to make a payment on {{config('app.name')}}. Find your payment reference below
@component('mail::panel')
<div align="left" style="align-items: self-start">
<b>Payment Reference:</b> {{ $payment['reference']}}<br/>
<b>Amount:</b> &#x20a6;{{number_format(($payment['amount']/100), 2)}}
</div>
@endcomponent
This email confirms that you are the one making the payment.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
