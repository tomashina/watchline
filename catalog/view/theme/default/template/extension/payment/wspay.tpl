<form name="pay" action="<?php echo $action; ?>" method="POST">




 <input type="hidden" name="ShopID" value="<?php echo $merchant; ?>">
<input type="hidden" name="ShoppingCartID" value="<?php echo $order_id; ?>">
<!--<input type="hidden" name="ShoppingCartID" value="<?php echo $order_id.$merchant; ?>"> -->
<input type="hidden" name="TotalAmount" value="<?php echo $total; ?>">


<input type="hidden" name="Signature" value="<?php echo $md5; ?>">

<input type="hidden" name="CustomerFirstname" value="<?php echo $firstname; ?>">
<input type="hidden" name="CustomerLastName" value="<?php echo $lastname; ?>">
<input type="hidden" name="CustomerAddress" value="<?php echo $address; ?>">
<input type="hidden" name="CustomerCity" value="<?php echo $city; ?>">
<input type="hidden" name="CustomerCountry" value="<?php echo $country; ?>">
<input type="hidden" name="CustomerZIP" value="<?php echo $postcode; ?>">
<input type="hidden" name="CustomerPhone" value="<?php echo $telephone; ?>">
<input type="hidden" name="CustomerEmail" value="<?php echo $email; ?>">

<input type="hidden" name="Lang" value="HR">

<input type="hidden" name="valuta" value="<?php echo $currency; ?>">

<input type="hidden" name="tecaj" value="<?php echo $tecaj; ?>">

<input type="hidden" name="ReturnErrorURL" value="<?php echo HTTP_SERVER ?>index.php?route=checkout/cart">
<input type="hidden" name="ReturnURL" value="<?php echo HTTP_SERVER ?>index.php?route=extension/payment/wspay/callback">
<input type="hidden" name="CancelURL" value="<?php echo HTTP_SERVER ?>index.php?route=checkout/cart">


<div class="buttons pull-right">
     <input type="submit" value="<?php echo $button_confirm; ?>" class="btn btn-dark" style="background-color: #090;color:#fff"/>
        
        
    </div>

    
</form>
