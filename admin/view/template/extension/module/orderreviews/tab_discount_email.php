<div class="tab-pane">
    <ul class="nav nav-tabs discountmail_tabs">
        <h5><strong>Multi-lingual settings:</strong></h5>
        <?php $k=0; foreach ($languages as $discount_language) { ?>
        <li <?php if ($k==0) echo 'class="active"'; ?>><a href="#tab-discount-<?php echo $reviewmail['id']; ?>-<?php echo $discount_language['language_id']; ?>" data-toggle="tab"><img src="<?php echo $discount_language['flag_url'] ?>"/> <?php echo $discount_language['name']; ?></a></li>
        <?php $k++; }?>
    </ul>
    <div class="tab-content">
        <?php $k=0; foreach ($languages as $discount_language) { ?>
        <div id="tab-discount-<?php echo $reviewmail['id']; ?>-<?php echo $discount_language['language_id']; ?>" discount_language-id="<?php echo $discount_language['language_id']; ?>" class="row-fluid tab-pane discount_language <?php if ($k==0) echo 'active'; ?>">
            <h5><strong>Discount Mail Settings:</strong></h5>
            <div class="row">
                <div class="col-md-3">
                    <h5><i class="fa fa-info-circle"></i>&nbsp;Discount Mail Subject:</h5>
                </div>
                <div class="col-md-6">
                    <input placeholder="Mail subject" type="text" class="form-control" name="<?php echo $reviewmail_name; ?>[SubjectDiscount][<?php echo $discount_language['language_id']; ?>]" value="<?php if(!empty($reviewmail_data['SubjectDiscount'][$discount_language['language_id']])) echo $reviewmail_data['SubjectDiscount'][$discount_language['language_id']]; else echo "MailReview Subject"; ?>" />
                </div>
                <div class="col-md-3">
                    <button type="button" class="btn btn-info" id="DiscountLivePreview_<?php echo $reviewmail['id']; ?>" data-email-id="<?php echo $reviewmail['id']; ?>" data-lang-id="<?php echo $discount_language['language_id']; ?>">Discount Mail Preview</button>
                </div>
            </div>
            <br/>
            <div class="row">
                <div class="col-md-3">
                    <h5><strong>Message:</strong></h5>
                    <span class="help">Use can use the following short-codes:
                        <br />
                        <br />{first_name} - First name
                        <br />{last_name} - Last name
                        <br />{discount_code} - Discount code
                        <br />{discount_value} - Discount value
                        <br />{total_amount} - Total amout
                        <br />{date_end} - Date end
                        <br />{order_id} - Order ID
                        <br /><br />
                    </span>
                </div>
                <div class="col-md-6">
                    <textarea id="messageD_<?php echo $reviewmail['id']; ?>_<?php echo $discount_language['language_id']; ?>" name="<?php echo $reviewmail_name; ?>[MessageDiscount][<?php echo $discount_language['language_id']; ?>]">
                    <?php if(!empty($reviewmail_data['MessageDiscount'][$discount_language['language_id']])) echo $reviewmail_data['MessageDiscount'][$discount_language['language_id']]; else echo '<table style="font-family:verdana; width:100%">
                            <tbody>
                                    <tr>
                                            <td>
                                                    <table style="border:1px solid #f0f0f0; font-family:verdana; font-size:1em; line-height:1.8; margin:0 auto; width:680px">
                                                            <tbody>
                                                                    <tr>
                                                                            <td style="padding:10px;">
                                                                                    <p>Hello {first_name} {last_name},<br />
                                                                                            <br />
                                                                                    Thank you for your review!</p>
                                                                                    
                                                                                    <p>We would like to give you a special discount code - <strong>{discount_code}</strong> - which gives you <strong>{discount_value} OFF</strong>.&nbsp;The code applies after you spent <strong>{total_amount}</strong>. This promotion is just for you and expires on <strong>{date_end}</strong>.</p>
																					
																					<p>You can apply the discount for all products and or all products under categories below:</p>
																					<p>{product_discount}</p>
																					<br />
																					<p>{category_discount}</p>
                                                                                    
                                                                                    <p>We hope that you will visit us again soon.</p>
                                                                                    
                                                                                    <p>Kind Regards,<br />
                                                                                    OrderReviews.</p>
                                                                                    
                                                                                    <p><a href="'.HTTP_CATALOG.'" target="_blank">'.HTTP_CATALOG.'</a></p>
                                                                            </td>
                                                                    </tr>
                                                            </tbody>
                                                    </table>
                                            </td>
                                    </tr>
                            </tbody>
                    </table>
                    '; ?>
                    </textarea>
                </div>
            </div>
        </div>
        <?php $k++; }?>
        <br/>
        <div class="row discountMailSettings">
            <div class="col-md-3">
                <h5><strong>Select date format for the end date of coupon validity:</strong></h5>
            </div>
            <div class="col-md-6">
                <select name="<?php echo $reviewmail_name; ?>[DateFormat]" class="form-control">
                    <option value="d-m-Y" <?php echo (isset($reviewmail_data['DateFormat']) && $reviewmail_data['DateFormat'] == 'd-m-Y') ? 'selected=selected' : '' ?>>dd-mm-yyyy</option>
                    <option value="m-d-Y" <?php echo (isset($reviewmail_data['DateFormat']) && $reviewmail_data['DateFormat'] == 'm-d-Y') ? 'selected=selected' : '' ?>>mm-dd-yyyy</option>
                    <option value="Y-m-d" <?php echo (isset($reviewmail_data['DateFormat']) && $reviewmail_data['DateFormat'] == 'Y-m-d') ? 'selected=selected' : '' ?>>yyyy-mm-dd</option>
                    <option value="Y-d-m" <?php echo (isset($reviewmail_data['DateFormat']) && $reviewmail_data['DateFormat'] == 'Y-d-m') ? 'selected=selected' : '' ?>>yyyy-dd-mm</option>
                </select>
            </div>
        </div>
        <div class="modal fade " id="DiscountEmailPreview_<?php echo $reviewmail['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"> <!--Modal for email preview-->
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Discount Email Preview</h4>
                </div>
                <div class="modal-body" id="discountModal_<?php echo $reviewmail['id']; ?>">
                </div>
            </div>
        </div>
    </div>
</div>
</div>
 <script>
$('button[id^=DiscountLivePreview_<?php echo $reviewmail['id']; ?>').click(function(e){
    e.preventDefault();
    e.stopPropagation();
    var discount_language_id = $(this).attr('data-lang-id');
debugger;
   var discount_email_container = $(this).parents('#reviewmail_'+$(this).attr('data-email-id'));
   var textarea = '';
   try {
       textarea = discount_email_container.find('#messageD_<?php echo $reviewmail['id']; ?>_'+ discount_language_id +'').code();

   } catch (err) {
      if(err.message.indexOf('is not a function') > -1) {
         textarea = discount_email_container.find('#messageD_<?php echo $reviewmail['id']; ?>_'+ discount_language_id +'').summernote('code');
      }
   }
   var discount_value = discount_email_container.find('input[name*="Discount"]').val();
   var total_value = discount_email_container.find('input[name*="TotalAmount"]').val();
   var validity = discount_email_container.find('input[name*="DiscountValidity"]').val();
   var date_format = discount_email_container.find('select[name*="DateFormat"] option:selected').val();
   var date = new Date();
   date.setDate(date.getDate() + parseInt(validity)); 
   var date_end = formatDate(date,date_format); 
   var discount_code = 'EXAMPLE%';
   var review_form;
   var ordered_products = '<a href="#"><strong>Our Test Product(s)</strong></a>';

   var products = [];
   <?php if (isset($reviewmail_data['products'])) { foreach ($reviewmail_data['products'] as $product) { ?>
		products = '<a href="<?php echo $catalogURL; ?>index.php?route=product/product&product_id=<?php echo $product["product_id"]; ?>"><?php echo $product["name"]; ?></a>';
   <?php } } ?>
   var categories = [];
   <?php if (isset($reviewmail_data['categories'])) { foreach ($reviewmail_data['categories'] as $category) { ?>
		categories = '<a href="<?php echo $catalogURL; ?>index.php?route=product/category&path=<?php echo $category["category_id"]; ?>"><?php echo $category["name"]; ?></a>';
   <?php } } ?>
   
   var patterns = ['{first_name}','{last_name}','{discount_code}','{discount_value}','{total_amount}','{date_end}','{product_discount}','{category_discount}'];
   var replacement = ["John","Doe",discount_code,discount_value,total_value,date_end,products,categories];
   
   for (var x = 0; x < patterns.length; x++){
      textarea = textarea.replace(patterns[x],replacement[x]); 
   }
   $('#DiscountEmailPreview_<?php echo $reviewmail['id']; ?>').modal('show');
   $('#DiscountEmailPreview_<?php echo $reviewmail['id']; ?>').on('shown.bs.modal', function (e) {
         var modalContent = $(this).find( ".modal-body" );
         modalContent.html(textarea);
   });
   
   function formatDate(date, format) {
      console.log(format);
    var d = new Date(date),
        month = '' + (d.getMonth() + 1),
        day = '' + d.getDate(),
        year = d.getFullYear();

    if (month.length < 2) month = '0' + month;
    if (day.length < 2) day = '0' + day;
   var outputDate;

   switch(format){
      
      case 'd-m-Y':
      outputDate = [day, month, year].join('-'); break;
      case 'm-d-Y':
      outputDate = [month, day, year].join('-'); break;
      case 'Y-m-d':
      outputDate = [year, month, day].join('-'); break;
      case 'Y-d-m':
      outputDate = [year, day, month].join('-'); break;
   }
   

    return outputDate;
}
   
});
      
</script>