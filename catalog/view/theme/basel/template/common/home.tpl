<?php echo $header; ?>
<div class="container">
  <div class="row"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-md-9 col-sm-8'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
	<main id="content" class="<?php echo $class; ?>">
	  <h1 id="page-title" class="home-heading text-center"><?php echo htmlspecialchars(isset($heading_title) ? $heading_title : 'Satovi, sunčane naočale i nakit – Watch Line', ENT_QUOTES, 'UTF-8'); ?></h1>
	  <?php echo $content_top; ?><?php echo $content_bottom; ?>
	</main>
    <?php echo $column_right; ?></div>
</div>
<?php echo $footer; ?>
