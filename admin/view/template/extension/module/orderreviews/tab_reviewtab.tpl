<?php 
   $reviewmail_name = $moduleNameMail.'[ReviewMail]['.$reviewmail['id'].']';
   $reviewmail_data = $reviewmail ? $reviewmail : array();
?>
<div id="reviewmail_<?php echo $reviewmail['id']; ?>" class="tab-pane reviews" style="width:99%;overflow:hidden;">
   <ul class="nav nav-tabs">
        <li class="active"><a data-toggle="tab" href="#general_settings_<?php echo $reviewmail['id'] ?>">General</a></li>
        <li><a data-toggle="tab" href="#conditions_<?php echo $reviewmail['id'] ?>">Configuration</a></li>
        <li><a data-toggle="tab" href="#email_template_<?php echo $reviewmail['id'] ?>">Email Template</a></li>
        <li><a data-toggle="tab" href="#discount_<?php echo $reviewmail['id'] ?>">Discount Settings</a></li>
        <li id="discountMailTab_<?php echo $reviewmail['id']; ?>"><a data-toggle="tab" href="#discount_email_template_<?php echo $reviewmail['id'] ?>">Discount Email Template</a></li>
    </ul>

    <div class="tab-content">
        <div id="general_settings_<?php echo $reviewmail['id'] ?>" class="tab-pane fade in active">
            <?php require(DIR_APPLICATION.'view/template/'.$module_path.'/tab_general.php'); ?>
        </div>
        <div id="conditions_<?php echo $reviewmail['id'] ?>" class="tab-pane fade in">
            <?php require(DIR_APPLICATION.'view/template/'.$module_path.'/tab_conditions.php'); ?>
        </div>
        <div id="discount_<?php echo $reviewmail['id'] ?>" class="tab-pane fade in">
            <?php require(DIR_APPLICATION.'view/template/'.$module_path.'/tab_discount.php'); ?>
        </div>
        <div id="email_template_<?php echo $reviewmail['id'] ?>" class="tab-pane fade in">
            <?php require(DIR_APPLICATION.'view/template/'.$module_path.'/tab_email.php'); ?>
        </div>
         <div id="discount_email_template_<?php echo $reviewmail['id'] ?>" class="tab-pane fade in">
            <?php require(DIR_APPLICATION.'view/template/'.$module_path.'/tab_discount_email.php'); ?>
        </div>
        
    </div>
    <?php if (isset($newAddition) && $newAddition==true) { ?>
	<script type="text/javascript">
		<?php foreach ($languages as $language) { ?>
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
		<?php } ?>

		<?php foreach ($languages as $discount_language) { ?>
		$('#messageD_<?php echo $reviewmail['id']; ?>_<?php echo $discount_language['language_id']; ?>').summernote({
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
										
										$('#messageD_<?php echo $reviewmail['id']; ?>_<?php echo $discount_language['language_id']; ?>').summernote('insertImage', $(this).attr('href'));
																	
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
		<?php } ?>
		selectorsForDiscount();
	</script>
	<?php } ?>
</div>		
</script>
