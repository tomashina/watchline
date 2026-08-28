<div class="table-responsive">
	<table class="table table-bordered table-hover">
	  <thead>
		<tr>
		  <td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
		 
		  <td class="text-center customerinfo"><?php echo $column_customerifno; ?></td>
		  <td class="text-center"><?php echo $column_vistor_type; ?></td>
		  <td class="text-center"><?php echo $column_cart_products; ?></td>
		  <td class="text-center"><?php echo $entry_last_visited; ?></td>
		  <td class="text-center"><?php echo $column_ip; ?></td>
		  <td class="text-center"><?php echo $column_notify; ?></td>
		  <td class="text-center"><?php echo $column_date_added; ?></td>
		  <td class="text-right"><?php echo $column_action; ?></td>
		</tr>
	  </thead>
	  <tbody>
		<?php if ($ebcarts) { ?>
		<?php foreach ($ebcarts as $ebcart) { ?>
		<tr id="<?php echo $ebcart['ebabandonedcart_id']; ?>">
		  <td class="text-center"><?php if (in_array($ebcart['ebabandonedcart_id'], $selected)) { ?>
			<input type="checkbox" name="selected[]" value="<?php echo $ebcart['ebabandonedcart_id']; ?>" checked="checked" />
			<?php } else { ?>
			<input type="checkbox" name="selected[]" value="<?php echo $ebcart['ebabandonedcart_id']; ?>" />
			<?php } ?></td>
		  <td>
			<table class="table table-bordered">
			  <tr>
				<td><i class="fa fa-user fw"></i> <?php echo ($ebcart['name'] ? $ebcart['name'] : 'Unknown'); ?></td>
			  </tr>
			  <tr>
				<td><i class="fa fa-envelope-o"></i> <?php echo ($ebcart['email'] ? $ebcart['email'] : 'Not Provided'); ?></td>
			  </tr>
			  <tr>
				<td><i class="fa fa-phone"></i> <?php echo ($ebcart['telephone'] ? $ebcart['telephone'] : 'Not Provided'); ?></td>
			  </tr>
			  <tr>
				<td><i class="fa fa-map-marker" aria-hidden="true"></i>  <?php echo $ebcart['store']; ?></td>
			  </tr>
			  <tr>
				<td><?php echo $text_currency; ?> (<?php echo $ebcart['currency']; ?>) </td>
			  </tr>
			  <tr>
				<td><img src="language/<?php echo $ebcart['language_code']; ?>/<?php echo $ebcart['language_code']; ?>.png" title="<?php echo $ebcart['language']; ?>" /> <?php echo $ebcart['language']; ?></td>
			  </tr>
			</table>
		  </td>
		  <td class="text-center align-top"><?php echo $ebcart['visitor']; ?></td>
		  <td class="align-top">
			<table class="table table-bordered">
			 <tr>
				<th class="text-center"><?php echo $column_image; ?></th>
				<th><?php echo $column_product; ?></th>
				<th><?php echo $column_quantity; ?></th>
				<th><?php echo $column_price; ?></th>
				<th><?php echo $column_total; ?></th>
			 </tr>
			  <?php $total=0; foreach($ebcart['ebcart_products'] as $ebproduct){ ?>
				<tr>
				   <td class="text-center"><a target="_new" href="<?php echo $ebproduct['href']; ?>"><img class="img-thumbnail" src="<?php echo $ebproduct['image']; ?>"/></a></td>
				   <td>
					<a target="_new" href="<?php echo $ebproduct['href']; ?>"><?php echo $ebproduct['name']; ?></a>
					 <?php if ($ebproduct['option_data']) { ?>
					  <?php foreach ($ebproduct['option_data'] as $option) { ?>
					  <br />
					   <small> - <?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
					  <?php } ?>
					 <?php } ?>
				   </td>
				   <td><?php echo $ebproduct['quantity']; ?></td>
				   <td><?php echo $ebproduct['price']; ?></td>
				   <td><?php echo $ebproduct['total']; ?></td>
				</tr>
				<?php $total += $ebproduct['total2']; } ?>
			  <tr>
				<td class="text-right" colspan="4"><b><?php echo $column_total; ?></b></td>
				<td class="text-left"><?php echo $ebcart['cart_total']; ?></td>
			  </tr>
			</table>
		  </td>
		  <td>
			<table class="table table-bordered">
			  <tr>
				<td><a target="_new" href="<?php echo $ebcart['visit_page']; ?>">../<?php echo substr($ebcart['visit_page'],-30); ?></a></td>
			  </tr>
			  <tr>
				<td><?php echo $ebcart['visit_date']; ?></td>
			  </tr>
			</table>
			<a onclick="vistorhistory('<?php echo $ebcart['ebabandonedcart_id']; ?>');" style="cursor:pointer;" class="btn-sm btn-success pull-right">View History</a>
		  </td>
		  <td><?php echo $ebcart['ip']; ?><br/> <br/> <a target="_blank" class="btn btn-sm btn-info" href="<?php echo $ebcart['ip_href']; ?>"><i class="fa fa-search" aria-hidden="true"></i> Check ip</a></td>
		  <td class="text-center"><?php echo $ebcart['notify_status']; ?></td>
		  <td><?php echo $ebcart['date_added']; ?></td>
		  <td style="width: 115px;">
			<a <?php echo (!$ebcart['email'] ? 'disabled="disabled"' : ''); ?> data-toggle="tooltip" data-original-title="Notify Customer" rel="<?php echo $ebcart['ebabandonedcart_id']; ?>" class="btn btn-info <?php echo ($ebcart['email'] ? 'sendnotify"' : ''); ?> "><i class="fa fa-envelope"></i></a> 
			<a data-toggle="tooltip" data-original-title="Delete Record" rel="<?php echo $ebcart['ebabandonedcart_id']; ?>" class="btn btn-danger deletecart"><i class="fa fa-trash-o"></i></a>
		  </td>
		</tr>
		<?php } ?>
		<?php } else { ?>
		<tr>
		  <td class="text-center" colspan="9"><?php echo $text_no_results; ?></td>
		</tr>
		<?php } ?>
	  </tbody>
	</table>
</div>
<div class="row">
  <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
  <div class="col-sm-6 text-right"><?php echo $results; ?></div>
</div>
<style>
.text-center.customerinfo {
    width: 254px;
}
</style>