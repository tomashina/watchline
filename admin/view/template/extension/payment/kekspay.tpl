<?php echo $header; ?><?php echo $column_left; ?>
<div id="content" xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <button type="submit" form="form-payment" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
                      <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
            </div>
            <h1><i class="fa fa-credit-card text-success"></i> <?php echo $heading_title; ?></h1>
            <ul class="breadcrumb">
                 <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
                <?php } ?>
            </ul>
        </div>
    </div>
    <div class="container-fluid">
        <div class="panel-body">
            <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-payment" class="form-horizontal">
                <div class="form-group required">
                    <label class="col-sm-2 control-label" for="input-cid"><span data-toggle="tooltip" title="<?php echo $help_cid; ?>"><?php echo $entry_cid; ?></span></label>
                    <div class="col-sm-10">
                        <input type="text" name="kekspay_cid" value="<?php echo $kekspay_cid; ?>" placeholder="<?php echo $entry_cid; ?>" id="input-cid" class="form-control" />
                      <?php if ($error_cid) { ?>
                            <span class="error text-danger"><?php echo $error_cid; ?></span>
                              <?php } ?>
                    </div>
                </div>
                <div class="form-group required">
                    <label class="col-sm-2 control-label" for="input-tid"><span data-toggle="tooltip" title="<?php echo $help_tid; ?>"><?php echo $entry_tid; ?></span></label>
                    <div class="col-sm-10">
                        <input type="text" name="kekspay_tid" value="<?php echo $kekspay_tid; ?>" placeholder="<?php echo $entry_tid; ?>" id="input-tid" class="form-control" />
                         <?php if ($error_tid) { ?>
                            <span class="error text-danger"><?php echo $error_tid; ?></span>
                        <?php } ?>
                    </div>
                </div>
                <div class="form-group required">
                    <label class="col-sm-2 control-label" for="input-token"><span data-toggle="tooltip" title="<?php echo $help_token; ?>"><?php echo $entry_token; ?></span></label>
                    <div class="col-sm-10">
                        <input type="text" name="kekspay_token" value="<?php echo $kekspay_token; ?>" placeholder="<?php echo $entry_token; ?>" id="input-token" class="form-control" />
                       <?php if ($error_token) { ?>
                            <span class="error text-danger"><?php echo $error_token; ?></span>
                          <?php } ?>
                    </div>
                </div>
                <div class="form-group required">
                    <label class="col-sm-2 control-label" for="input-password"><span data-toggle="tooltip" title="<?php echo $help_password; ?>"><?php echo $entry_password; ?></span></label>
                    <div class="col-sm-10">
                        <input type="text" name="kekspay_password" value="<?php echo $kekspay_password; ?>" placeholder="<?php echo $entry_password; ?>" id="input-password" class="form-control" />
                       <?php if ($error_passwor) { ?>
                            <span class="error text-danger"> <?php echo $error_password; ?></span>
                          <?php } ?>
                    </div>
                </div>

          

                <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-shop-title"><?php echo $entry_shop_title; ?></label>
                    <div class="col-sm-10">
                        <input type="text" name="kekspay_shop_title" value="<?php echo $kekspay_shop_title; ?>" id="input-shop-title" class="form-control" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-callback"><span data-toggle="tooltip" title="<?php echo $help_entry_callback; ?>"><?php echo $entry_callback; ?></span></label>
                    <div class="col-sm-10">
                        <input type="text" name="callback" value="<?php echo $callback; ?>" id="input-callback" class="form-control" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-test"><?php echo $entry_test; ?></label>
                    <div class="col-sm-10">
                        <select name="kekspay_test" id="input-test" class="form-control">
                            <?php if ($kekspay_test == '0') { ?>
                                <option value="0" selected="selected"><?php echo $text_off; ?></option>
                               <?php } else { ?>
                                <option value="0"><?php echo $text_off; ?></option>
                                <?php } ?>
                           <?php if ($kekspay_test == '100') { ?>
                                <option value="100" selected="selected"><?php echo $text_successful; ?></option>
                              <?php } else { ?>
                                <option value="100"><?php echo $text_successful; ?></option>
                                  <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-total"><span data-toggle="tooltip" title="<?php echo $help_entry_total; ?>"><?php echo $entry_total; ?></span></label>
                    <div class="col-sm-10">
                        <input type="text" name="kekspay_total" value="<?php echo $kekspay_total; ?>" id="input-total" class="form-control" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-order-status"><?php echo $entry_order_status; ?></label>
                    <div class="col-sm-10">
                        <select name="kekspay_order_status_id" id="input-order-status" class="form-control">
                          <?php foreach ($order_statuses as $order_status) { ?>
                                <?php if ($order_status['order_status_id'] == $kekspay_order_status_id) { ?>
                                <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                                <?php } else { ?>
                                <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                                <?php } ?>
                        <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-geo-zone"><?php echo $entry_geo_zone; ?></label>
                    <div class="col-sm-10">
                        <select name="kekspay_geo_zone_id" id="input-geo-zone" class="form-control">
                            <option value="0"><?php echo $text_all_zones; ?></option>
                           <?php foreach ($geo_zones as $geo_zone) { ?>
                                <?php if ($geo_zone['geo_zone_id'] == $kekspay_geo_zone_id) { ?>
                                <option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
                                <?php } else { ?>
                                <option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
                                <?php } ?>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
                    <div class="col-sm-10">
                        <select name="kekspay_status" id="input-status" class="form-control">
                                <?php if ($kekspay_status) { ?>
                                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                <option value="0"><?php echo $text_disabled; ?></option>
                            <?php } else { ?>
                                <option value="1"><?php echo $text_enabled; ?></option>
                                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                           <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-sort"><?php echo $entry_sort_order; ?></label>
                    <div class="col-sm-10">
                        <input type="text" name="kekspay_sort_order" value="<?php echo $kekspay_sort_order; ?>" id="input-sort" class="form-control" />
                    </div>
                </div>
            </form>
        </div>

    </div>
</div>
<?php echo $footer; ?>