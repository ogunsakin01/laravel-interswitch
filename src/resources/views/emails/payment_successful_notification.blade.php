@component('mail::message')
# Payment Successful

Your payment was successful
@component('mail::panel')
<div align="left" style="align-items: self-start">
   <b>Payment Reference:</b>   {{$payment['reference']}}<br/>
   <b>Amount:</b>   &#x20a6;{{number_format(($payment['amount']/100),2)}}
</div>
@endcomponent
Thanks,<br>
{{ config('app.name') }}
@endcomponent
