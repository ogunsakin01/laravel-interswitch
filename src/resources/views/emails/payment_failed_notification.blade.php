@component('mail::message')
# Payment Failed

Your attempt to make a payment on {{config('app.name')}} platform failed.
@component('mail::panel')
<div style="align-items: self-start">
<b>Reason:</b> {{$payment['response_description']}}<br/>
<b>Payment Reference:</b>   {{$payment['reference']}}<br/>
<b>Amount:</b>   &#x20a6;{{number_format(($payment['amount']/100),2)}}
</div>
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
