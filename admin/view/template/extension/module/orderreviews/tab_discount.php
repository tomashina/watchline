<div class="tab-pane">
<div class="row">
    <div class="col-md-3">
        <h5><strong>Type of discount:</strong></h5>
        <span class="help"><i class="fa fa-info-circle"></i>&nbsp;If you choose the option 'No discount', you will have to remove the following codes from the mail template: {discount_code}, {discount_value}, {total_amount} and {date_end}.</span>
    </div>
    <div class="col-md-3">
        <select name="<?php echo $reviewmail_name; ?>[DiscountType]" id="DiscountType_<?php echo $reviewmail['id']; ?>" class="discountTypeSelect form-control">
            <option value="P" <?php if(!empty($reviewmail_data['DiscountType']) && $reviewmail_data['DiscountType'] == "P") echo "selected"; ?>>Percentage</option>
            <option value="F" <?php if(!empty($reviewmail_data['DiscountType']) && $reviewmail_data['DiscountType'] == "F") echo "selected"; ?>>Fixed amount</option>
            <option value="N" <?php if(empty($reviewmail_data['DiscountType']) || $reviewmail_data['DiscountType'] == "N") echo "selected"; ?>>No discount</option>
        </select>
    </div>
</div>
<br />
<div class="discountSettings">
    <div class="row">
        <div class="col-md-3">
            <h5><strong><span class="required">* </span>Discount:</strong></h5>
            <span class="help"><i class="fa fa-info-circle"></i>&nbsp;Enter the discount percent or value.</span>
        </div>
        <div class="col-md-3">
            <div class="input-group">
                <input type="text" class="form-control" name="<?php echo $reviewmail_name; ?>[Discount]" value="<?php if(!empty($reviewmail_data['Discount'])) echo $reviewmail_data['Discount']; else echo '10'; ?>">
                <span class="input-group-addon">
                <span style="display:none;" id="currencyAddon"><?php echo $currency; ?></span><span style="display:none;" id="percentageAddon">%</span>
                </span>
            </div>
        </div>
    </div>
    <br />
    <div class="row">
        <div class="col-md-3">
            <h5><strong><span class="required">* </span>Total amount:</strong></h5>
            <span class="help"><i class="fa fa-info-circle"></i>&nbsp;The total amount that must reached before the coupon is valid.</span>
        </div>
        <div class="col-md-3">
            <div class="input-group">
                <input type="text" class="form-control" name="<?php echo $reviewmail_name; ?>[TotalAmount]" value="<?php if(!empty($reviewmail_data['TotalAmount'])) echo $reviewmail_data['TotalAmount']; else echo '20'; ?>">
                <span class="input-group-addon"><?php echo $currency ?></span>
            </div>
        </div>
    </div>
    <br />
	<div class="row">
		<div class="col-sm-3">
			<h5><strong>Products</strong></h5>
			<span class="help"><i class="fa fa-info-circle"></i>&nbsp;Choose specific products the coupon will apply to. Select no products to apply coupon to entire cart.</span>
		</div>
		<div class="col-sm-9">
			<input type="text" name="products" value="" placeholder="Products" id="input-product" class="form-control" />
			<div id="products_<?php echo $reviewmail['id']; ?>" class="well well-sm" style="height: 150px; overflow: auto;">
				<?php if (isset($reviewmail_data['products'])) { foreach ($reviewmail_data['products'] as $product) { ?>
					<div id="products_<?php echo $reviewmail['id']; ?>_<?php echo $product['product_id']; ?>"><i class="fa fa-minus-circle"></i> <?php echo $product['name']; ?>
						<input type="hidden" name="<?php echo $reviewmail_name; ?>[products][]" value="<?php echo $product['product_id']; ?>" />
					</div>
				<?php } } ?>
			</div>
		</div>
	</div>
	<br />
	<div class="row">
		<div class="col-sm-3">
			<h5><strong>Categories</strong></h5>
			<span class="help"><i class="fa fa-info-circle"></i>&nbsp;Choose all products under selected category.</span>
		</div>
		<div class="col-sm-9">
			<input type="text" name="categories" value="" placeholder="Categories" id="input-category" class="form-control" />
			<div id="categories_<?php echo $reviewmail['id']; ?>" class="well well-sm" style="height: 150px; overflow: auto;">
				<?php if (isset($reviewmail_data['categories'])) { foreach ($reviewmail_data['categories'] as $category) { ?>
				<div id="categories_<?php echo $reviewmail['id']; ?>_<?php echo $category['category_id']; ?>"><i class="fa fa-minus-circle"></i> <?php echo $category['name']; ?>
					<input type="hidden" name="<?php echo $reviewmail_name; ?>[categories][]" value="<?php echo $category['category_id']; ?>" />
				</div>
				<?php } } ?>
			</div>
		</div>
	</div>
	<br />
    <div class="row">
        <div class="col-md-3">
            <h5><strong><span class="required">* </span>Discount validity:</strong></h5>
            <span class="help"><i class="fa fa-info-circle"></i>&nbsp;Define how many days the discount code will be active after sending the reminder.</span>
        </div>
        <div class="col-md-3">
            <div class="input-group">
                <input type="text" class="form-control" value="<?php if(!empty($reviewmail_data['DiscountValidity'])) echo (int)$reviewmail_data['DiscountValidity']; else echo 7; ?>" name="<?php echo $reviewmail_name; ?>[DiscountValidity]">
                <span class="input-group-addon">days</span>
            </div>
        </div>
    </div>
    <br />
    <div class="row">
         <div class="col-md-3">
            <h5><strong>Discount mail status:</strong></h5>
            <span class="help"><i class="fa fa-info-circle"></i>&nbsp;The customer will receive information about his discount after he submits a review directly in the success page. If you enable this option, the customer will also receive an email with the discount information.</span>
         </div>
         <div class="col-md-3">
            <select id="Checker_<?php echo $reviewmail['id']; ?>" name="<?php echo $reviewmail_name; ?>[DiscountMailEnabled]" class="discountMailSelect form-control">
               <option value="yes" <?php echo (!empty($reviewmail_data['DiscountMailEnabled']) && $reviewmail_data['DiscountMailEnabled'] == 'yes') ? 'selected=selected' : '' ?>>Enabled</option>
               <option value="no"  <?php echo (empty($reviewmail_data['DiscountMailEnabled']) || $reviewmail_data['DiscountMailEnabled']== 'no') ? 'selected=selected' : '' ?>>Disabled</option>
            </select>
         </div>
      </div>
</div>
</div>
<script>
   $(function() {
      var $typeSelector = $('#Checker_<?php echo $reviewmail['id']; ?>');
      var $toggleArea = $('#discountMailTab_<?php echo $reviewmail['id']; ?>');
    if ($typeSelector.val() === 'yes') {
              $toggleArea.show(200); 
          }
          else {
              $toggleArea.hide(200); 
          }
      $typeSelector.change(function(){
          if ($typeSelector.val() === 'yes') {
              $toggleArea.show(200); 
          }
          else {
              $toggleArea.hide(200); 
          }
      });
   });
   
   $(function() {
      var $typeSelector = $('#DiscountType_<?php echo $reviewmail['id']; ?>');
      var $toggleArea = $('#discountMailTab_<?php echo $reviewmail['id']; ?>');
	  var $toggleArea2 = $('#Checker_<?php echo $reviewmail['id']; ?>');
    if ($typeSelector.val() === 'N') {
              $toggleArea.hide(200); 
			  $toggleArea2.val('no');
          }
      $typeSelector.change(function(){
          if ($typeSelector.val() === 'N') {
              $toggleArea.hide(200); 
			  $toggleArea2.val('no');
          }
      });
   });
   
	// Category
	$('input[name="categories"]').autocomplete({
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
			var method = 'categories';

			$(this).val('');
			$('#' + method +'_<?php echo $reviewmail["id"]; ?>_' + item['value']).remove();
			$('#' + method +'_<?php echo $reviewmail["id"]; ?>').append('<div id="' + method + '_<?php echo $reviewmail["id"]; ?>_' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="<?php echo $reviewmail_name; ?>[' + method + '][]" value="' + item['value'] + '" /></div>');
		}
	});

	$('#categories_<?php echo $reviewmail["id"]; ?>').delegate('.fa-minus-circle', 'click', function() {
		$(this).parent().remove();
	});

	// Products
	$('input[name="products"]').autocomplete({
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
			var method = 'products';

			$(this).val('');
			$('#' + method +'_<?php echo $reviewmail["id"]; ?>_' + item['value']).remove();
			$('#' + method +'_<?php echo $reviewmail["id"]; ?>').append('<div id="' + method + '_<?php echo $reviewmail["id"]; ?>_' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="<?php echo $reviewmail_name; ?>[' + method + '][]" value="' + item['value'] + '" /></div>');
		}
	});
	$('#products_<?php echo $reviewmail["id"]; ?>').delegate('.fa-minus-circle', 'click', function() {
		$(this).parent().remove();
	});
</script>