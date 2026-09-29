<div id="payment" class="text-left" style="display:none;"></div>
<div class="terms">
  <div class="legal-guarantee-checkout">
    <a href="<?php echo $legal_guarantee_url; ?>" data-toggle="modal" data-target="#legal-guarantee-modal" aria-controls="legal-guarantee-modal" aria-haspopup="dialog"><i class="fa fa-shield" aria-hidden="true"></i><?php echo $text_legal_guarantee; ?></a>
    <a href="<?php echo $withdrawal_url; ?>" target="_blank" rel="noopener"><i class="fa fa-undo" aria-hidden="true"></i><?php echo $text_withdrawal_14_days; ?></a>
  </div>
  <label><?php if ($text_agree) { ?>
    <?php echo $text_agree; ?>
    <input type="checkbox" name="agree" value="1" />
  <?php } ?></label>
  <button type="button" id="button-payment-method" class="btn btn-primary" data-loading-text="<?php echo $text_loading; ?>"><?php echo $button_continue; ?></button>
</div>
