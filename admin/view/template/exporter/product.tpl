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
  <div class="container-fluid" id="form-product-export">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?> ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default first-panel">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-filter"></i> <?php echo $text_form; ?></h3>
      </div>
      <div class="panel-body">
        <div class="alert alert-info"><i class="fa fa-info-circle"></i> <strong><?php echo $text_filters_note; ?></strong></div>
        <ul class="nav nav-tabs" style="position: relative;">
          <li class="active"><a href="#tab-general" data-toggle="tab"><i class="fa fa-cog"></i> <span><?php echo $tab_general; ?></span></a></li>
          <li><a href="#tab-support" data-toggle="tab"><i class="fa fa-thumbs-up"></i> <span><?php echo $tab_support; ?></span></a></li>
        </ul>
        <div class="tab-content">
          <div class="tab-pane active" id="tab-general">
          	<div class="row">
							<div class="col-sm-7">
								<div class="well">
									<div class="row">
										<div class="col-sm-6">
											<div class="form-group">
												<label class="control-label" for="input-qty"><span data-toggle="tooltip" title="<?php echo $help_qty; ?>"><?php echo $entry_qty; ?></span></label>
												<input type="text" name="find_quantity_start" placeholder="<?php echo $placeholder_quantity_start; ?> : 0" id="input-qty" class="form-control" value="" />
											</div>
										</div>
										<div class="col-sm-6">
											<div class="form-group">
												<label class="control-label" for="input-qty-limit">&nbsp; &nbsp; </label>
												<input type="text" name="find_quantity_limit"  placeholder="<?php echo $placeholder_quantity_limit; ?> : 1000" id="input-qty-limit" class="form-control" value="" />
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-sm-6">
											<div class="form-group">
												<label class="control-label" for="input-price"><span data-toggle="tooltip" title="<?php echo $help_price; ?>"><?php echo $entry_price; ?></span></label>
												<input type="text" name="find_price_start" placeholder="<?php echo $placeholder_price_start; ?> : 0" id="input-price" class="form-control" value="" />
											</div>
										</div>
										<div class="col-sm-6">
											<div class="form-group">
												<label class="control-label" for="input-price-limit">&nbsp; &nbsp; </label>
												<input type="text" placeholder="<?php echo $placeholder_price_limit; ?> : 1000" name="find_price_limit" id="input-price-limit" class="form-control" value="" />
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-sm-6">
											<div class="form-group">
												<label class="control-label" for="input-product"><span data-toggle="tooltip" title="<?php echo $help_product_limit; ?>"><?php echo $entry_product_limit; ?></span></label>
												<input type="text" name="find_product_start" placeholder="<?php echo $placeholder_product_start; ?> : 0" id="input-product" class="form-control" value="" />
											</div>
										</div>
										<div class="col-sm-6">
											<div class="form-group">
												<label class="control-label" for="input-product-limit">&nbsp; &nbsp; </label>
												<input type="text" name="find_product_limit" placeholder="<?php echo $placeholder_product_limit; ?> : 1000" id="input-product-limit" class="form-control" value="" />
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-sm-12">
											<div class="form-group">
												<label class="control-label" for="input-model"><?php echo $entry_model; ?></label>
												<input type="text" name="find_model" id="input-model" class="form-control" value="" placeholder="<?php echo $entry_model; ?>" />
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-sm-6">
											<div class="form-group">
												<label class="control-label" for="input-store"><?php echo $entry_store; ?></label>
												<select name="find_store_id" id="input-store" class="form-control selectpicker">
													<?php foreach ($stores as $store) { ?>
													<option value="<?php echo $store['store_id']; ?>"><?php echo $store['name']; ?></option>
													<?php } ?>
												</select>
											</div>
										</div>
										<div class="col-sm-6">
											<div class="form-group">
												<label class="control-label" for="input-language"><?php echo $entry_language; ?></label>
												<select name="find_language_id" id="input-language" class="form-control selectpicker">
													<?php foreach ($languages as $language) { ?>
													<option value="<?php echo $language['language_id']; ?>"><?php echo $language['name']; ?></option>
													<?php } ?>
												</select>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-sm-6">
											<div class="form-group">
												<label class="control-label" for="input-status"><?php echo $entry_status; ?></label>
												<select name="find_status" id="input-status" class="form-control selectpicker">
													<option value=""><?php echo $text_all_status; ?></option>
													<option value="1"><?php echo $text_enabled; ?></option>
													<option value="0"><?php echo $text_disabled; ?></option>
												</select>
											</div>
										</div>
										<div class="col-sm-6">
											<div class="form-group">
												<label class="control-label" for="input-stock-status"><?php echo $entry_stock_status; ?></label>
												<select name="find_stock_status_id" id="input-stock-status" class="form-control selectpicker">
													<option value=""><?php echo $text_all_stock_status; ?></option>
													<?php foreach ($stock_statuses as $stock_status) { ?>
													<option value="<?php echo $stock_status['stock_status_id']; ?>"><?php echo $stock_status['name']; ?></option>
													<?php } ?>
												</select>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-sm-5">
								<div class="panel panel-default">
				    			<div class="panel-heading">
				        		<h3 class="panel-title" data-toggle="collapse" href="#input_product_lists_collapse" role="button" aria-expanded="false" aria-controls="input_product_lists_collapse"><label class="control-label" for="input-product-lists"><i class="fa fa-tags"></i> <span data-toggle="tooltip" title="<?php echo $help_product; ?>"><?php echo $entry_product; ?></span></label></h3>
				      		</div>
				      		<div class="panel-body collapse in" id="input_product_lists_collapse">
				      			<input type="text" name="product_name" value="" placeholder="<?php echo $entry_product; ?>" id="input-product-lists" class="form-control" />
										<div id="export-product" class="well well-sm">
										</div>
				      		</div>
				      	</div>
				      	<div class="panel panel-default">
				    			<div class="panel-heading">
				        		<h3 class="panel-title" data-toggle="collapse" href="#input_manufacturer_collapse" role="button" aria-expanded="false" aria-controls="input_manufacturer_collapse"><label class="control-label" for="input-manufacturer"><i class="fa fa-tags"></i> <span data-toggle="tooltip" title="<?php echo $help_manufacturer; ?>"><?php echo $entry_manufacturer; ?></span></label></h3>
				      		</div>
				      		<div class="panel-body collapse in" id="input_manufacturer_collapse">
				      			<input type="text" name="manufacturer_name" value="" placeholder="<?php echo $entry_manufacturer; ?>" id="input-manufacturer" class="form-control" />
										<div id="export-manufacturer" class="well well-sm">
										</div>
				      		</div>
				      	</div>
				      	<div class="panel panel-default">
				    			<div class="panel-heading">
				        		<h3 class="panel-title" data-toggle="collapse" href="#input_category_collapse" role="button" aria-expanded="false" aria-controls="input_category_collapse"><label class="control-label" for="input-category"><i class="fa fa-tags"></i> <span data-toggle="tooltip" title="<?php echo $help_category; ?>"><?php echo $entry_category; ?></span></label></h3>
				      		</div>
				      		<div class="panel-body collapse in" id="input_category_collapse">
				      			<input type="text" name="category_name" value="" placeholder="<?php echo $entry_category; ?>" id="input-category" class="form-control" />
										<div id="export-category" class="well well-sm">
										</div>
				      		</div>
				      	</div>
							</div>
						</div>
						<div class="row">
					    <div class="col-sm-7">
				    		<div class="panel panel-default">
				    			<div class="panel-heading">
				        		<h3 class="panel-title" data-toggle="collapse" href="#export_fields_collapse" role="button" aria-expanded="false" aria-controls="export_fields_collapse"><label class="control-label"><i class="fa fa-download"></i> <span data-toggle="tooltip" title="<?php echo $help_cell_operation; ?>"><?php echo $entry_cell_operation; ?></span></label></h3>
				      		</div>
				      		<div class="abs-position">
				      			<a onclick="$('.export-table-body').parent().find(':checkbox').prop('checked', true);" class="btn btn-info btn-sm"><?php echo $text_all; ?></a>
						        <a onclick="$('.export-table-body').parent().find(':checkbox').prop('checked', false);"  class="btn btn-info btn-sm"><?php echo $text_unall; ?></a>
						        <a class="btn btn-success btn-sm save_field_settings"><i class="fa fa-refresh" data-class="fa fa-refresh"></i> <?php echo $button_save; ?></a>
				      		</div>
				      		<div class="panel-body collapse in" id="export_fields_collapse">
				      			<div class="export-table-body" id="find-fields-settings">
				              <?php $i = 1; ?>
				              <div class="row">
				              	<?php //print_r($find_fields); die;?>
				                <?php foreach ($find_fields as $find_field_value) { ?>
				                <div class="col-sm-4">
				                  <?php echo $i; ?> <label class="control-label"><input type="checkbox" name="productexport_setting_fields[<?php echo $find_field_value['code']; ?>][code]" value="1" <?php if (isset($productexport_setting_fields[$find_field_value['code']]) && $productexport_setting_fields[$find_field_value['code']]['code'] ) { ?>checked="checked"<?php } ?> /> <?php if ($find_field_value['help']) { ?><span data-toggle="tooltip" title="<?php echo $find_field_value['help']; ?>"> <?php echo $find_field_value['title']; ?></span><?php } else { ?><?php echo $find_field_value['title']; ?><?php } ?></label>
				                </div>
				                <?php $i = $i + 1; ?>
				                <?php } ?>
				              </div>
				      			</div>
									</div>
								</div>
							</div>

							<div class="col-sm-5">
								<div class="panel panel-default">
									<div class="panel-heading">
										<h3 class="panel-title"><label class="control-label" for="export-format"><span data-toggle="tooltip" title="<?php echo $help_format; ?>"><i class="fa fa-file-o"></i><?php echo $entry_format; ?></span></label></h3>
									</div>
									<div class="panel-body">
										<div class="form-group mp-buttons">
											<select name="find_format" class="form-control selectpicker" id="export-format">
											<option value="xls"><?php echo $text_xls; ?></option>
											<option value="xlsx"><?php echo $text_xlsx; ?></option>
											<option value="csv"><?php echo $text_csv; ?></option>
											<option value="xml"><?php echo $text_xml; ?></option>
											<option value="json"><?php echo $text_json; ?></option>
											</select>

										</div>
										<div class="buttons exports">
											<button type="button" class="btn btn-success btn-block" id="exporter-product"><i class="fa fa-download" aria-hidden="true"></i> <?php echo $button_export; ?></button>
										</div>
									</div>
								</div>
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
      </div>
    </div>
	</div>
	<div class="mpteam"></div>
<script type="text/javascript"><!--
$(function() {
	// Exporter Product
	$('#exporter-product').click(function() {
		$.ajax({
			url: 'index.php?route=exporter/product/export&token=<?php echo $token; ?>',
			type: 'post',
			data: $('#form-product-export input[type=\'text\'], #form-product-export input[type=\'hidden\'], #form-product-export select, #form-product-export input[type=\'checkbox\']:checked, #form-product-export input[type=\'radio\']:checked'),
			dataType: 'json',
			beforeSend: function() {
				$('.alert-danger, .alert-success').remove();
				$('#exporter-product').button('loading');
				$('.mpteam').after('<div class="modal-backdrop in mpteam_loader"></div> <div class="loader mpteam_loader"></div>');
			},
			complete: function() {
				$('#exporter-product').button('reset');
				$('.mpteam_loader').remove();
			},
			success: function(json) {
				if(json['error']) {
					$('.first-panel').before('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> '+ json['error'] +' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');

					$('html, body').animate({ scrollTop: 0 }, 'slow');
				}

				if(json['href']) {
					window.location = json['href'];
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				if(xhr.responseText) {
					$('.first-panel').before('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> '+ xhr.responseText +' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
				}
			}
		});
	});

	// Product
	$('input[name=\'product_name\']').autocomplete({
		'source': function(request, response) {
			$.ajax({
				url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
				dataType: 'json',
				success: function(json) {
					response($.map(json, function(item) {
						return {
							label: item['name'],
							value: item['product_id']
						}
					}));
				}
			});
		},
		'select': function(item) {
			$('input[name=\'product_name\']').val('');

			$('#export-product' + item['value']).remove();

			$('#export-product').append('<div id="export-product' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="find_product[]" value="' + item['value'] + '" /></div>');
		}
	});

	$('#export-product').delegate('.fa-minus-circle', 'click', function() {
		$(this).parent().remove();
	});

	// Manufacturer
	$('input[name=\'manufacturer_name\']').autocomplete({
		'source': function(request, response) {
			$.ajax({
				url: 'index.php?route=catalog/manufacturer/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
				dataType: 'json',
				success: function(json) {
					response($.map(json, function(item) {
						return {
							label: item['name'],
							value: item['manufacturer_id']
						}
					}));
				}
			});
		},
		'select': function(item) {
			$('input[name=\'manufacturer_name\']').val('');

			$('#export-manufacturer' + item['value']).remove();

			$('#export-manufacturer').append('<div id="export-manufacturer' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="find_manufacturer[]" value="' + item['value'] + '" /></div>');
		}
	});
	$('#export-manufacturer').delegate('.fa-minus-circle', 'click', function() {
		$(this).parent().remove();
	});

	// Category
	$('input[name=\'category_name\']').autocomplete({
		'source': function(request, response) {
			$.ajax({
				url: 'index.php?route=catalog/category/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
				dataType: 'json',
				success: function(json) {
					response($.map(json, function(item) {
						return {
							label: item['name'],
							value: item['category_id']
						}
					}));
				}
			});
		},
		'select': function(item) {
			$('input[name=\'category_name\']').val('');

			$('#export-category' + item['value']).remove();

			$('#export-category').append('<div id="export-category' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="find_category[]" value="' + item['value'] + '" /></div>');
		}
	});

	$('#export-category').delegate('.fa-minus-circle', 'click', function() {
		$(this).parent().remove();
	});

	$('.save_field_settings').on('click', function() {
		var $this = $(this);

		$.ajax({
			url: 'index.php?route=exporter/product/saveFieldSettings&token=<?php echo $token; ?>',
			type: 'post',
			data: $('#find-fields-settings input[type=\'text\'], #find-fields-settings input[type=\'hidden\'], #find-fields-settings input[type=\'checkbox\']:checked, #find-fields-settings input[type=\'radio\']:checked').serialize(),
			dataType: 'json',
			beforeSend: function() {
				$('.alert-danger, .alert-success').remove();
				$this.attr('disabled','disabled').find('i').attr('class','fa fa-spinner fa-spin');
			},
			complete: function() {
				$this.removeAttr('disabled').find('i').attr('class', $this.find('i').attr('data-class'));
			},
			success: function(json) {
				if (json['error']) {
					if (typeof json['error']['warning']) {
						$this.closest('.abs-position').after('<div class="alert alert-danger"><i class="fa fa-check-circle"></i> ' + json['error']['warning'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
					}
					<?php /*
					if (typeof json['error']['sort_order']) {
						for (var i in json['error']['sort_order']) {
							$('#sort_alphabet-' + i).closest('td').css('color','#f00');
	    				$('#sort_alphabet-' + i).css('color','#f00').css('border-color','#f00');
							json['error']['sort_order'][i]
						}
					}
					*/ ?>
				}
				if (json['success']) {
					$this.closest('.abs-position').after('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
			}
		});
	});

	$('#export_fields_collapse').on('shown.bs.collapse', function() {
		$('.abs-position .btn').removeAttr('disabled')
	});
	$('#export_fields_collapse').on('hidden.bs.collapse', function() {
		$('.abs-position .btn').attr('disabled','disabled')
	});
});
//--></script>
</div>
<?php echo $footer; ?>