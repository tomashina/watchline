<div class="table-responsive">
	<table class="table table-bordered table-hover">
		<thead>
			<tr>
				<td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
				<td class="text-left sortorder"><?php if ($sort == 'cp.name') { ?>
					<a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_name; ?></a>
					<?php } else { ?>
					<a href="<?php echo $sort_name; ?>"><?php echo $column_name; ?></a>
					<?php } ?></td>
				<td class="text-left sortorder"><?php if ($sort == 'cp.code') { ?>
					<a href="<?php echo $sort_code; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_code; ?></a>
					<?php } else { ?>
					<a href="<?php echo $sort_code; ?>"><?php echo $column_code; ?></a>
					<?php } ?></td>
				<td class="text-left sortorder"><?php if ($sort == 'cp.date_start') { ?>
					<a href="<?php echo $sort_date_start; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date_start; ?></a>
					<?php } else { ?>
					<a href="<?php echo $sort_date_start; ?>"><?php echo $column_date_start; ?></a>
					<?php } ?></td>
				<td class="text-left sortorder"><?php if ($sort == 'cp.date_end') { ?>
					<a href="<?php echo $sort_date_end; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date_end; ?></a>
					<?php } else { ?>
					<a href="<?php echo $sort_date_end; ?>"><?php echo $column_date_end; ?></a>
					<?php } ?></td>
				<td class="text-left sortorder"><?php if ($sort == 'c.date_added') { ?>
					<a href="<?php echo $sort_date_added; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date_added; ?></a>
					<?php } else { ?>
					<a href="<?php echo $sort_date_added; ?>"><?php echo $column_date_added; ?></a>
					<?php } ?></td>
				<td>Action</td>
			</tr>
		</thead>
		<tbody>
			<?php if ($customers) { ?>
			<?php foreach ($customers as $customer) { ?>
			<tr id="<?php echo $customer['coupon_id']; ?>">
			    <td class="text-center"><input type="checkbox" name="selected[]" value="<?php echo $customer['coupon_id']; ?>" /></td>
				<td class="text-left"><?php echo $customer['coupon_name']; ?></td>
				<td class="text-left"><?php echo $customer['code']; ?></td>
				<td class="text-left"><?php echo $customer['date_start']; ?></td>
				<td class="text-left"><?php echo $customer['date_end']; ?></td>
				<td class="text-left"><?php echo $customer['date_added']; ?></td>
				<td class="text-left">
				<a target="_new"  href="<?php echo $customer['href']; ?>" data-toggle="tooltip" title="" class="btn btn-primary" data-original-title="Edit"><i class="fa fa-eye"></i></a>
				<a rel="<?php echo $customer['coupon_id']; ?>" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger deletecoupononebyeone"><i class="fa fa-trash-o"></i></a>
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
