<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <form action="<?php echo $sync_action; ?>" method="post" style="display:inline-block">
          <button type="submit" data-toggle="tooltip" title="<?php echo $button_sync; ?>" class="btn btn-info"><i class="fa fa-refresh"></i> <?php echo $button_sync; ?></button>
        </form>
        <form action="<?php echo $publish_action; ?>" method="post" style="display:inline-block">
          <button type="submit" data-toggle="tooltip" title="<?php echo $button_publish; ?>" class="btn btn-success"><i class="fa fa-file-text-o"></i> <?php echo $button_publish; ?></button>
        </form>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
      </div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>

  <div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo htmlspecialchars($error_warning, ENT_QUOTES, 'UTF-8'); ?><button type="button" class="close" data-dismiss="alert">&times;</button></div>
    <?php } ?>
    <?php if ($success) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo htmlspecialchars($success, ENT_QUOTES, 'UTF-8'); ?><button type="button" class="close" data-dismiss="alert">&times;</button></div>
    <?php } ?>
    <?php if ($publication_warning) { ?>
    <div class="alert alert-warning"><i class="fa fa-clock-o"></i> <?php echo htmlspecialchars($publication_warning, ENT_QUOTES, 'UTF-8'); ?></div>
    <?php } ?>

    <div class="alert alert-info">
      <i class="fa fa-info-circle"></i> <?php echo $text_reference_rule; ?>
      <span class="pull-right"><strong><?php echo sprintf($text_missing_count, (int)$missing_count); ?></strong></span>
    </div>

    <div class="panel panel-default">
      <div class="panel-heading"><h3 class="panel-title"><i class="fa fa-cog"></i> <?php echo $text_settings; ?></h3></div>
      <div class="panel-body">
        <form action="<?php echo $settings_action; ?>" method="post" class="form-horizontal">
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-default-unit"><span data-toggle="tooltip" title="<?php echo htmlspecialchars($help_default_unit, ENT_QUOTES, 'UTF-8'); ?>"><?php echo $entry_default_unit; ?></span></label>
            <div class="col-sm-3"><input type="text" name="default_unit" value="<?php echo htmlspecialchars($default_unit, ENT_QUOTES, 'UTF-8'); ?>" id="input-default-unit" maxlength="16" class="form-control" /></div>
            <div class="col-sm-2"><button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> <?php echo $button_settings; ?></button></div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-cron-url"><?php echo $entry_cron_url; ?></label>
            <div class="col-sm-10"><input type="text" readonly="readonly" value="<?php echo htmlspecialchars(html_entity_decode($cron_url, ENT_QUOTES, 'UTF-8'), ENT_QUOTES, 'UTF-8'); ?>" id="input-cron-url" class="form-control" /><p class="help-block"><?php echo $text_cron_help; ?></p></div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-cron-key"><?php echo $entry_cron_key; ?></label>
            <div class="col-sm-10"><input type="text" readonly="readonly" value="<?php echo htmlspecialchars($cron_key, ENT_QUOTES, 'UTF-8'); ?>" id="input-cron-key" class="form-control" /></div>
          </div>
        </form>
      </div>
    </div>

    <div class="panel panel-default">
      <div class="panel-heading"><h3 class="panel-title"><i class="fa fa-filter"></i> <?php echo $text_filter; ?></h3></div>
      <div class="panel-body">
        <form action="<?php echo $filter_action; ?>" method="get" class="form-horizontal">
          <input type="hidden" name="route" value="extension/module/anchor_price" />
          <input type="hidden" name="token" value="<?php echo $token; ?>" />
          <div class="row">
            <div class="col-sm-3"><div class="form-group"><label class="control-label" for="filter-name"><?php echo $entry_filter_name; ?></label><input type="text" name="filter_name" value="<?php echo htmlspecialchars($filter_name, ENT_QUOTES, 'UTF-8'); ?>" id="filter-name" class="form-control" /></div></div>
            <div class="col-sm-2"><div class="form-group"><label class="control-label" for="filter-model"><?php echo $entry_filter_model; ?></label><input type="text" name="filter_model" value="<?php echo htmlspecialchars($filter_model, ENT_QUOTES, 'UTF-8'); ?>" id="filter-model" class="form-control" /></div></div>
            <div class="col-sm-2"><div class="form-group"><label class="control-label" for="filter-status"><?php echo $entry_filter_status; ?></label><select name="filter_status" id="filter-status" class="form-control"><option value=""><?php echo $text_all_statuses; ?></option><?php foreach ($statuses as $status_code => $status_name) { ?><option value="<?php echo $status_code; ?>"<?php echo $filter_status === $status_code ? ' selected="selected"' : ''; ?>><?php echo $status_name; ?></option><?php } ?></select></div></div>
            <div class="col-sm-2"><div class="form-group"><label class="control-label" for="filter-date-from"><?php echo $entry_date_from; ?></label><input type="date" name="filter_date_from" value="<?php echo htmlspecialchars($filter_date_from, ENT_QUOTES, 'UTF-8'); ?>" id="filter-date-from" class="form-control" /></div></div>
            <div class="col-sm-2"><div class="form-group"><label class="control-label" for="filter-date-to"><?php echo $entry_date_to; ?></label><input type="date" name="filter_date_to" value="<?php echo htmlspecialchars($filter_date_to, ENT_QUOTES, 'UTF-8'); ?>" id="filter-date-to" class="form-control" /></div></div>
            <div class="col-sm-1"><div class="form-group"><label class="control-label">&nbsp;</label><button type="submit" class="btn btn-primary btn-block"><i class="fa fa-search"></i></button></div></div>
          </div>
        </form>
      </div>
    </div>

    <div class="panel panel-default">
      <div class="panel-heading"><h3 class="panel-title"><i class="fa fa-anchor"></i> <?php echo $text_list; ?></h3></div>
      <div class="panel-body">
        <div class="table-responsive">
          <table class="table table-bordered table-hover">
            <thead><tr>
              <td><a href="<?php echo $sort_product_name; ?>"><?php echo $column_product; ?></a></td>
              <td><a href="<?php echo $sort_model; ?>"><?php echo $column_model; ?></a></td>
              <td class="text-right"><a href="<?php echo $sort_price; ?>"><?php echo $column_net_price; ?></a></td>
              <td class="text-right"><a href="<?php echo $sort_gross_price; ?>"><?php echo $column_gross_price; ?></a></td>
              <td><a href="<?php echo $sort_reference_date; ?>"><?php echo $column_reference_date; ?></a></td>
              <td><a href="<?php echo $sort_verification_status; ?>"><?php echo $column_status; ?></a></td>
              <td class="text-right"><?php echo $column_action; ?></td>
            </tr></thead>
            <tbody>
              <?php if ($anchors) { foreach ($anchors as $anchor) { ?>
              <tr>
                <td><?php echo htmlspecialchars($anchor['product_name'], ENT_QUOTES, 'UTF-8'); ?><br /><small class="text-muted">ID <?php echo $anchor['product_id']; ?><?php echo $anchor['manufacturer'] ? ' · ' . htmlspecialchars($anchor['manufacturer'], ENT_QUOTES, 'UTF-8') : ''; ?></small></td>
                <td><?php echo htmlspecialchars($anchor['model'], ENT_QUOTES, 'UTF-8'); ?><?php echo $anchor['sku'] ? '<br /><small class="text-muted">' . htmlspecialchars($anchor['sku'], ENT_QUOTES, 'UTF-8') . '</small>' : ''; ?></td>
                <td class="text-right"><?php echo $anchor['price']; ?> <?php echo $anchor['currency_code']; ?></td>
                <td class="text-right"><strong><?php echo $anchor['gross_price']; ?> <?php echo $anchor['currency_code']; ?></strong></td>
                <td><?php echo $anchor['reference_date']; ?></td>
                <td><span class="label <?php echo $anchor['verification_status'] === 'confirmed' ? 'label-success' : ($anchor['verification_status'] === 'pending' ? 'label-warning' : 'label-default'); ?>"><?php echo $anchor['status_text']; ?></span></td>
                <td class="text-right"><a href="<?php echo $anchor['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a></td>
              </tr>
              <?php } } else { ?>
              <tr><td class="text-center" colspan="7"><?php echo $text_no_results; ?></td></tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
        <div class="row"><div class="col-sm-6 text-left"><?php echo $pagination; ?></div><div class="col-sm-6 text-right"><?php echo $results; ?></div></div>
      </div>
    </div>

    <div class="panel panel-default">
      <div class="panel-heading"><h3 class="panel-title"><i class="fa fa-archive"></i> <?php echo $text_publications; ?></h3></div>
      <div class="panel-body"><div class="table-responsive"><table class="table table-bordered table-hover">
        <thead><tr><td><?php echo $column_location; ?></td><td><?php echo $column_sequence; ?></td><td><?php echo $column_filename; ?></td><td><?php echo $column_status; ?></td><td class="text-right"><?php echo $column_products; ?></td><td><?php echo $column_published; ?></td><td class="text-right"><?php echo $column_action; ?></td></tr></thead>
        <tbody><?php if ($publications) { foreach ($publications as $publication) { ?><tr>
          <td><strong><?php echo htmlspecialchars($publication['location_code'], ENT_QUOTES, 'UTF-8'); ?></strong></td><td><?php echo $publication['sequence_no']; ?></td><td><?php echo htmlspecialchars($publication['filename'], ENT_QUOTES, 'UTF-8'); ?></td><td><?php echo htmlspecialchars($publication['status'], ENT_QUOTES, 'UTF-8'); ?></td><td class="text-right"><?php echo $publication['product_count']; ?></td><td><?php echo $publication['published_at']; ?></td><td class="text-right"><?php if ($publication['download']) { ?><a href="<?php echo $publication['download']; ?>" title="<?php echo $button_download; ?>" class="btn btn-default"><i class="fa fa-download"></i></a><?php } ?></td>
        </tr><?php } } else { ?><tr><td colspan="7" class="text-center"><?php echo $text_no_results; ?></td></tr><?php } ?></tbody>
      </table></div></div>
    </div>
  </div>
</div>
<?php echo $footer; ?>
