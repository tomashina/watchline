<?php echo $header; ?>
<div class="container">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <div class="row"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
      <h1><?php echo $heading_title; ?></h1>
        <?php if (isset($success)) { ?>
          <div class="success">
            <?php echo $success; ?>
          </div>
          <br />
          <?php if (isset($discount_text)) { echo $discount_text; } ?>
          <br /><br />
        <?php } else if (isset($errors)) { ?>
          <div class="warning">
            <strong><?php echo (isset($errors)) ? $errors : ''; ?></strong><br />
            <ul>
              <?php if (isset($errorsArray)) { foreach ($errorsArray as $error) { ?>
                <li><?php echo $error; ?></li>
              <?php } } ?>
            </ul>
          </div>
        <?php } ?>
        <?php if (isset($FormData)) { ?>
        <div class="row orderReviews">
			<div class="col-md-12">
				<div class="table-responsive">
					<?php echo $FormData; ?>
				</div>
			</div>
		</div>
          <script>
            $(document).ready(function() {
				<?php if ($start_rating) { ?>
					var start_rating = <?php echo $start_rating; ?>;
					var ratSelector = 'input[id^=rat]';
					$(ratSelector).each(function(){
						var values = $(this).val();
						console.log(values);
						if (values == start_rating) {
							$(this).attr("checked", "checked");
						} else {
							$(this).removeAttr('checked');
						}
					});
				<?php } ?>
				$('form table input[type=submit]').on('click', function(e) {
					if ($('label#ORPrivacyPolicy input').length > 0 && $('label#ORPrivacyPolicy input:checked').length == 0) {
						$('label#ORPrivacyPolicy').after('<div style="font-size:12px;color:#d00"><?php echo $text_privacy_error; ?></div>');
						return false;
					}
				});
				$('.orderReviews table, .orderReviews table table, .orderReviews table table table').css('width', '100%');
            });
          </script>
        <?php } else { ?>
              <div class="buttons">
               		<?php if (isset($errors)) { ?>
						<?php if ($duplicate == false) { ?>
							<a href="<?php echo $ReveiewMailLinkNotDec; ?>" class="btn btn-warning"><?php echo $button_send_again; ?></a>&nbsp;&nbsp;
						<?php } ?>
						<?php if ($history && $duplicate == false) { ?>
							<a href="<?php echo $ReveiewMailLink; ?>" class="btn btn-warning"><?php echo $button_back; ?></a>&nbsp;&nbsp;
						<?php } ?>
					<?php } ?>
              	</div>
        <?php } ?>
      <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div>
<?php echo $footer; ?>