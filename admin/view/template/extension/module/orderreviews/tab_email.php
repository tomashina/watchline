<div class="tab-pane">
<ul class="nav nav-tabs reviewmail_tabs">
      <h5><strong>Multi-lingual settings:</strong></h5>
      <?php $i=0; foreach ($languages as $language) { ?>
      <li <?php if ($i==0) echo 'class="active"'; ?>><a href="#tab-<?php echo $reviewmail['id']; ?>-<?php echo $language['language_id']; ?>" data-toggle="tab"><img src="<?php echo $language['flag_url'] ?>"/> <?php echo $language['name']; ?></a></li>
      <?php $i++; }?>
   </ul>
   <div class="tab-content">
      <?php $i=0; foreach ($languages as $language) { ?>
      <div id="tab-<?php echo $reviewmail['id']; ?>-<?php echo $language['language_id']; ?>" language-id="<?php echo $language['language_id']; ?>" class="row-fluid tab-pane language <?php if ($i==0) echo 'active'; ?>">
         <br />
         <h5><strong><strong>Review Mail Settings:</strong></strong></h5>
         <div class="row">
            <div class="col-md-3">
               <h5><strong>Subject:</strong></h5>
            </div>
            <div class="col-md-6">
               <input placeholder="Mail subject" type="text" class="form-control" name="<?php echo $reviewmail_name; ?>[Subject][<?php echo $language['language_id']; ?>]" value="<?php if(!empty($reviewmail_data['Subject'][$language['language_id']])) echo $reviewmail_data['Subject'][$language['language_id']]; else echo "MailReview Subject"; ?>" />
            </div>
            <div class="col-md-3">
               <button type="button" class="btn btn-info preview_button" data-lang-id="<?php echo $language['language_id']; ?>" id="livePreview_<?php echo $reviewmail['id']; ?>_<?php echo $language['language_id']; ?>" data-email-id="<?php echo $reviewmail['id']; ?>">Email Preview</button>
            </div>
         </div>
         <br />
         <div class="row">
            <div class="col-md-3">
               <h5><strong>Message:</strong></h5>
               <span class="help"><i class="fa fa-info-circle"></i>&nbsp;Use can use the following short-codes:
                  <br />
                  <br />{first_name} - First name
                  <br />{last_name} - Last name
                  <br />{order_products} - Ordered products
                  <br />{review_form} - Review form
                  <br />{order_id} - Order ID (optional)
                  <br />{reviewmail_link} - Link for online form of the email
               </span>
            </div>
            <div class="col-md-6">
               <textarea id="message_<?php echo $reviewmail['id']; ?>_<?php echo $language['language_id']; ?>" name="<?php echo $reviewmail_name; ?>[Message][<?php echo $language['language_id']; ?>]">
               <?php if(!empty($reviewmail_data['Message'][$language['language_id']])) echo $reviewmail_data['Message'][$language['language_id']]; else echo '<table style="width:100%;font-family:Verdana;">
                  <tbody>
                        <tr>
                              <td align="center">
                                 <table style="width:680px;margin:0 auto;border:1px solid #f0f0f0;line-height:1.8;font-size:1em;font-family:Verdana;">
                                       <tbody>
                                             <tr>
                                                   <td style="font-family:inherit;padding:10px;">
                                                      {reviewmail_link}
                                       
                                                      <p><span style="font-family: inherit; font-size: 1em; line-height: 1.8;">​Hello {first_name} {last_name},</span></p>
                                       
                                                      <p>Recently you bought {order_products} from our store. What do you think about the product(s) you ordered?</p>
                                       
                                                      <p>{review_form}</p>
                                       
                                                      <p>We really appreciate your feedback and we hope that you will visit us again soon.</p>
                                       
                                                      <p>Kind Regards,<br />
                                                      OrderReviews</p>
                                       
                                                      <p><a href="'.HTTP_CATALOG.'" target="_blank">'.HTTP_CATALOG.'</a></p>
                                                   </td>
                                             </tr>
                                       </tbody>
                                 </table>
                              </td>
                        </tr>
                  </tbody>
               </table>'; ?>
               </textarea>
            </div>
         </div>
         <br />
         <hr />
           
      
   </div>
   <?php $i++; } ?>
   <br />   
</div>
</div>
 <!--Modal for review email preview-->
<div class="modal fade " id="emailPreview_<?php echo $reviewmail['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"> <!--Modal for email preview-->
    <div class="modal-dialog modal-lg" role="document">
       <div class="modal-content">
          <div class="modal-header">
             <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
             <h4 class="modal-title" id="myModalLabel">Email preview</h4>
          </div>
          <div class="modal-body">
             
          </div>
       </div>
    </div>
 </div>

<script>
$('.preview_button').click(function(e){
   e.preventDefault();
   e.stopPropagation();

   var email_container =$(this).parents('#reviewmail_'+$(this).attr('data-email-id'));

   var display_images = email_container.find('select[name*="DisplayImages"] option:selected').val();
   var email_type = $('#EmailType').val();
   var review_form;
   var textarea = '';
   var language_id = $(this).attr('data-lang-id');
   try {
       textarea = email_container.find('#message_<?php echo $reviewmail['id']; ?>_'+ language_id +'').code();
   } catch (err) {
      if(err.message.indexOf('is not a function') > -1) {
         textarea = email_container.find('#message_<?php echo $reviewmail['id']; ?>_'+ language_id +'').summernote('code');
      }
   }

   var review_form;
   var ordered_products = '<a href="#"><strong>Our Test Product(s)</strong></a>';
   if(email_type == 'form'){
      var form_link = '<div style="font-family:inherit;font-size:11px;text-align:center;background: #f2f2f2;padding:3px;"><p>If this email is not displayed correctly or you cannot submit the form, please <a href="#"><strong>click here</strong></a>.</p></div>';
   
      review_form = '<table width="100%">';
      
      if(display_images == 'yes'){        
      review_form += '<tr style="text-align:center;"><td><img src="../image/iphone_1.jpg"></td></tr>';
      }
      review_form += '<tr>';
      review_form += '<td width="100%" style="font-family:Verdana;color:#f0ad4e;direction:ltr; text-align:center;">';
      review_form += '<span style="display:inline-block">';
      review_form += '<span style="display:inline-block;float:left;margin-right:15px">';
      review_form += '<input id="rat{number}_1" name="orderreviews[{number}][rating]" type="radio" value="1" style="font-family:inherit"><label for="rat{number}_1">';
      review_form += '<span style="font-size:21px;padding-left:0">★</span></label></span>';
      review_form += '<span style="display:inline-block;float:left;margin-right:15px">';
      review_form += '<input id="rat{number}_1" name="orderreviews[{number}][rating]" type="radio" value="1" style="font-family:inherit"><label for="rat{number}_1">';
      review_form += '<span style="font-size:21px;padding-left:0">★★</span></label></span>';
      review_form += '<span style="display:inline-block;float:left;margin-right:15px">';
      review_form += '<input id="rat{number}_1" name="orderreviews[{number}][rating]" type="radio" value="1" style="font-family:inherit"><label for="rat{number}_1">';
      review_form += '<span style="font-size:21px;padding-left:0">★★★</span></label></span>';
      review_form += '<span style="display:inline-block;float:left;margin-right:15px">';
      review_form += '<input id="rat{number}_1" name="orderreviews[{number}][rating]" type="radio" value="1" style="font-family:inherit"><label for="rat{number}_1">';
      review_form += '<span style="font-size:21px;padding-left:0">★★★★</span></label></span>';
      review_form += '<span style="display:inline-block;float:left;margin-right:15px">';
      review_form += '<input id="rat{number}_1" name="orderreviews[{number}][rating]" type="radio" value="1" style="font-family:inherit"><label for="rat{number}_1">';
      review_form += '<span style="font-size:21px;padding-left:0">★★★★★</span></label></span>';
      review_form += '</span>';
      review_form += '</tr>';
      review_form += '<tr>';
      review_form += '<td>';
      review_form += '<ul style="margin:0;padding:0"><textarea name="orderreviews[{number}][text]" cols="40" rows="5"';
      review_form += 'style="font-size:12px;padding-left:3px;min-height:60px;width:99%;border:solid 1px #e1e1e1;font-family:inherit;margin:0 0 15px 0">';
      review_form += '</textarea></ul><br>';
      review_form += '</td>';
      review_form += '</tr>';
      review_form += '<tr><td style="font-family:Verdana;text-align:right">';
      review_form += '<input type="submit" style="font-family:inherit;border:1px solid #C57824;padding:6px 13px;text-transform:uppercase;';
      review_form += 'text-decoration:none;background-color:#DF9020;font-size:13px;color:#ffffff" value="✓ Submit "></td></tr>';
      review_form += '</table>';    
   }else{
      var form_link = '<div style="font-family:inherit;font-size:11px;text-align:center;background: #f2f2f2;padding:3px; height:25px;"></div>';
      review_form = '';
      review_form += '<table width="100%">';
      review_form += ' <tbody><tr><td width="5%" style="font-family:Verdana"></td>';
      review_form += '<td width="90%" style="font-family:Verdana"><table cellspacing="0" cellpadding="0" border="0" style="width:100%"><tbody>';
      review_form += '<tr><td  width="210px" height="25%" align="left" style="font-family:Verdana;font-size:inherit;padding:15px 0;letter-spacing:0; text-align:center;">';
      review_form += ' <a href="javascript:void(0)" style="display: block; width: 140px;height: 40px;background: #229ac8;padding: 10px;font-weight: bold;  margin: 0 auto; text-decoration: none; color: white;">Leave a review</a>';
      review_form += ' </td></tr></tbody></table></td><td width="5%" style="font-family:Verdana"></td></tr>';
      review_form += '</tbody>'
      review_form += '</table>';
   }
   
   
   var patterns = ['{reviewmail_link}', '{first_name}','{last_name}','{order_products}','{review_form}'];
   var replacement = [form_link,"John","Doe",ordered_products,review_form];
   
   for (var x = 0; x < patterns.length; x++){
      textarea = textarea.replace(patterns[x],replacement[x]); 
   }

   $('#emailPreview_<?php echo $reviewmail['id']; ?>').modal('show');

   $('#emailPreview_<?php echo $reviewmail['id']; ?>').on('shown.bs.modal', function (e) {
         var modalContent = $(this).find( ".modal-body" );
         modalContent.html(textarea);
   });
   
});
      
</script>
