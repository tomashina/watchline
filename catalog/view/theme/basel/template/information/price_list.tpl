<?php echo $header; ?>
<div class="container">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <div class="row">
    <?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-md-9 col-sm-8'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="<?php echo $class; ?>">
      <?php echo $content_top; ?>
      <h1 id="page-title"><?php echo $heading_title; ?></h1>
      <p><?php echo $text_intro; ?></p>
      <?php if ($publications) { ?>
      <div class="table-responsive">
        <table class="table table-bordered table-hover price-list-archive">
          <thead>
            <tr>
              <th><?php echo $column_location; ?></th>
              <th><?php echo $column_published; ?></th>
              <th><?php echo $column_products; ?></th>
              <th><?php echo $column_file; ?></th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($publications as $publication) { ?>
            <tr>
              <td><strong><?php echo htmlspecialchars($publication['location_name'], ENT_QUOTES, 'UTF-8'); ?></strong></td>
              <td><?php echo htmlspecialchars($publication['published'], ENT_QUOTES, 'UTF-8'); ?></td>
              <td><?php echo (int)$publication['product_count']; ?></td>
              <td><a class="btn btn-primary btn-sm" href="<?php echo $publication['download']; ?>" rel="nofollow"><i class="fa fa-download"></i> <?php echo $button_download; ?></a><br><small><?php echo htmlspecialchars($publication['filename'], ENT_QUOTES, 'UTF-8'); ?></small></td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
      <?php } else { ?>
      <p><?php echo $text_empty; ?></p>
      <?php } ?>
      <?php echo $content_bottom; ?>
    </div>
    <?php echo $column_right; ?>
  </div>
</div>
<?php echo $footer; ?>
