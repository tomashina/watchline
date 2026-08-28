<table class="table table-bordered table-hover" width="100%" >
      <thead>
        <tr class="table-header">
          <td class="left" width="20%"><strong>Coupon Name</strong></td>
          <td class="left" width="10%"><strong>Code</strong></td>
          <td class="left" width="5%"><strong>Discount</strong></td>
          <td class="left" width="15%"><strong>Date Start</strong></td>
          <td class="left" width="14%"><strong>Date End</strong></td>
           <td class="left" width="20%"><strong>Status</strong></td>
            <td class="left" width="5%"><strong>Used</strong></td>
           <td class="left" width="20%"><strong>Date Added</strong></td>
        </tr>
      </thead>
      <?php if (!empty($sources)) { ?>
        <?php $i=0; foreach ($sources as $src) { ?>              
                  <tbody>
                  <tr>
                    <td>
                      <?php echo $src['name'] ?>
                    </td>
                    <td class="left">
                      <?php echo $src['code'] ?>
                    </td>
                    <td class="left">
                      <?php echo $src['discount'] ?>
                    </td>
                    <td class="left">
                      <?php echo $src['date_start'] ?>
                    </td>
                    <td class="left">
                      <?php echo $src['date_end'] ?>
                    </td>
                     <td class="left">
                      <?php if($src['status']){ ?>
                        Enabled 
                        <?php }else { ?>
                        Disabled
                        <?php } ?>
                    </td>
                    <td class="left">
                      <?php if ($src['used'] > 0){ ?>
                        YES
                      <?php } else { ?>
                        NO
                      <?php } ?>
                    </td>
                    <td class="left">
                      <?php echo $src['date_added'] ?>
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
  $('#SentCoupons<?php echo $store_id; ?> .pagination a').click(function(e){
    e.preventDefault();
    $.ajax({
      url: this.href,
      type: 'get',
      dataType: 'html',
      success: function(data) {       
        $("#SentCoupons<?php echo $store_id; ?>").html(data);
      }
    });
  });    
});
</script>