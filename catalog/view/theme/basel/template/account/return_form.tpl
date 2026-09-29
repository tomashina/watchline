<?php echo $header; ?>
<div class="container">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"> <?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <?php if ($error_warning) { ?>
  <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?></div>
  <?php } ?>
  <div class="row"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-md-9 col-sm-8'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
      <h1 id="page-title"><?php echo $heading_title; ?></h1>
      <p class="margin-b20"><?php echo $text_description; ?></p>
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" class="form-horizontal">
        <fieldset class="margin-b15">
          <legend><?php echo $text_order; ?></legend>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-firstname"><?php echo $entry_firstname; ?></label>
            <div class="col-sm-10">
              <input type="text" name="firstname" value="<?php echo $firstname; ?>" placeholder="<?php echo $entry_firstname; ?>" id="input-firstname" class="form-control" />
              <?php if ($error_firstname) { ?>
              <div class="text-danger"><?php echo $error_firstname; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-lastname"><?php echo $entry_lastname; ?></label>
            <div class="col-sm-10">
              <input type="text" name="lastname" value="<?php echo $lastname; ?>" placeholder="<?php echo $entry_lastname; ?>" id="input-lastname" class="form-control" />
              <?php if ($error_lastname) { ?>
              <div class="text-danger"><?php echo $error_lastname; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-email"><?php echo $entry_email; ?></label>
            <div class="col-sm-10">
              <input type="text" name="email" value="<?php echo $email; ?>" placeholder="<?php echo $entry_email; ?>" id="input-email" class="form-control" />
              <?php if ($error_email) { ?>
              <div class="text-danger"><?php echo $error_email; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-telephone"><?php echo $entry_telephone; ?></label>
            <div class="col-sm-10">
              <input type="text" name="telephone" value="<?php echo $telephone; ?>" placeholder="<?php echo $entry_telephone; ?>" id="input-telephone" class="form-control" />
              <?php if ($error_telephone) { ?>
              <div class="text-danger"><?php echo $error_telephone; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-invoice-number"><?php echo $entry_invoice_number; ?></label>
            <div class="col-sm-10">
              <input type="text" name="invoice_number" value="<?php echo $invoice_number; ?>" placeholder="<?php echo $entry_invoice_number; ?>" id="input-invoice-number" class="form-control" />
              <?php if ($error_order_id) { ?>
              <div class="text-danger"><?php echo $error_order_id; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-invoice-date"><?php echo $entry_invoice_date; ?></label>
            <div class="col-sm-3">
              <div class="input-group date"><input type="text" name="invoice_date" value="<?php echo $invoice_date; ?>" placeholder="<?php echo $entry_invoice_date; ?>" data-date-format="YYYY-MM-DD" id="input-invoice-date" class="form-control" /><span class="input-group-btn">
                <button type="button" class="btn btn-outline"><i class="fa fa-calendar"></i></button>
                </span></div>
              <?php if ($error_date_ordered) { ?>
              <div class="text-danger"><?php echo $error_date_ordered; ?></div>
              <?php } ?>
            </div>
          </div>
        </fieldset>
        <fieldset>
          <legend><?php echo $text_product; ?></legend>
          <input type="hidden" name="product_id" value="<?php echo $product_id; ?>" />
          <div class="form-group required">
            <label class="col-sm-2 control-label"><?php echo $text_return_products_title; ?></label>
            <div class="col-sm-10">
              <div class="table-responsive">
                <table id="return-product" class="table table-bordered">
                  <thead>
                    <tr>
                      <td><?php echo $entry_product_code; ?></td>
                      <td><?php echo $entry_quantity; ?></td>
                      <td><?php echo $entry_price; ?></td>
                      <td></td>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($return_products as $product_row => $return_product) { ?>
                    <tr>
                      <td><input type="text" name="return_products[<?php echo $product_row; ?>][code]" value="<?php echo $return_product['code']; ?>" placeholder="<?php echo $entry_product_code; ?>" class="form-control" /></td>
                      <td><input type="text" name="return_products[<?php echo $product_row; ?>][quantity]" value="<?php echo $return_product['quantity']; ?>" placeholder="<?php echo $entry_quantity; ?>" class="form-control" /></td>
                      <td><input type="text" name="return_products[<?php echo $product_row; ?>][price]" value="<?php echo $return_product['price']; ?>" placeholder="<?php echo $entry_price; ?>" class="form-control" /></td>
                      <td class="text-center"><button type="button" class="btn btn-outline button-remove-return-product" title="<?php echo $button_remove; ?>"><i class="fa fa-minus-circle"></i></button></td>
                    </tr>
                    <?php } ?>
                  </tbody>
                  <tfoot>
                    <tr>
                      <td colspan="3"></td>
                      <td class="text-center"><button type="button" id="button-return-product" class="btn btn-outline" title="<?php echo $button_add_product; ?>"><i class="fa fa-plus-circle"></i></button></td>
                    </tr>
                  </tfoot>
                </table>
              </div>
              <?php if ($error_return_products) { ?>
              <div class="text-danger"><?php echo $error_return_products; ?></div>
              <?php } ?>
            </div>
          </div>
		  <div class="form-group">
			<label class="col-sm-2 control-label"><?php echo $entry_reason; ?></label>
            <div class="col-sm-10">
              <?php foreach ($return_reasons as $return_reason) { ?>
              <?php if ($return_reason['return_reason_id'] == $return_reason_id) { ?>
              <div class="radio">
                <label>
                  <input type="radio" name="return_reason_id" value="<?php echo $return_reason['return_reason_id']; ?>" checked="checked" />
                  <?php echo $return_reason['name']; ?></label>
              </div>
              <?php } else { ?>
              <div class="radio">
                <label>
                  <input type="radio" name="return_reason_id" value="<?php echo $return_reason['return_reason_id']; ?>" />
                  <?php echo $return_reason['name']; ?></label>
              </div>
              <?php  } ?>
              <?php  } ?>
              <?php if ($error_reason) { ?>
              <div class="text-danger"><?php echo $error_reason; ?></div>
              <?php } ?>
            </div>
          </div>
		  <div class="form-group">
			<label class="col-sm-2 control-label" for="input-refund-iban"><?php echo $entry_refund_iban; ?></label>
            <div class="col-sm-10">
              <input type="text" name="refund_iban" value="<?php echo $refund_iban; ?>" placeholder="<?php echo $entry_refund_iban; ?>" id="input-refund-iban" class="form-control" />
              <?php if ($error_refund_iban) { ?>
              <div class="text-danger"><?php echo $error_refund_iban; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-comment"><?php echo $entry_fault_detail; ?></label>
            <div class="col-sm-10">
              <textarea name="comment" rows="10" placeholder="<?php echo $entry_fault_detail; ?>" id="input-comment" class="form-control"><?php echo $comment; ?></textarea>
            </div>
          </div>
          <?php echo $captcha; ?>
        </fieldset>
        <?php if ($text_agree) { ?>
        <div class="buttons clearfix">
          <div class="pull-left"><a href="<?php echo $back; ?>" class="btn btn-danger"><?php echo $button_back; ?></a></div>
          <div class="pull-right"><?php echo $text_agree; ?>
            <?php if ($agree) { ?>
            <input type="checkbox" name="agree" value="1" checked="checked" />
            <?php } else { ?>
            <input type="checkbox" name="agree" value="1" />
            <?php } ?>
            <input type="submit" value="<?php echo $button_submit; ?>" class="btn btn-primary" />
          </div>
        </div>
        <?php } else { ?>
        <div class="buttons clearfix text-right">
            <input type="submit" value="<?php echo $button_submit; ?>" class="btn btn-contrast" />
        </div>
        <?php } ?>
      </form>
      <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div>
<script><!--
$('.date').datetimepicker({
	pickTime: false
});

var returnProductRow = <?php echo count($return_products); ?>;

$('#button-return-product').on('click', function() {
	html  = '<tr>';
	html += '  <td><input type="text" name="return_products[' + returnProductRow + '][code]" value="" placeholder="<?php echo addslashes($entry_product_code); ?>" class="form-control" /></td>';
	html += '  <td><input type="text" name="return_products[' + returnProductRow + '][quantity]" value="" placeholder="<?php echo addslashes($entry_quantity); ?>" class="form-control" /></td>';
	html += '  <td><input type="text" name="return_products[' + returnProductRow + '][price]" value="" placeholder="<?php echo addslashes($entry_price); ?>" class="form-control" /></td>';
	html += '  <td class="text-center"><button type="button" class="btn btn-outline button-remove-return-product" title="<?php echo addslashes($button_remove); ?>"><i class="fa fa-minus-circle"></i></button></td>';
	html += '</tr>';

	$('#return-product tbody').append(html);

	returnProductRow++;
});

$('#return-product').on('click', '.button-remove-return-product', function() {
	if ($('#return-product tbody tr').length > 1) {
		$(this).closest('tr').remove();
	} else {
		$(this).closest('tr').find('input').val('');
	}
});
//--></script>
<?php echo $footer; ?>
