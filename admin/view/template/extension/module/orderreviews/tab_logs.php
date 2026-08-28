<div class="tab-pane active">
   <h3><?php echo $text_mails_log; ?></h3>
   <br>
   <div id="SentEmails<?php echo $store['store_id']; ?>"> 

   </div>   
</div>
<script>
    $(document).ready(function(){
      $.ajax({
         url: "index.php?route=<?php echo $module_path; ?>/getlog&token=<?php echo $token; ?>&page=1&store_id=<?php echo $store['store_id']; ?>",
         type: 'get',
         dataType: 'html',
         success: function(data) {     
             $("#SentEmails<?php echo $store['store_id']; ?>").html(data);
         }
      });
    });
</script>