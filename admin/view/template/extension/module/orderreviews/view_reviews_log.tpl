<table class="table table-bordered table-hover" width="100%" >
      <thead>
        <tr class="table-header">
          <td class="left" width="15%"><strong>Order ID</strong></td>
          <td class="left" width="20%"><strong>Customer Name</strong></td>
          <td class="left" width="20%"><strong>Product Name</strong></td>
          <td class="left" width="10%"><strong>Review Rating</strong></td>
          <td class="left" width="15%"><strong>Review Coupon</strong></td>
          <?php if (!empty($setting['PrivacyPolicy'])) { ?>
            <td width="10%"><?php echo $text_privacy_policy; ?></td>
          <?php } ?>
          <td class="left" width="20%"><strong>Date Added</strong></td>
        </tr>
      </thead>
	<?php if (!empty($sources)) { ?>
		<?php $i=0; foreach ($sources as $src) { ?>              
              <tbody>
      				<tr>
                  <td>
                    <button type="button" class="btn btn-default btn-sm disabled" tabindex="-1"><?php echo $src['order_id']; ?></button>
                    <a href="index.php?route=sale/order/info&token=<?php echo $token; ?>&order_id=<?php echo $src['order_id']; ?>" target="_blank" class="btn btn-default btn-sm btn-info">Order details</a>
                  </td>
                  <td class="left">
                    <?php echo $src['customer_name']?>
                  </td>
                  <td class="left">
                  	<a href="<?php echo $src['url']?>"><?php echo $src['name']?></a>
                  </td>
  			          <td class="left">
                  	<?php echo $src['review_rating']?>
                  </td>
                   <td class="left">
                    <?php echo !empty($src['review_coupon']) ? $src['review_coupon'] :'Not Provided' ?>
                  </td>
                  <?php if (!empty($setting['PrivacyPolicy'])) { ?>
                      <td class="text-center"><?php echo $src['privacy_policy'] ? $text_agree : ''; ?></td>
                  <?php } ?>
                  <td class="left">
                  	<?php echo $src['date_created']?>
                  </td>
                </tr>
              </tbody>
        <?php } ?>
	<?php } ?>
    <tfoot>
    	<tr>
        	<td colspan="10">
                <div class="row">
                  <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
                  <div class="col-sm-6 text-right"><?php echo $results; ?></div>
                </div>
        	</td>
        </tr>
    </tfoot>
</table>
<script>
$(document).ready(function(){
	$('#LogWrapper<?php echo $store_id; ?> .pagination a').click(function(e){
		e.preventDefault();
		$.ajax({
			url: this.href,
			type: 'get',
			dataType: 'html',
			success: function(data) {				
				$("#LogWrapper<?php echo $store_id; ?>").html(data);
			}
		});
	});		 
});
</script>
