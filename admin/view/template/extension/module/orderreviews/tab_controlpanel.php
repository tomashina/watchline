<div class="container-fluid">
    <div class="row">
      <div class="col-md-3">
        <h5><strong><span class="required">* </span>Module status:</strong></h5>
        <span class="help"><i class="fa fa-info-circle"></i>&nbsp;Enable or disable the module.</span>
      </div>
      <div class="col-md-3">
        <select id="Checker" name="<?php echo $moduleName; ?>[Enabled]" class="form-control">
              <option value="yes" <?php echo (!empty($moduleData['Enabled']) && $moduleData['Enabled'] == 'yes') ? 'selected=selected' : '' ?>><?php echo $text_enabled; ?></option>
              <option value="no"  <?php echo (empty($moduleData['Enabled']) || $moduleData['Enabled']== 'no') ? 'selected=selected' : '' ?>><?php echo $text_disabled; ?></option>
        </select>
      </div>
    </div>
    <br/>
     <div class="row">
      <div class="col-md-3">
        <h5><strong><span class="required">* </span>Type of review mail:</strong></h5>
        <span class="help"><i class="fa fa-info-circle"></i>&nbsp;Choose whether to send the full review form or just a link to it on your site. Choose the link option if the review form is not displayed well.</span>
      </div>
      <div class="col-md-3">
        <select id="EmailType" name="<?php echo $moduleName; ?>[EmailType]" class="form-control">
              <option value="link" <?php echo (!empty($moduleData['EmailType']) && $moduleData['EmailType'] == 'link') ? 'selected=selected' : '' ?>>Send link</option>
              <option value="form"  <?php echo (empty($moduleData['EmailType']) || $moduleData['EmailType']== 'form') ? 'selected=selected' : '' ?>>Send form</option>
        </select>
      </div>
    </div>
    <br/>
 	<div class="row">
      <div class="col-md-3">
        <h5><strong><?php echo $text_privacy_policy; ?></strong></h5>
        <span class="help"><i class="fa fa-info-circle"></i>&nbsp;<?php echo $text_privacy_policy_help; ?></span>
      </div>
      <div class="col-md-3">
        <select name="<?php echo $moduleName; ?>[PrivacyPolicy]" class="form-control">
            <option value="1" <?php echo (!empty($moduleData['PrivacyPolicy']) && $moduleData['PrivacyPolicy'] == '1' ) ? 'selected=selected' : '' ?>><?php echo $text_enabled; ?></option>
            <option value="0" <?php echo (empty($moduleData['PrivacyPolicy']) || $moduleData['PrivacyPolicy'] == '0' ) ? 'selected=selected' : '' ?>><?php echo $text_disabled; ?></option>
        </select>
      </div>
    </div>
    <br />
	<div class="row">
      <div class="col-md-3">
        <h5><strong>Auto Approve</strong></h5>
        <span class="help"><i class="fa fa-info-circle"></i>&nbsp;Use this feature if you want to auto-approved your customer reviews</span>
      </div>
      <div class="col-md-3">
        <select name="<?php echo $moduleName; ?>[AutoApproved]" class="form-control" id="AutoApprovedToggle">
            <option value="yes" <?php echo (!empty($moduleData['AutoApproved']) && $moduleData['AutoApproved'] == 'yes' ) ? 'selected=selected' : '' ?>><?php echo $text_enabled; ?></option>
            <option value="no" <?php echo (empty($moduleData['AutoApproved']) || $moduleData['AutoApproved'] == 'no' ) ? 'selected=selected' : '' ?>><?php echo $text_disabled; ?></option>
        </select>
      </div>
    </div>
	<br />
	<div id="AutoApprovedSettings">
	<div class="row">
      <div class="col-md-3">
        <h5><strong>Auto Approve Stars Rating</strong></h5>
        <span class="help"><i class="fa fa-info-circle"></i>&nbsp;Choose minimum stars rating that will be automatically approved</span>
      </div>
      <div class="col-md-3">
        <select name="<?php echo $moduleName; ?>[AutoApprovedStar]" class="form-control">
            <option value="1" <?php echo (!empty($moduleData['AutoApprovedStar']) && $moduleData['AutoApprovedStar'] == '1' ) ? 'selected=selected' : '' ?>>1</option>
            <option value="2" <?php echo (!empty($moduleData['AutoApprovedStar']) && $moduleData['AutoApprovedStar'] == '2' ) ? 'selected=selected' : '' ?>>2</option>
            <option value="3" <?php echo (empty($moduleData['AutoApprovedStar']) || $moduleData['AutoApprovedStar'] == '3' ) ? 'selected=selected' : '' ?>>3</option>
            <option value="4" <?php echo (!empty($moduleData['AutoApprovedStar']) && $moduleData['AutoApprovedStar'] == '4' ) ? 'selected=selected' : '' ?>>4</option>
            <option value="5" <?php echo (!empty($moduleData['AutoApprovedStar']) && $moduleData['AutoApprovedStar'] == '5' ) ? 'selected=selected' : '' ?>>5</option>
        </select>
      </div>
    </div>
    </div>
	<br />
    <div class="row">
        <div class="col-xs-3">
            <h5 class="option_title"><strong><?php echo $text_expired_coupons; ?></strong></h5>
            <span class="help"><i class="fa fa-info-circle"></i>&nbsp;<?php echo $text_expired_coupons_help; ?></span>
        </div>
	        <div class="col-md-3">
	             <a class="btn btn-default btn-block btn-remove-expired" id="remove_expired_coupons"><i class="fa fa-info-circle"></i>&nbsp;<?php echo $btn_clear_expired_coupons; ?></a>
	        </div>
    </div>
    <br>
</div>
<div class="tabbable tabs-left" id="review_tabs">
	<div class="container-fluid">
        <div class="row">
          <div class="col-md-3">
            <h5><strong>Cron job:</strong></h5>
            <span class="help"><i class="fa fa-info-circle"></i>&nbsp;Send automatically emails to the customers.</span>
          </div>
          <div class="col-md-3">
				<button class="btn btn-primary cronModal"><i class="fa fa-question-circle"></i>&nbsp;&nbsp;How to set up the cron job?</button>
                <div class="checkbox">
                    <label>
                      <input type="checkbox" name="<?php echo $moduleName; ?>[CronNotification]" value="yes" <?php echo isset($moduleData['CronNotification']) ? 'checked="checked"' : ''; ?>/> Receive notification email when the cron is executed.
                    </label>
                </div>
          </div>
        </div>
        <br />
        <div class="row">
          <div class="col-md-3">
            <h5><strong>Send BCC to store owner:</strong></h5>
            <span class="help"><i class="fa fa-info-circle"></i>&nbsp;Enabling this option will add <?php echo $e_mail; ?> as BCC recepient.</span>
          </div>
          <div class="col-md-3">
            <select name="<?php echo $moduleName; ?>[BCC]" class="form-control">
                <option value="yes" <?php echo (isset($moduleData['BCC']) && $moduleData['BCC'] == 'yes') ? 'selected=selected' : '' ?>>Enabled</option>
                <option value="no" <?php echo (empty($moduleData['BCC']) || $moduleData['BCC']== 'no') ? 'selected=selected' : '' ?>>Disabled</option>
            </select>
          </div>
        </div>
        <br />
        <div class="row">
          <div class="col-md-3">
            <h5><strong><?php echo $text_logs; ?></strong></h5>
            <span class="help"><i class="fa fa-info-circle"></i>&nbsp;<?php echo $text_log_help; ?></span>
          </div>
          <div class="col-md-3">
            <select name="<?php echo $moduleName; ?>[LOG]" class="form-control">
                <option value="yes" <?php echo (isset($moduleData['LOG']) && $moduleData['LOG'] == 'yes') ? 'selected=selected' : '' ?>>Enabled</option>
                <option value="no" <?php echo (empty($moduleData['LOG']) || $moduleData['LOG'] == 'no') ? 'selected=selected' : '' ?>>Disabled</option>
            </select>
          </div>
        </div>
    </div>
    <br />
    <br />
    <ul class="nav nav-tabs review-list">
        <li class="static"><a class="addNewReviewMail"><i class="fa fa-plus"></i> Add New ReviewMail</a></li>
        <?php if (isset($moduleData['ReviewMail'])) { ?>
            <?php foreach ($moduleData['ReviewMail'] as $reviewmail) { ?>
            <li><a href="#reviewmail_<?php echo $reviewmail['id']; ?>" data-toggle="tab" data-reviewmail-id="<?php echo $reviewmail['id']; ?>"><i class="fa fa-pencil-square-o"></i> <?php echo (isset($reviewmail['Name']) && !empty($reviewmail['Name'])) ? $reviewmail['Name'] : 'ReviewMail '.$reviewmail['id']; ?> <i class="fa fa-minus-circle removeReviewMail"></i>
                <input type="hidden" name="<?php echo $moduleNameMail; ?>[ReviewMail][<?php echo $reviewmail['id']; ?>][id]" value="<?php echo $reviewmail['id']; ?>" />
                </a> </li>
            <?php } ?>
        <?php } ?>
    </ul>
    <div class="tab-content review-settings">
        <?php if (isset($moduleData['ReviewMail'])) { ?>
            <?php foreach ($moduleData['ReviewMail'] as $reviewmail) { 
                require(DIR_APPLICATION.'view/template/'.$module_path.'/tab_reviewtab.tpl');
            } ?>
        <?php } ?>
    </div>
</div>
        
<script type="text/javascript"><!--
// Add Template
function addNewReviewMail() {
	count = $('.review-list li:last-child > a').data('reviewmail-id') + 1 || 1;
	var ajax_data = {};
	ajax_data.token = '<?php echo $token; ?>';
	ajax_data.store_id = '<?php echo $store['store_id']; ?>';
	ajax_data.reviewmail_id = count;

	$.ajax({
		url: 'index.php?route=<?php echo $module_path; ?>/get_review_settings',
		data: ajax_data,
		dataType: 'html',
		beforeSend: function() {
			NProgress.start();
		},
		success: function(settings_html) {
			$('.review-settings').append(settings_html);
			
			if (count == 1) { $('a[href="#reviewmail_'+ count +'"]').tab('show'); }
			tpl 	= '<li>';
			tpl 	+= '<a href="#reviewmail_'+ count +'" data-toggle="tab" data-reviewmail-id="'+ count +'">';
			tpl 	+= '<i class="fa fa-pencil-square-o"></i> ReviewMail '+ count;
			tpl 	+= '<i class="fa fa-minus-circle removeReviewMail"></i>';
			tpl 	+= '<input type="hidden" name="<?php echo $moduleNameMail; ?>[ReviewMail]['+ count +'][id]" value="'+ count +'"/>';
			tpl 	+= '</a>';
			tpl	+= '</li>';
			
			$('.review-list').append(tpl);
			
			NProgress.done();
			$('.review-list').children().last().children('a').trigger('click');
			window.localStorage['currentSubTab'] = $('.review-list').children().last().children('a').attr('href');
		}
	});
}

// Remove expired coupons action
function removeExpiredCoupons() {      
    var confirmRemoveCoupons = confirm('Are you sure you want to remove all coupons generated by the module?');
    if (confirmRemoveCoupons==true) {
        $.ajax({
            url: 'index.php?route=<?php echo $module_path; ?>/removeallexpiredcoupons&token=<?php echo $token; ?>',
            type: 'post',
            data: {'remove': confirmRemoveCoupons },
            success: function(response) {
                alert('Coupons Removed Successfully!');
                location.reload();
            }
        });
    }
}

// Remove Template
function removeReviewMail() {
	tab_link = $(event.target).parent();
	tab_pane_id = tab_link.attr('href');
	
	var confirmRemove = confirm('Are you sure you want to remove ' + tab_link.text().trim() + '?');
	
	if (confirmRemove == true) {
		tab_link.parent().remove();
		$(tab_pane_id).remove();
		
		if ($('.review-list').children().length > 1) {
			$('.review-list > li:nth-child(2) a').tab('show');
			window.localStorage['currentSubTab'] = $('.review-list > li:nth-child(2) a').attr('href');
		}
	}
}



// Events for the Add and Remove buttons
$(document).ready(function() {
	// Add New Label
	$('.addNewReviewMail').click(function(e) { addNewReviewMail(); });
	// Remove Label
	$('.review-list').delegate('.removeReviewMail', 'click', function(e) { removeReviewMail(); });

	$('#remove_expired_coupons').on('click', function(){
		removeExpiredCoupons();
	});

});

// Show the EDITOR
<?php 
if (isset($moduleData['ReviewMail'])) { 
	foreach ($moduleData['ReviewMail'] as $reviewmail) {
		foreach ($languages as $language) { ?>
			$('#message_<?php echo $reviewmail['id']; ?>_<?php echo $language['language_id']; ?>').summernote({
				disableDragAndDrop: true,
				height: 320,
				emptyPara: '',
				toolbar: [
					['style', ['style']],
					['font', ['bold', 'underline', 'clear']],
					['fontname', ['fontname']],
					['color', ['color']],
					['para', ['ul', 'ol', 'paragraph']],
					['table', ['table']],
					['insert', ['link', 'image', 'video']],
					['view', ['fullscreen', 'codeview', 'help']]
				],
				buttons: {
					image: function() {
						var ui = $.summernote.ui;

						// create button
						var button = ui.button({
							contents: '<i class="note-icon-picture" />',
							tooltip: $.summernote.lang[$.summernote.options.lang].image.image,
							click: function () {
								$('#modal-image').remove();
							
								$.ajax({
									url: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
									dataType: 'html',
									beforeSend: function() {
										$('#button-image i').replaceWith('<i class="fa fa-circle-o-notch fa-spin"></i>');
										$('#button-image').prop('disabled', true);
									},
									complete: function() {
										$('#button-image i').replaceWith('<i class="fa fa-upload"></i>');
										$('#button-image').prop('disabled', false);
									},
									success: function(html) {
										$('body').append('<div id="modal-image" class="modal">' + html + '</div>');
										
										$('#modal-image').modal('show');
										
										$('#modal-image').delegate('a.thumbnail', 'click', function(e) {
											e.preventDefault();
											
											$('#message_<?php echo $reviewmail['id']; ?>_<?php echo $language['language_id']; ?>').summernote('insertImage', $(this).attr('href'));
																		
											$('#modal-image').modal('hide');
										});
									}
								});						
							}
						});
					
						return button.render();
					}
				}
			});
			$('#messageD_<?php echo $reviewmail['id']; ?>_<?php echo $language['language_id']; ?>').summernote({
				disableDragAndDrop: true,
				height: 320,
				emptyPara: '',
				toolbar: [
					['style', ['style']],
					['font', ['bold', 'underline', 'clear']],
					['fontname', ['fontname']],
					['color', ['color']],
					['para', ['ul', 'ol', 'paragraph']],
					['table', ['table']],
					['insert', ['link', 'image', 'video']],
					['view', ['fullscreen', 'codeview', 'help']]
				],
				buttons: {
					image: function() {
						var ui = $.summernote.ui;

						// create button
						var button = ui.button({
							contents: '<i class="note-icon-picture" />',
							tooltip: $.summernote.lang[$.summernote.options.lang].image.image,
							click: function () {
								$('#modal-image').remove();
							
								$.ajax({
									url: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
									dataType: 'html',
									beforeSend: function() {
										$('#button-image i').replaceWith('<i class="fa fa-circle-o-notch fa-spin"></i>');
										$('#button-image').prop('disabled', true);
									},
									complete: function() {
										$('#button-image i').replaceWith('<i class="fa fa-upload"></i>');
										$('#button-image').prop('disabled', false);
									},
									success: function(html) {
										$('body').append('<div id="modal-image" class="modal">' + html + '</div>');
										
										$('#modal-image').modal('show');
										
										$('#modal-image').delegate('a.thumbnail', 'click', function(e) {
											e.preventDefault();
											
											$('#messageD_<?php echo $reviewmail['id']; ?>_<?php echo $language['language_id']; ?>').summernote('insertImage', $(this).attr('href'));
																		
											$('#modal-image').modal('hide');
										});
									}
								});						
							}
						});
					
						return button.render();
					}
				}
			});
<?php	}
	}
} ?>

$('.cronModal').on('click', function(e){
	e.preventDefault();
	e.stopPropagation();
	$('.cronjobs').modal({backdrop: true});
});

// Selectors for discount
function selectorsForDiscount() {
	$('.discountTypeSelect').each(function() {
		//debugger;
		if ($(this).val() == 'P'){
			$(this).parents('.reviews').find('#percentageAddon').show();
		} else {
			$(this).parents('.reviews').find('#currencyAddon').show();
		}
		//
		$(this).parents('.reviews').find('.discountMailSelect').each(function() {
			if ($(this).val() == 'yes'){
				$(this).parents('.reviews').find('.discountMailSettings').show();
			} else {
				$(this).parents('.reviews').find('.discountMailSettings').hide();
			}
		});
		//
		if ($(this).val() == 'N'){
			$(this).parents('.reviews').find('.discountSettings').hide();
			$(this).parents('.reviews').find('.discountMailSettings').hide();
		} else {
			$(this).parents('.reviews').find('.discountSettings').show();
		}
	});

	$('.discountMailSelect').on('change', function(e){ 
		if($(this).val() == 'yes') {
			$(this).parents('.reviews').find('.discountMailSettings').show(300);
		} else {
			$(this).parents('.reviews').find('.discountMailSettings').hide(300);
		}	
	});
	
	$('.discountTypeSelect').on('change', function(e){ 
		if($(this).val() == 'P') {
			$(this).parents('.reviews').find('#percentageAddon').show();
			$(this).parents('.reviews').find('#currencyAddon').hide();
		} else {
			$(this).parents('.reviews').find('#currencyAddon').show();
			$(this).parents('.reviews').find('#percentageAddon').hide();
		}
		//
		$(this).parents('.reviews').find('.discountMailSelect').each(function() {
			if ($(this).val() == 'yes'){
				$(this).parents('.reviews').find('.discountMailSettings').show();
			} else {
				$(this).parents('.reviews').find('.discountMailSettings').hide();
			}
		});
		//	
		if($(this).val() == 'N') {
			$(this).parents('.reviews').find('.discountSettings').hide(300);
			$(this).parents('.reviews').find('.discountMailSettings').hide();
		} else {
			$(this).parents('.reviews').find('.discountSettings').show(300);
		}
	});
}

// Initialize selector for discount
$(function() {
	selectorsForDiscount();
});

$(function() {
    var $typeSelector = $('#AutoApprovedToggle');
    var $toggleArea = $('#AutoApprovedSettings');
	
	if ($typeSelector.val() === 'no') {
		$toggleArea.hide(300) 
	}
	 
    $typeSelector.change(function(){
        if ($typeSelector.val() === 'yes') {
            $toggleArea.show(300) 
        }
        else {
            $toggleArea.hide(300);
        }
    });
});
</script>
