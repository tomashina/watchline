<div class="tab-pane active">
	<div id="LogWrapper<?php echo $store['store_id']; ?>"> 

	</div>		
</div>
<script>
    $(document).ready(function(){
		$.ajax({
			url: "index.php?route=<?php echo $module_path; ?>/getAllReviews&token=<?php echo $token; ?>&page=1&store_id=<?php echo $store['store_id']; ?>",
			type: 'get',
			dataType: 'html',
			success: function(data) {		
			    $("#LogWrapper<?php echo $store['store_id']; ?>").html(data);
			}
		});
    });
</script>