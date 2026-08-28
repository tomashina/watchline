<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-fbecommevnt" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <button type="submit" form="form-fbecommevnt" data-toggle="tooltip" onclick="$('#svsty').val(1);" title="Save & Stay" class="btn btn-primary">Save & Stay</button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a> <?php echo $text_extension_doc; ?> </div>
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
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-fbecommevnt" class="form-horizontal">
          <input type="hidden"  name="svsty" id="svsty" />
          <ul class="nav nav-tabs">
            <li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
            <li><a href="#tab-language" data-toggle="tab"><?php echo $tab_language;?></a></li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane active" id="tab-general">
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
                <div class="col-sm-10">
                  <select name="fbecommevnt_status" id="input-mainstatus" class="form-control">
                    <?php if ($fbecommevnt_status) { ?>
                    <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                    <option value="0"><?php echo $text_disabled; ?></option>
                    <?php } else { ?>
                    <option value="1"><?php echo $text_enabled; ?></option>
                    <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <ul class="nav nav-tabs" id="storetab">
                <?php foreach ($stores as $store) { ?>
                <li><a href="#tab-<?php echo $store['store_id'];?>" data-toggle="tab"><?php echo $store['name']; ?></a></li>
                <?php } ?>
              </ul>
              <div class="tab-content">
                <?php foreach ($stores as $store) { ?>
                <div class="tab-pane" id="tab-<?php echo $store['store_id'];?>">
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-status"><?php echo $store['name']; ?> <?php echo $tab_store;?> <?php echo $entry_status; ?></label>
                    <div class="col-sm-10">
                      <input type="radio" value="1" name="fbecommevnt_sts<?php echo $store['store_id'];?>" <?php if ($fbecommevnt_sts[$store['store_id']] == 1) { ?>checked<?php } ?>>
                      <?php echo $text_enabled; ?>&nbsp;&nbsp;
                      <input type="radio" value="0" name="fbecommevnt_sts<?php echo $store['store_id'];?>" <?php if ($fbecommevnt_sts[$store['store_id']] == 0) { ?>checked<?php } ?>>
                      <?php echo $text_disabled; ?> </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-fb_pixel_id"><?php echo $entry_fb_pixel_id; ?></label>
                    <div class="col-sm-10">
                      <input type="text" name="fbecommevnt_fb_pixel_id<?php echo $store['store_id'];?>" id="input-fb_pixel_id" value="<?php echo $fbecommevnt_fb_pixel_id[$store['store_id']]; ?>" class="form-control" />
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-fb_product_catalog_id"><?php echo $entry_fb_product_catalog_id; ?></label>
                    <div class="col-sm-10">
                      <input type="text" name="fbecommevnt_fb_product_catalog_id<?php echo $store['store_id'];?>" id="input-fb_product_catalog_id" value="<?php echo $fbecommevnt_fb_product_catalog_id[$store['store_id']]; ?>" class="form-control" />
                    </div>
                  </div>
                </div>
                <?php } ?>
              </div>
            </div>
            <div class="tab-pane" id="tab-language">
              <ul class="nav nav-tabs" id="language">
                <?php foreach ($languages as $language) { ?>
                <li><a href="#language<?php echo $language['language_id']; ?>" data-toggle="tab"><img src="<?php echo $language['imgsrc'];?>" /> <?php echo $language['name']; ?></a></li>
                <?php } ?>
              </ul>
              <div class="tab-content">
                <?php foreach ($languages as $language) { ?>
                <div class="tab-pane" id="language<?php echo $language['language_id']; ?>">
                  <?php foreach($lang as $lng) { ?>
                  <div class="form-group">
                    <label class="col-sm-2 control-label"><?php echo $entrylang[$lng]; ?></label>
                    <div class="col-sm-10">
                      <input type="text" name="fbecommevnt_lang[<?php echo $lng; ?>][<?php echo $language['language_id']; ?>]" value="<?php echo $fbecommevnt_lang[$lng][$language['language_id']]; ?>" placeholder="<?php echo $entrylang[$lng]; ?>" class="form-control" />
                    </div>
                  </div>
                  <?php } ?>
                </div>
                <?php } ?>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
$('#storetab a:first').tab('show');
$('#language a:first').tab('show');
//--></script> 
<?php echo $footer; ?>