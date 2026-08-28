<div class="tab-pane active">
	<h3>Sent Coupons</h3>
	<br>
	<div id="SentCoupons<?php echo $store['store_id']; ?>"> 

	</div>	
</div>
<script>
    $(document).ready(function(){
		$.ajax({
			url: "index.php?route=<?php echo $module_path; ?>/getAllSentCoupons&token=<?php echo $token; ?>&page=1&store_id=<?php echo $store['store_id']; ?>",
			type: 'get',
			dataType: 'html',
			success: function(data) {		
			    $("#SentCoupons<?php echo $store['store_id']; ?>").html(data);
			}
		});
    });
</script>