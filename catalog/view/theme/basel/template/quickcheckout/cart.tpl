<table class="quickcheckout-cart">
	<thead>
		<tr>
		  <td class="image"><?php echo $column_image; ?></td>
		  <td class="name"><?php echo $column_name; ?></td>
		  <td class="quantity"><?php echo $column_quantity; ?></td>
		  <td class="price1 hidden-xs hidden-sm"><?php echo $column_price; ?></td>
		  <td class="total text-right"><?php echo $column_total; ?></td>
		</tr>
	</thead>
    <?php if ($products || $vouchers) { ?>
	<tbody>
        <?php foreach ($products as $product) { ?>
        <tr>
          <td class="image"><?php if ($product['thumb']) { ?>
            <a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" /></a>
            <?php } ?></td>
          <td class="name"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>
            <div>
              <?php foreach ($product['option'] as $option) { ?>
              <small><?php echo $option['name']; ?> <?php echo $option['value']; ?></small><br />
              <?php } ?>
			  <?php if ($product['reward']) { ?>
			  <br />
			  <small><?php echo $product['reward']; ?></small>
			  <?php } ?>
			  <?php if ($product['recurring']) { ?>
			  <br />
			  <span class="label label-info"><?php echo $text_recurring_item; ?></span> <small><?php echo $product['recurring']; ?></small>
			  <?php } ?>
            </div></td>
          <td class="quantity"><?php if ($edit_cart) { ?>
            <div class="qty-block">
		      <input type="text" name="quantity[<?php echo $product['key']; ?>]" size="1" value="<?php echo $product['quantity']; ?>" class="form-control" />
				<a data-toggle="tooltip" title="<?php echo $button_update; ?>" class="button-update"><i class="fa fa-refresh"></i></a>
				<a data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="button-remove" data-remove="<?php echo $product['key']; ?>"><i class="fa fa-times-circle"></i></a>
			</div>
			<?php } else { ?>
			x&nbsp;<?php echo $product['quantity']; ?>
			<?php } ?></td>
		  <td class="price1 hidden-xs hidden-sm"><?php echo $product['price']; ?></td>
          <td class="total text-right"><?php echo $product['total']; ?></td>
        </tr>
        <?php } ?>
        <?php foreach ($vouchers as $voucher) { ?>
        <tr>
          <td class="image"></td>
          <td class="name"><?php echo $voucher['description']; ?></td>
          <td class="quantity">x&nbsp;1</td>
		  <td class="price1"><?php echo $voucher['amount']; ?></td>
          <td class="total text-right"><?php echo $voucher['amount']; ?></td>
        </tr>
        <?php } ?>
        </tbody>
       <?php } ?>
     </table>

<table class="table table-bordered total-list margin-b0">
	<tbody>
		<?php foreach ($totals as $total) { ?>
			<tr>
				<td class="text-right" colspan="4"><b><?php echo $total['title']; ?>:</b></td>
				<td class="text-right"><?php echo $total['text']; ?></td>
			</tr>
        <?php } ?>
	</tbody>
</table>