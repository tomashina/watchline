<?php echo $header; ?><?php echo $column_left; ?>
<div id="content" class="mp-content">
	<div class="page-header">
		<div class="container-fluid">
			<h1><?php echo $heading_title; ?></h1>
			<ul class="breadcrumb">
				<?php foreach ($breadcrumbs as $breadcrumb) { ?>
				<li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
				<?php } ?>
			</ul>
		</div>
	</div>
	<div class="container-fluid">
		<?php if ($error_warning) { ?>
		<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
			<button type="button" class="close" data-dismiss="alert">&times;</button>
		</div>
		<?php } ?>
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><i class="fa fa-upload"></i> <?php echo $text_form; ?></h3>
			</div>
			<div class="panel-body">
				<form id="form-product-import" class="form-horizontal">
					<ul class="nav nav-tabs" style="position: relative;">
		        <li class="active"><a href="#tab-general" data-toggle="tab"><i class="fa fa-cog"></i> <span><?php echo $tab_general; ?></span></a></li>
		        <li><a href="#tab-support" data-toggle="tab"><i class="fa fa-thumbs-up"></i> <span><?php echo $tab_support; ?></span></a></li>
		      </ul>
		      <div class="tab-content">
			      <div class="tab-pane active" id="tab-general">
			      	<div class="row">
								<div class="col-sm-6">
									<div class="form-group">
										<label class="col-sm-12 control-label"><span data-toggle="tooltip" title="<?php echo $help_file; ?>"><?php echo $entry_file; ?></span></label>
										<div class="col-sm-12">
											<div class="file-area">
												<input type="file" name="find_file" accept=".xls,.xlsx,.csv" id="input-file" required="required" />
												<div class="file-dummy">
													<div class="success"><?php echo $entry_great; ?></div>
													<div class="warning"><i class="fa fa-times-circle" aria-hidden="true"></i> Invalid</div>
													<div class="default"><?php echo $entry_dragfile; ?></div>
													<br/>
													<small class="small"></small>
												</div>
											</div>
										</div>
									</div>
									<div class="buttons imports">
										<button type="button" id="importer-product" class="btn btn-success btn-block"><i class="fa fa-upload"></i> <?php echo $button_import; ?></button>
										<a href="<?php echo $sample_download; ?>" download class="btn btn-default btn-block"><i class="fa fa-download"></i> <?php echo $button_download; ?></a>
									</div>
								</div>
								<div class="col-sm-6">
									<div class="form-group required">
										<label class="col-sm-12 control-label" for="input-language"><span data-toggle="tooltip" title="<?php echo $help_language; ?>"><?php echo $entry_language; ?></span></label>
										<div class="col-sm-12">
											<select name="find_language_id" id="input-language" class="form-control selectpicker">
												<?php foreach ($languages as $language) { ?>
												<option value="<?php echo $language['language_id']; ?>"><?php echo $language['name']; ?></option>
												<?php } ?>
											</select>
										</div>
									</div>
									<div class="form-group required">
										<label class="col-sm-12 control-label" for="input-store"><span data-toggle="tooltip" title="<?php echo $help_store; ?>"><?php echo $entry_store; ?></span></label>
										<div class="col-sm-12">
											<select name="find_store[]" id="input-store" class="form-control selectpicker" multiple="multiple">
												<option value="0" selected="selected"><?php echo $text_default; ?></option>
												<?php foreach ($stores as $store) { ?>
												<option value="<?php echo $store['store_id']; ?>"><?php echo $store['name']; ?></option>
												<?php } ?>
											</select>
										</div>
									</div>
									<div class="form-group mp-buttons">
										<label class="col-sm-12 control-label" for="input-reviews"><span data-toggle="tooltip" title="<?php echo $help_review; ?>"><?php echo $entry_review; ?></span></label>
										<div class="col-sm-12 col-xs-12 ">
											<div id="input-reviews" class="btn-group btn-group-justified" data-toggle="buttons">
												<label class="btn btn-primary active"><input type="radio" name="find_review" value="1" checked="checked" /> <?php echo $text_yes; ?></label>
												<label class="btn btn-primary"><input type="radio" name="find_review" value="0" /> <?php echo $text_no; ?></label>
											</div>
										</div>
									</div>
									<div class="form-group mp-buttons">
										<label class="col-sm-12 control-label" for="input-images"><span data-toggle="tooltip" title="<?php echo $help_images; ?>"><?php echo $entry_images; ?></span></label>
										<div class="col-sm-12 col-xs-12 ">
											<div id="input-images" class="btn-group btn-group-justified" data-toggle="buttons">
												<label class="btn btn-primary active"><input type="radio" name="find_images" value="1" checked="checked" /> <?php echo $text_yes; ?></label>
												<label class="btn btn-primary"><input type="radio" name="find_images" value="0" /> <?php echo $text_no; ?></label>
											</div>
										</div>
									</div>
									<div class="form-group mp-buttons">
										<label class="col-sm-12 control-label" for="input-customfields"><span data-toggle="tooltip" title="<?php echo $help_custom_fields; ?>"><?php echo $entry_custom_fields; ?></span></label>
										<div class="col-sm-12 col-xs-12 ">
											<div id="input-customfields" class="btn-group btn-group-justified" data-toggle="buttons">
												<label class="btn btn-primary active"><input type="radio" name="find_custom_fields" value="1" checked="checked" /> <?php echo $text_yes; ?></label>
												<label class="btn btn-primary"><input type="radio" name="find_custom_fields" value="0" /> <?php echo $text_no; ?></label>
											</div>
										</div>
									</div>
									<div class="form-group mp-buttons">
										<label class="col-sm-12 control-label" for="input-existsupdate"><span data-toggle="tooltip" title="<?php echo $help_existsupdate; ?>"><?php echo $entry_existsupdate; ?></span></label>
										<div class="col-sm-12 col-xs-12 ">
											<div id="input-existsupdate" class="btn-group btn-group-justified" data-toggle="buttons">
												<label class="btn btn-primary active"><input type="radio" name="find_existsupdate" value="1" checked="checked" /> <?php echo $text_yes; ?></label>
												<label class="btn btn-primary"><input type="radio" name="find_existsupdate" value="0" /> <?php echo $text_no; ?></label>
											</div>
										</div>
									</div>
									<div class="form-group mp-buttons">
										<label class="col-sm-12 control-label" for="input-importon"><span data-toggle="tooltip" title="<?php echo $help_importon; ?>"><?php echo $entry_importon; ?></span></label>
										<div class="col-sm-12 col-xs-12 ">
											<div id="input-importon" class="btn-group btn-group-justified" data-toggle="buttons">
												<label class="btn btn-primary active"><input type="radio" name="find_importon" value="product_id" checked="checked" /> <?php echo $text_product_id; ?></label>
												<label class="btn btn-primary"><input type="radio" name="find_importon" value="model_number" /> <?php echo $text_model; ?></label>
												<label class="btn btn-primary"><input type="radio" name="find_importon" value="product_name" /> <?php echo $text_product_name; ?></label>
											</div>
										</div>
									</div>
									<div class="form-group">
		                <label class="col-sm-12 control-label"><span data-toggle="tooltip" title="<?php echo $help_cell_operation; ?>"><?php echo $entry_cell_operation; ?></span></label>
		                <div class="col-sm-12">
		                  <div class="well well-sm" style="height: 250px; overflow: auto;">
		                    <?php foreach ($cell_operations as $cell_key => $cell_operation) { ?>
		                    <div>
		                      <label>
		                        <h4><input type="checkbox" name="find_cell_operations[<?php echo $cell_key; ?>]" value="1" checked="checked" />
		                        <?php echo $cell_operation; ?></h4>
		                      </label>
		                    </div>
		                    <?php } ?>
		                  </div>
		                  <a onclick="$(this).parent().find(':checkbox').prop('checked', true);" class="btn btn-info btn-xs"><?php echo $text_all; ?></a>
		                  <a onclick="$(this).parent().find(':checkbox').prop('checked', false);" class="btn btn-info btn-xs"><?php echo $text_unall; ?></a>
		                </div>
		              </div>
									<?php /* <div class="form-group last-group">
										<label class="col-sm-12 control-label" for="input-format"><span data-toggle="tooltip" title="<?php echo $help_format; ?>"><?php echo $entry_format; ?></span></label>
										<div class="col-sm-12 col-xs-12 ">
											<div id="input-format" class="btn-group btn-group-justified" data-toggle="buttons">
												<label class="btn btn-primary active"><input type="radio" name="find_format" value="xls" checked="checked" /> <?php echo $text_xls; ?></label>
												<label class="btn btn-primary"><input type="radio" name="find_format" value="xlsx" /> <?php echo $text_xlsx; ?></label>
												<label class="btn btn-primary"><input type="radio" name="find_format" value="csv" /> <?php echo $text_csv; ?></label>
											</div>
										</div>
									</div> */ ?>
								</div>
							</div>
			      </div>
			      <div class="tab-pane" id="tab-support">
		        	<div class="bs-callout bs-callout-info">
			          <h4>ModulePoints <?php echo $heading_title; ?></h4>
			          <center><strong><?php echo $heading_title; ?> - Version 2.0 </strong></center> <br/>
			          <p><?php echo $heading_title; ?> v2 comes with new features and few bug fixes. With this extension you have multiple filters and many export fields, so you get only data which you required. It's a upgraded version of "Old <?php echo $heading_title; ?>" extension with various new features and bug fixes.</p>
			        </div>
		        	<fieldset>
		            <div class="form-group">
		              <div class="col-md-12 col-xs-12">
		                <h4 class="text-mpsuccess text-center"><i class="fa fa-thumbs-up" aria-hidden="true"></i> Thanks For Choosing Our Extension</h4>
		                 <ul class="list-group">
		                  <li class="list-group-item clearfix">Installed Version <span class="badge"><i class="fa fa-gg" aria-hidden="true"></i> V.2.0</span></li>
		                </ul>
		                <h4 class="text-mpsuccess text-center"><i class="fa fa-phone" aria-hidden="true"></i> Please Contact Us In Case Any Issue OR Give Feedback!</h4>
		                <ul class="list-group">
		                  <li class="list-group-item clearfix">support@modulepoints.com <span class="badge"><a href="mailto:support@modulepoints.com?Subject=Request Support: <?php echo $heading_title; ?> Extension"><i class="fa fa-envelope"></i> Contact Us</a></span></li>
		                </ul>
		              </div>
		            </div>
		          </fieldset>
		        </div>
			    </div>
				</form>
			</div>
		</div>
	</div>
	<div class="mpteam"></div>
<script type="text/javascript"><!--
$('#input-file').change(function() {
	var fullPath = document.getElementById('input-file').value;
	if (fullPath) {
		var startIndex = (fullPath.indexOf('\\') >= 0 ? fullPath.lastIndexOf('\\') : fullPath.lastIndexOf('/'));
		var filename = fullPath.substring(startIndex);
		if (filename.indexOf('\\') === 0 || filename.indexOf('/') === 0) {
				filename = filename.substring(1);
		}
		$('.file-dummy').removeClass('warning').find('.warning').html('');
		$('.file-dummy').find('.success').html('<?php echo $entry_great; ?>');
		$('.file-dummy .small').html(filename);
	}
});

// Importer Product
$('#importer-product').click(function() {

	$('.file-dummy').removeClass('warning').find('.warning').html('');

	var customarray = $('#form-product-import input[type=\'text\'], #form-product-import input[type=\'file\'], #form-product-import input[type=\'hidden\'], #form-product-import select, #form-product-import input[type=\'checkbox\']:checked, #form-product-import input[type=\'radio\']:checked').serialize();

	var file_data = $("#form-product-import input[type=\'file\']").prop("files")[0];
	var form_data = new FormData();
	form_data.append("find_file", file_data)

	$.ajax({
		url: 'index.php?route=importer/product/import&token=<?php echo $token; ?>&'+ customarray,
		dataType: 'json',
		cache: false,
		contentType: false,
		processData: false,
		type: 'post',
		data: form_data,
		beforeSend: function() {
			$('.alert-danger, .alert-success, .text-danger').remove();
			$('.text-danger').parent().removeClass('has-error');

			$('#importer-product').button('loading');
			$('.mpteam').after('<div class="modal-backdrop in mpteam_loader"></div> <div class="loader mpteam_loader"></div>');
		},
		complete: function() {
			$('#importer-product').button('reset');
			$('.mpteam_loader').remove();
		},
		success: function(json) {
			$('.form-group, .col-sm-12').removeClass('has-error');

			if (json['error']) {
				if (json['error']['store']) {
					$('#input-store').after('<div class="text-danger">' + json['error']['store'] + '</div>');
				}

				if (json['error']['language']) {
					$('#input-language').after('<div class="text-danger">' + json['error']['language'] + '</div>');
				}

				if (json['error']['importon']) {
					$('#input-importon').after('<div class="text-danger">' + json['error']['importon'] + '</div>');
				}

				if (json['error']['file']) {
					$('.file-dummy').addClass('warning').find('.warning').html(json['error']['file']);
				}

				if(json['error']['warning']) {
					$('.panel.panel-default').before('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> '+ json['error']['warning'] +' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');

					$('html, body').animate({ scrollTop: 0 }, 'slow');
				}

				$('.text-danger').parent().parent().addClass('has-error');
			}

			if(json['success']) {
				$('.panel.panel-default').before('<div class="alert alert-success"><i class="fa fa-check-circle"></i> '+ json['success'] +' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');

				$('html, body').animate({ scrollTop: 0 }, 'slow');

				$('.file-dummy').find('.success').html('<i class="fa fa-check-circle"></i> '+ json['success']);
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			if(xhr.responseText) {
				$('.panel.panel-default').before('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> '+ xhr.responseText +' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
			}
		}
	});
});
//--></script>
</div>
<?php echo $footer; ?>