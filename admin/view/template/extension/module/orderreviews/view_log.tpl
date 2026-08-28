<table id="LogWrapper<?php echo $store_id; ?>" class="table table-bordered table-hover" width="100%" >
	<thead>
		<tr class="table-header">
			<td width="2%"><input type="checkbox" onclick="$('input[class*=\'select-to-remove\']').attr('checked', this.checked);" /></td>
			<td class="left" width="14%"><strong><?php echo $text_order_id; ?></strong></td>
			<td class="left" width="19%"><strong><?php echo $text_customer; ?></strong></td>
			<td class="left" width="18%"><strong><?php echo $text_email; ?></strong></td>
			<td class="left" width="14%"><strong><?php echo $text_date; ?></strong></td>
		</tr>
	</thead>
	<?php if (!empty($sources)) { ?>
		<?php foreach ($sources as $src) { ?>
			<tbody>
				<tr>
					<td><input class="select-to-remove" type="checkbox" data-orderreviews-logid="<?php echo $src['id']; ?>"></td>
					<td class="left">
						<button type="button" class="btn btn-default btn-sm disabled" tabindex="-1"><?php echo $src['order_id']; ?></button>
						<a href="index.php?route=sale/order/info&token=<?php echo $token; ?>&order_id=<?php echo $src['order_id']; ?>" target="_blank" class="btn btn-default btn-sm btn-info">Order details</a>
					</td>
					<td class="left"><?php echo isset($src['order_data']['firstname']) ? $src['order_data']['firstname'] : ''?> <?php echo isset($src['order_data']['lastname']) ? $src['order_data']['lastname'] : ''?></td>
					<td class="left"><?php echo isset($src['order_data']['email']) ? $src['order_data']['email'] : ''?></td>
					<td class="left"><?php echo $src['date']?></td>
				</tr>
			</tbody>
		<?php } ?>
	<?php } ?>
    <tfoot>
    	<tr>
        	<td colspan="5">
                <div class="row">
					<div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
					<div class="col-sm-6 text-right"><?php echo $results; ?></div>
                </div>
        	</td>
        </tr>
    </tfoot>
</table>
<div class="row">
	<div class="col-md-12">
		<a id="btn-remove" class="btn btn-danger pull-right"><?php echo $text_remove; ?></a>
	</div>
</div>
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

//Remove log entries
$('#btn-remove').on('click', function(){
    var selected_log_entries = [];
    var store_id = <?php echo $store_id; ?>;
    $("input:checkbox[class=select-to-remove]:checked").each(function(){
        selected_log_entries.push($(this).attr('data-orderreviews-logid') );
    });
    $.ajax({
      url: 'index.php?route=<?php echo $module_path; ?>/deleteLogEntry&token=<?php echo $token; ?>',
      type:'post',
      data: {selected_log_entries: selected_log_entries, store_id:store_id},
      success:function(){
        location.reload();
      }
    });    
});
</script>