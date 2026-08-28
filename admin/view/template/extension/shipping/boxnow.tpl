<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-boxnow" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-boxnow" class="form-horizontal">
      <div class="panel panel-default">
        <div class="panel-heading"><h3 class="panel-title"><i class="fa fa-plug"></i> <?php echo $text_api; ?></h3></div>
        <div class="panel-body">
          <?php
          $api_fields = array(
            array('api-url', 'boxnow_api_url', $entry_api_url, $boxnow_api_url, $help_api_url, 'text', $error_api_url),
            array('widget-url', 'boxnow_widget_url', $entry_widget_url, $boxnow_widget_url, $help_widget_url, 'text', $error_widget_url),
            array('tracking-url', 'boxnow_tracking_url', $entry_tracking_url, $boxnow_tracking_url, $help_tracking_url, 'text', $error_tracking_url),
            array('partner-id', 'boxnow_partner_id', $entry_partner_id, $boxnow_partner_id, $help_partner_id, 'text', $error_partner_id),
            array('widget-partner-id', 'boxnow_widget_partner_id', $entry_widget_partner_id, $boxnow_widget_partner_id, $help_widget_partner_id, 'text', $error_widget_partner_id),
            array('origin-location-id', 'boxnow_origin_location_id', $entry_origin_location_id, $boxnow_origin_location_id, $help_origin_location_id, 'text', $error_origin_location_id),
            array('client-id', 'boxnow_client_id', $entry_client_id, $boxnow_client_id, '', 'text', $error_client_id),
            array('client-secret', 'boxnow_client_secret', $entry_client_secret, $boxnow_client_secret, $client_secret_saved ? $text_secret_saved : '', 'password', $error_client_secret)
          );
          foreach ($api_fields as $field) { ?>
          <div class="form-group<?php echo $field[6] ? ' has-error' : ''; ?>">
            <label class="col-sm-3 control-label" for="input-<?php echo $field[0]; ?>"><?php echo $field[2]; ?></label>
            <div class="col-sm-9">
              <input type="<?php echo $field[5]; ?>" name="<?php echo $field[1]; ?>" value="<?php echo htmlspecialchars($field[3], ENT_QUOTES, 'UTF-8'); ?>" id="input-<?php echo $field[0]; ?>" class="form-control" autocomplete="<?php echo $field[5] == 'password' ? 'new-password' : 'off'; ?>" />
              <?php if ($field[4]) { ?><p class="help-block"><?php echo $field[4]; ?></p><?php } ?>
              <?php if ($field[6]) { ?><div class="text-danger"><?php echo $field[6]; ?></div><?php } ?>
            </div>
          </div>
          <?php } ?>
        </div>
      </div>

      <div class="panel panel-default">
        <div class="panel-heading"><h3 class="panel-title"><i class="fa fa-truck"></i> <?php echo $text_sender; ?></h3></div>
        <div class="panel-body">
          <?php
          $sender_fields = array(
            array('origin-name', 'boxnow_origin_name', $entry_origin_name, $boxnow_origin_name, '', $error_origin_name),
            array('origin-email', 'boxnow_origin_email', $entry_origin_email, $boxnow_origin_email, '', $error_origin_email),
            array('origin-phone', 'boxnow_origin_phone', $entry_origin_phone, $boxnow_origin_phone, '', $error_origin_phone),
            array('order-prefix', 'boxnow_order_prefix', $entry_order_prefix, $boxnow_order_prefix, $help_order_prefix, $error_order_prefix),
            array('compartment-size', 'boxnow_compartment_size', $entry_compartment_size, $boxnow_compartment_size, $help_compartment_size, $error_compartment_size)
          );
          foreach ($sender_fields as $field) { ?>
          <div class="form-group<?php echo $field[5] ? ' has-error' : ''; ?>">
            <label class="col-sm-3 control-label" for="input-<?php echo $field[0]; ?>"><?php echo $field[2]; ?></label>
            <div class="col-sm-9">
              <input type="text" name="<?php echo $field[1]; ?>" value="<?php echo htmlspecialchars($field[3], ENT_QUOTES, 'UTF-8'); ?>" id="input-<?php echo $field[0]; ?>" class="form-control" />
              <?php if ($field[4]) { ?><p class="help-block"><?php echo $field[4]; ?></p><?php } ?>
              <?php if ($field[5]) { ?><div class="text-danger"><?php echo $field[5]; ?></div><?php } ?>
            </div>
          </div>
          <?php } ?>
          <div class="form-group">
            <label class="col-sm-3 control-label" for="input-allow-return"><?php echo $entry_allow_return; ?></label>
            <div class="col-sm-9">
              <select name="boxnow_allow_return" id="input-allow-return" class="form-control">
                <option value="1"<?php echo $boxnow_allow_return ? ' selected="selected"' : ''; ?>><?php echo $text_yes; ?></option>
                <option value="0"<?php echo !$boxnow_allow_return ? ' selected="selected"' : ''; ?>><?php echo $text_no; ?></option>
              </select>
            </div>
          </div>
        </div>
      </div>

      <div class="panel panel-default">
        <div class="panel-heading"><h3 class="panel-title"><i class="fa fa-money"></i> <?php echo $text_pricing; ?></h3></div>
        <div class="panel-body">
          <div class="form-group<?php echo $error_cost ? ' has-error' : ''; ?>">
            <label class="col-sm-3 control-label" for="input-cost"><?php echo $entry_cost; ?></label>
            <div class="col-sm-9">
              <input type="text" name="boxnow_cost" value="<?php echo htmlspecialchars($boxnow_cost, ENT_QUOTES, 'UTF-8'); ?>" id="input-cost" class="form-control" />
              <?php if ($error_cost) { ?><div class="text-danger"><?php echo $error_cost; ?></div><?php } ?>
            </div>
          </div>
          <div class="form-group<?php echo $error_free_total ? ' has-error' : ''; ?>">
            <label class="col-sm-3 control-label" for="input-free-total"><?php echo $entry_free_total; ?></label>
            <div class="col-sm-9">
              <input type="text" name="boxnow_free_total" value="<?php echo htmlspecialchars($boxnow_free_total, ENT_QUOTES, 'UTF-8'); ?>" id="input-free-total" class="form-control" />
              <p class="help-block"><?php echo $help_free_total; ?></p>
              <?php if ($error_free_total) { ?><div class="text-danger"><?php echo $error_free_total; ?></div><?php } ?>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-3 control-label" for="input-tax-class"><?php echo $entry_tax_class; ?></label>
            <div class="col-sm-9">
              <select name="boxnow_tax_class_id" id="input-tax-class" class="form-control">
                <option value="0"><?php echo $text_none; ?></option>
                <?php foreach ($tax_classes as $tax_class) { ?>
                <option value="<?php echo $tax_class['tax_class_id']; ?>"<?php echo $tax_class['tax_class_id'] == $boxnow_tax_class_id ? ' selected="selected"' : ''; ?>><?php echo $tax_class['title']; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-3 control-label" for="input-geo-zone"><?php echo $entry_geo_zone; ?></label>
            <div class="col-sm-9">
              <select name="boxnow_geo_zone_id" id="input-geo-zone" class="form-control">
                <option value="0"><?php echo $text_all_zones; ?></option>
                <?php foreach ($geo_zones as $geo_zone) { ?>
                <option value="<?php echo $geo_zone['geo_zone_id']; ?>"<?php echo $geo_zone['geo_zone_id'] == $boxnow_geo_zone_id ? ' selected="selected"' : ''; ?>><?php echo $geo_zone['name']; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-3 control-label" for="input-status"><?php echo $entry_status; ?></label>
            <div class="col-sm-9">
              <select name="boxnow_status" id="input-status" class="form-control">
                <option value="1"<?php echo $boxnow_status ? ' selected="selected"' : ''; ?>><?php echo $text_enabled; ?></option>
                <option value="0"<?php echo !$boxnow_status ? ' selected="selected"' : ''; ?>><?php echo $text_disabled; ?></option>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-3 control-label" for="input-sort-order"><?php echo $entry_sort_order; ?></label>
            <div class="col-sm-9"><input type="text" name="boxnow_sort_order" value="<?php echo (int)$boxnow_sort_order; ?>" id="input-sort-order" class="form-control" /></div>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>
<?php echo $footer; ?>
