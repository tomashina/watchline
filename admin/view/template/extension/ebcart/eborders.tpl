<div class="table-responsive">
	<table class="table table-bordered table-hover">
	  <thead>
		<tr>
		  <td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
		  <td class="text-center">Order <?php echo $column_id; ?></td>
		  <td class="text-center"><?php echo $column_customerifno; ?></td>
		  <td class="text-center"><?php echo $column_vistor_type; ?></td>
		  <td class="text-center">Order Products</td>
		  <td class="text-center"><?php echo $column_ip; ?></td>
		  <td class="text-center"><?php echo $column_date_added; ?></td>
		  <td class="text-right"><?php echo $column_action; ?></td>
		</tr>
	  </thead>
	  <tbody>
		<?php if ($orders) { ?>
		<?php foreach ($orders as $order) { ?>
		<tr id="<?php echo $order['order_id']; ?>">
		  <td class="text-center">
			<input type="checkbox" name="selected[]" value="<?php echo $order['order_id']; ?>" />
			</td>
		  <td class="text-center"><?php echo $order['order_id']; ?></td>
		  <td>
			<table class="table table-bordered">
			  <tr>
				<td><i class="fa fa-user fw"></i> <?php echo $order['firstname'].' '.$order['lastname']; ?></td>
			  </tr>
			  <tr>
				<td><i class="fa fa-envelope-o"></i> <?php echo $order['email']; ?></td>
			  </tr>
			  <tr>
				<td><i class="fa fa-phone"></i> <?php echo $order['telephone']; ?></td>
			  </tr>
			  <tr>
				<td><i class="fa fa-map-marker" aria-hidden="true"></i>  <?php echo $order['store']; ?></td>
			  </tr>
			  <tr>
				<td><?php echo $text_currency; ?> (<?php echo $order['currency']; ?>) </td>
			  </tr>
			  <tr>
				<td><img src="language/<?php echo $order['language_code']; ?>/<?php echo $order['language_code']; ?>.png" title="<?php echo $order['language_code']; ?>" /> <?php echo $order['language_code']; ?></td>
			  </tr>
			</table>
		  </td>
		  <td class="text-center"><?php echo $order['visitor']; ?></td>
		  <td>
			<table class="table table-bordered">
			 <tr>
				<th class="text-center"><?php echo $column_image; ?></th>
				<th><?php echo $column_product; ?></th>
				<th><?php echo $column_quantity; ?></th>
				<th><?php echo $column_price; ?></th>
				<th><?php echo $column_total; ?></th>
			 </tr>
			  <?php foreach($order['products'] as $ebproduct){ ?>
				<tr>
				   <td class="text-center"><a href="<?php echo $ebproduct['href']; ?>"><img class="img-thumbnail" src="<?php echo $ebproduct['image']; ?>"/></a></td>
				   <td>
					<a href="<?php echo $ebproduct['href']; ?>"><?php echo $ebproduct['name']; ?></a>
					 <?php if ($ebproduct['option']) { ?>
					  <?php foreach ($ebproduct['option'] as $option) { ?>
					  <br />
					   <small> - <?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
					  <?php } ?>
					 <?php } ?>
				   </td>
				   <td><?php echo $ebproduct['quantity']; ?></td>
				   <td><?php echo $ebproduct['price']; ?></td>
				   <td><?php echo $ebproduct['total']; ?></td>
				</tr>
			  <?php } ?>
			  <tr>
				<td class="text-right" colspan="4"><b><?php echo $column_total; ?></b></td>
				<td class="text-left"><?php echo $order['total']; ?></td>
			  </tr>
			</table>
		  </td>
		  <td><?php echo $order['ip']; ?><br/> <br/> <a target="_blank" class="btn-sm btn-info" href="<?php echo $order['ip_href']; ?>"><i class="fa fa-search" aria-hidden="true"></i> Check ip</a></td>
		  <td><?php echo $order['date_added']; ?></td>
		  <td><a data-toggle="tooltip" title="" data-original-title="View" class="btn btn-primary" href="<?php echo $order['href']; ?>"><i class="fa fa-eye"></i></a></td>
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