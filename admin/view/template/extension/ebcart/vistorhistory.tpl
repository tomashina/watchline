<div id="vistorhistory" class="modal fade" role="dialog">
  <div class="modal-dialog">
	<!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Visitor History</h4>
      </div>
      <div class="modal-body">
        <table class="table table-bordered">
		 <tr>
			<th>Page</th>
			<th>Date & Time</th>
			<th>No. of Visit</th>
		 </tr>
		<?php foreach($visitor_hisorys as $history): ?>
		  <tr>
			<td><a target="_new" href="<?php echo $history['visit_page']; ?>">../<?php echo substr($history['visit_page'],-30); ?></a></td>
			<td><?php echo $history['visit_date']; ?></td>
			<td><?php echo $history['total_count']; ?></td>
		  </tr>
		 <?php endforeach; ?>
		</table>
      </div>
    </div>
  </div>
</div>