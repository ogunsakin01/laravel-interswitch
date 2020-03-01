@php
echo "Redirecting to interswitch's payment page, please hold on ...";
echo
    '<body onload="document.paymentRedirectForm.submit()">
    <form method="POST" action="'.$paymentData['requestUrl'].'" name="paymentRedirectForm" style="display:none">
      <input type="hidden" value="'.$paymentData['reference'].'" name="txn_ref">
      <input type="hidden" value="'.$paymentData['amount'].'" name="amount"/>
      <input type="hidden" value="'.$paymentData['currency'].'" name="currency"/>
      <input type="hidden" value="'.$paymentData['itemId'].'" name="pay_item_id"/>
      <input type="hidden" value="'.$paymentData['systemRedirectUrl'].'" name="site_redirect_url"/>
      <input type="hidden" value="'.$paymentData['productId'].'" name="product_id"/>
      <input type="hidden" value="'.$paymentData['customerId'].'" name="cust_id"/>
      <input type="hidden" value="'.$paymentData['customerName'].'" name="cust_name"/>
      <input type="hidden" value="'.$paymentData['hash'].'" name="hash"/>
    </form>
</body>';
@endphp
