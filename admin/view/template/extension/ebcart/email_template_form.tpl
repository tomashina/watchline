<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
		<button type="button" class="btn btn-success" data-toggle="modal" data-target="#ShortcutModal"><?php echo $text_shortcuts; ?></button>
        <button type="submit" form="form-email_template" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i> <?php echo $button_save; ?></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
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
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_form; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-email_template" class="form-horizontal">
          <ul class="nav nav-tabs">
            <li class="active"><a href="#tab-general" data-toggle="tab"><i class="fa fa-power-off" aria-hidden="true"></i> <?php echo $tab_general; ?></a></li>
            <li><a href="#tab-coupon" data-toggle="tab"><i class="fa fa-gift" aria-hidden="true"></i> <?php echo $tab_coupon; ?></a></li>
            <li><a href="#tab-shortcode" data-toggle="tab"><i class="fa fa-code" aria-hidden="true"></i> <?php echo $tab_shortcode; ?></a></li>
            <li><a href="#tab-support" data-toggle="tab"><i class="fa fa-life-ring" aria-hidden="true"></i> <?php echo $tab_support; ?></a></li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane active" id="tab-general">
              <ul class="nav nav-tabs" id="language">
                <?php foreach ($languages as $language) { ?>
                <li>
					<a href="#language<?php echo $language['language_id']; ?>" data-toggle="tab">
						<?php if(VERSION >= '2.2.0.0') { ?>
						<img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" title="<?php echo $language['name']; ?>" /> 
						<?php } else { ?> 
						<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> 
						<?php } ?>  <?php echo $language['name']; ?>
					</a>
				</li>
                <?php } ?>
              </ul>
              <div class="tab-content">
                <?php foreach ($languages as $language) { ?>
                <div class="tab-pane" id="language<?php echo $language['language_id']; ?>">
                  <div class="form-group required">
                    <label class="col-sm-2 control-label" for="input-title<?php echo $language['language_id']; ?>"><?php echo $entry_title; ?></label>
                    <div class="col-sm-10">
                      <input type="text" name="abandonedcart_email_template_description[<?php echo $language['language_id']; ?>][title]" value="<?php echo isset($abandonedcart_email_template_description[$language['language_id']]) ? $abandonedcart_email_template_description[$language['language_id']]['title'] : ''; ?>" placeholder="<?php echo $entry_title; ?>" id="input-title<?php echo $language['language_id']; ?>" class="form-control" />
                      <?php if (isset($error_title[$language['language_id']])) { ?>
                      <div class="text-danger"><?php echo $error_title[$language['language_id']]; ?></div>
                      <?php } ?>
                    </div>
                  </div>
				  <div class="form-group required">
                    <label class="col-sm-2 control-label" for="input-subject<?php echo $language['language_id']; ?>"><?php echo $entry_subject; ?></label>
                    <div class="col-sm-10">
                      <input type="text" name="abandonedcart_email_template_description[<?php echo $language['language_id']; ?>][subject]" value="<?php echo isset($abandonedcart_email_template_description[$language['language_id']]) ? $abandonedcart_email_template_description[$language['language_id']]['subject'] : ''; ?>" placeholder="<?php echo $entry_subject; ?>" id="input-subject<?php echo $language['language_id']; ?>" class="form-control" />
                      <?php if (isset($error_subject[$language['language_id']])) { ?>
                      <div class="text-danger"><?php echo $error_subject[$language['language_id']]; ?></div>
                      <?php } ?>
                    </div>
                  </div>
                  <div class="form-group required">
                    <label class="col-sm-2 control-label" for="input-description<?php echo $language['language_id']; ?>"><?php echo $entry_description; ?></label>
                    <div class="col-sm-10">
                      <textarea name="abandonedcart_email_template_description[<?php echo $language['language_id']; ?>][description]" placeholder="<?php echo $entry_description; ?>" id="input-description<?php echo $language['language_id']; ?>" class="form-control summernote"><?php echo isset($abandonedcart_email_template_description[$language['language_id']]) ? $abandonedcart_email_template_description[$language['language_id']]['description'] : ''; ?></textarea>
                      <?php if (isset($error_description[$language['language_id']])) { ?>
                      <div class="text-danger"><?php echo $error_description[$language['language_id']]; ?></div>
                      <?php } ?>
                    </div>
                  </div>
                </div>
                <?php } ?>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="input-sort-order"><?php echo $entry_sort_order; ?></label>
					<div class="col-sm-10">
					  <input type="text" name="sort_order" value="<?php echo $sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order" class="form-control" />
					</div>
				</div>
              </div>
            </div>
			<div class="tab-pane" id="tab-support">
				<p class="text-center">For Support and Query Feel Free to contact:<br><strong>extensionsbazaar@gmail.com</strong></p>
			</div>
            <div class="tab-pane" id="tab-shortcode">
				   <div class="table-responsive">
						<table class="table table-bordered table-hover">
						  <thead>
							<tr>
								<td>
									<div class="col-sm-12">
									   <fieldset>
											<legend>Cart Products</legend>
											<p>{cart_products} = cart Products</p>
										</fieldset>
									</div>
								</td>
								<td>
									<div class="col-sm-12">
									   <fieldset>
											<legend>Coupon</legend>
											<p>{coupon} = Coupon Code</p>
											<p>{discount} = Discount Value</p>
											<p>{currency} = Currency Symbol</p>
											<p>{total_amount} = Total Amount</p>
											<p>{date_end} = Date End</p>
										</fieldset>
									</div>
								</td>
							</tr>
							<tr>
								<td>
									<div class="col-sm-12">
									   <fieldset>
											<legend>Store Information</legend>
											<p>{logo} = Store Logo</p>
											<p>{Store_name} = Store Name</p>
											<p>{Store_address} = Store Address</p>
											<p>{Store_email} = Store Email</p>
											<p>{Store_telephone} = Store Telephone</p>
											<p>{store_url} = Store Link</p>
										</fieldset>
									</div>
								</td>
								<td>
									<div class="col-sm-12">
									   <fieldset>
											<legend>Customer Information</legend>
											<p>{firstname} = First Name</p>
											<p>{lastname} = Last Name</p>
											<p>{email} = E-Mail</p>
											<p>{telephone} = Telephone</p>
											<p>{date_end} = Date End</p>
										</fieldset>
									</div>
								</td>
							</tr>
						   </thead>
						 </table>
					</div>
			</div>
            <div class="tab-pane" id="tab-coupon">
				<div class="form-group">
					<label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
					<div class="col-sm-5">
					  <select name="coupon_status" id="input-status" class="form-control">
						<?php if ($coupon_status) { ?>
						<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
						<option value="0"><?php echo $text_disabled; ?></option>
						<?php } else { ?>
						<option value="1"><?php echo $text_enabled; ?></option>
						<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
						<?php } ?>
					  </select>
					</div>
				</div>
				<div class="form-group required">
					<label class="col-sm-2 control-label" for="input-name"><?php echo $entry_name; ?></label>
					<div class="col-sm-5">
						<input type="text" name="coupon_name" value="<?php echo $coupon_name; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control" />
						<?php if ($error_name) { ?>
						<div class="text-danger"><?php echo $error_name; ?></div>
						<?php } ?>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="input-type"><span data-toggle="tooltip" title="<?php echo $help_type; ?>"><?php echo $entry_type; ?></span></label>
					<div class="col-sm-5">
						<select name="coupon_type" id="input-type" class="form-control">
							<?php if ($coupon_type == 'P') { ?>
							<option value="P" selected="selected"><?php echo $text_percent; ?></option>
							<?php } else { ?>
							<option value="P"><?php echo $text_percent; ?></option>
							<?php } ?>
							<?php if ($coupon_type == 'F') { ?>
							<option value="F" selected="selected"><?php echo $text_amount; ?></option>
							<?php } else { ?>
							<option value="F"><?php echo $text_amount; ?></option>
							<?php } ?>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="input-discount"><?php echo $entry_discount; ?></label>
					<div class="col-sm-5">
						<input type="text" name="coupon_discount" value="<?php echo $coupon_discount; ?>" placeholder="<?php echo $entry_discount; ?>" id="input-discount" class="form-control" />
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="input-total"><span data-toggle="tooltip" title="<?php echo $help_total; ?>"><?php echo $entry_total; ?></span></label>
					<div class="col-sm-5">
						<input type="text" name="coupon_total" value="<?php echo $coupon_total; ?>" placeholder="<?php echo $entry_total; ?>" id="input-total" class="form-control" />
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="input-product"><span data-toggle="tooltip" title="Coupon Can apply Only cart products Or Entrie Products">Apply Condition</span></label>
					<div class="col-sm-5">
						<label class="radio-inline"><input name="coupon_contion" value="1" <?php if($coupon_contion){ ?> checked="checked" <?php } ?> type="radio"> Coupon apply only carts products </label> <br/>
						<label class="radio-inline"><input name="coupon_contion" <?php if(!$coupon_contion){ ?> checked="checked" <?php } ?> value="0" type="radio"> All Products </label>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="input-uses-total"><span data-toggle="tooltip" title="<?php echo $help_vaild; ?>"><?php echo $entry_vaild; ?></span></label>
					<div class="col-sm-5">
						<input type="text" name="coupon_vaild" value="<?php echo $coupon_vaild; ?>" placeholder="<?php echo $entry_vaild; ?>" id="input-uses-total" class="form-control" />
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="input-uses-total"><span data-toggle="tooltip" title="<?php echo $help_uses_total; ?>"><?php echo $entry_uses_total; ?></span></label>
					<div class="col-sm-5">
						<input type="text" name="coupon_uses_total" value="<?php echo $coupon_uses_total; ?>" placeholder="<?php echo $entry_uses_total; ?>" id="input-uses-total" class="form-control" />
					</div>
				</div>
				<div class="form-group">
						<label class="col-sm-2 control-label" for="input-uses-customer"><span data-toggle="tooltip" title="<?php echo $help_uses_customer; ?>"><?php echo $entry_uses_customer; ?></span></label>
						<div class="col-sm-5">
							<input type="text" name="coupon_uses_customer" value="<?php echo $coupon_uses_customer; ?>" placeholder="<?php echo $entry_uses_customer; ?>" id="input-uses-customer" class="form-control" />
						</div>
				</div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
	
	<!-- Modal -->
	<div id="ShortcutModal" class="modal fade" role="dialog">
		<div class="modal-dialog">

			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title"><?php echo $text_shortcuts; ?></h4>
				</div>
				<div class="modal-body">
					<div class="table-responsive">
						<table class="table table-bordered table-hover">
						  <thead>
							<tr>
								<td>
									<div class="col-sm-12">
									   <fieldset>
											<legend>Cart Products</legend>
											<p>{cart_products} = cart Products</p>
										</fieldset>
									</div>
								</td>
								<td>
									<div class="col-sm-12">
									   <fieldset>
											<legend>Coupon</legend>
											<p>{coupon} = Coupon Code</p>
											<p>{discount} = Discount Value</p>
											<p>{currency} = Currency Symbol</p>
											<p>{total_amount} = Total Amount</p>
											<p>{date_end} = Date End</p>
										</fieldset>
									</div>
								</td>
							</tr>
							<tr>
								<td>
									<div class="col-sm-12">
									   <fieldset>
											<legend>Store Information</legend>
											<p>{logo} = Store Logo</p>
											<p>{Store_name} = Store Name</p>
											<p>{Store_address} = Store Address</p>
											<p>{Store_email} = Store Email</p>
											<p>{Store_telephone} = Store Telephone</p>
											<p>{store_url} = Store Link</p>
										</fieldset>
									</div>
								</td>
								<td>
									<div class="col-sm-12">
									    <fieldset>
											<legend>Customer Information</legend>
											<p>{firstname} = First Name</p>
											<p>{lastname} = Last Name</p>
											<p>{email} = E-Mail</p>
											<p>{telephone} = Telephone</p>
										</fieldset>
									</div>
								</td>
							</tr>
						   </thead>
						 </table>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
<script type="text/javascript" src="view/javascript/summernote/summernote.js"></script>
<link href="view/javascript/summernote/summernote.css" rel="stylesheet" />
<script type="text/javascript" src="view/javascript/summernote/opencart.js"></script> 
<script type="text/javascript"><!--
$('#language a:first').tab('show');
//--></script>
<script type="text/javascript"><!--
$('input[name=\'product_name\']').autocomplete({
	source: function(request, response) {
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
	select: function(item) {
		$('input[name=\'product_name\']').val('');
		
		$('#featured-product' + item['value']).remove();
		
		$('#featured-product').append('<div id="featured-product' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="featured_product[]" value="' + item['value'] + '" /></div>');	
	}
});
	
$('#featured-product').delegate('.fa-minus-circle', 'click', function() {
	$(this).parent().remove();
});
//--></script>
<script type="text/javascript"><!--
$('input[name=\'product\']').autocomplete({
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
		$('input[name=\'product\']').val('');
		
		$('#coupon-product' + item['value']).remove();
		
		$('#coupon-product').append('<div id="coupon-product' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="coupon_product[]" value="' + item['value'] + '" /></div>');	
	}
});

$('#coupon-product').delegate('.fa-minus-circle', 'click', function() {
	$(this).parent().remove();
});

// Category
$('input[name=\'category\']').autocomplete({
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
		$('input[name=\'category\']').val('');
		
		$('#coupon-category' + item['value']).remove();
		
		$('#coupon-category').append('<div id="coupon-category' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="coupon_category[]" value="' + item['value'] + '" /></div>');
	}	
});

$('#coupon-category').delegate('.fa-minus-circle', 'click', function() {
	$(this).parent().remove();
});
//--></script>
</div>
<?php echo $footer; ?>