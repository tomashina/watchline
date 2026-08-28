<?php echo $header; ?>
<!--
//==============================================
// XML Sitemap OC 2.3.x_v2.x
// Author 	: OpenCartBoost
// Email 	: support@opencartboost.com
// Website 	: http://www.opencartboost.com
//==============================================
-->
<?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-feed" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-success"><i class="fa fa-save"></i></button>
        <a onclick="$('#form-feed').attr('action', '<?php echo $continue; ?>');$('#form-feed').submit();" data-toggle="tooltip" title="<?php echo $button_save_continue; ?>" class="btn btn-primary"><i class="fa fa-check"></i></a>
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
    <div id="message"></div>
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger alert-dismissible"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <?php if ($success) { ?>
    <div class="alert alert-success alert-dismissible"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-feed" class="form-horizontal">
          <ul class="nav nav-tabs">
            <li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
            <li><a href="#tab-custom-link" data-toggle="tab"><?php echo $tab_custom_link; ?></a></li>
            <li><a href="#tab-setting" data-toggle="tab"><?php echo $tab_setting; ?></a></li>
            <li><a href="#tab-raw-file" data-toggle="tab"><?php echo $tab_raw_file; ?></a></li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane active" id="tab-general">
              <div class="form-group">
	            <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
	            <div class="col-sm-4">
				  <div class="btn-group btn-toggle" data-toggle="buttons">
	                <?php if ($boost_sitemap_status) { ?>
	                  <label class="btn btn-success btn-md active"><input type="radio" name="boost_sitemap_status" value="1" checked="checked"><?php echo $text_enabled; ?></label>
					  <label class="btn btn-default btn-md"><input type="radio" name="boost_sitemap_status"  value="0"><?php echo $text_disabled; ?></label>
			        <?php } else { ?>
				      <label class="btn btn-default btn-md"><input type="radio" name="boost_sitemap_status" value="1"><?php echo $text_enabled; ?></label>
				      <label class="btn btn-success btn-md active"><input type="radio" name="boost_sitemap_status" value="0" checked="checked"><?php echo $text_disabled; ?></label>
				    <?php } ?>
	              </div>
				</div>
	          </div>
	          <div class="form-group">
	            <div class="col-sm-12">
	              <table class="table table-bordered">
	                <thead>
	                  <tr>
	                    <th><?php echo $column_store; ?></th>
	                    <th><?php echo $column_sitemap_index; ?></th>
	                    <th><?php echo $column_ping_to; ?></th>
	                  </tr>
	                </thead>
	                <tbody>
	                  <?php foreach ($data_feed as $feed) { ?>
	                  <tr>
	                    <td><?php echo $feed['store_name']; ?></td>
	                    <td><a target="_blank" href="<?php echo $feed['feed']; ?>" target="_blank"><?php echo $feed['feed']; ?></a></td>
	                    <td><a class="btn btn-danger" href="http://www.google.com/ping?sitemap=<?php echo $feed['feed']; ?>">Google</a> <a class="btn btn-info" href="http://www.bing.com/ping?sitemap=<?php echo $feed['feed']; ?>">Bing</a></td>
	                  </tr>
	                  <?php } ?>
	                </tbody>
	              </table>
	            </div>
	          </div>
            </div>
			
            <div class="tab-pane" id="tab-custom-link">
              <div class="row">
			    <div class="form-group col-sm-6">
			      <label class="col-sm-3 control-label"><?php echo $entry_url; ?></label>
	              <div class="col-sm-9">
	                <input type="text" class="form-control" name="custom_link_url" value="">
	              </div>
	            </div>
	            <div class="form-group col-sm-6">
	              <label class="col-sm-3 control-label"><?php echo $entry_frequency; ?></label>
	              <div class="col-sm-9">
	                <select name="custom_link_frequency" class="form-control">
	                  <option value="always"><?php echo $text_always; ?></option>
	                  <option value="hourly"><?php echo $text_hourly; ?></option>
	                  <option value="daily"><?php echo $text_daily; ?></option>
	                  <option value="weekly"><?php echo $text_weekly; ?></option>
	                  <option value="monthly"><?php echo $text_monthly; ?></option>
	                  <option value="yearly"><?php echo $text_yearly; ?></option>
	                  <option value="never"><?php echo $text_never; ?></option>
	                </select>
	              </div>
	            </div>
			  </div>
			  <div class="row">
			    <div class="form-group col-sm-6">
	              <label class="col-sm-3 control-label"><?php echo $entry_priority; ?></label>
	              <div class="col-sm-9">
	                <select name="custom_link_priority" class="form-control">
	                  <option value="0.1">0.1</option>
	                  <option value="0.2">0.2</option>
	                  <option value="0.3">0.3</option>
	                  <option value="0.4">0.4</option>
	                  <option value="0.5">0.5</option>
	                  <option value="0.6">0.6</option>
	                  <option value="0.7">0.7</option>
	                  <option value="0.8">0.8</option>
	                  <option value="0.9">0.9</option>
	                  <option value="1.0">1.0</option>
	                </select>
	              </div>
	            </div>
	            <div class="form-group col-sm-6">
	              <label class="col-sm-3 control-label"><?php echo $entry_store; ?></label>
	              <div class="col-sm-9">
	                <select name="custom_link_store_id" class="form-control">
	                  <?php foreach ($stores as $store) { ?>
	                    <option value="<?php echo $store['store_id']; ?>"><?php echo $store['name']; ?></option>
	                  <?php } ?>
	                </select>
	              </div>
	            </div>
			  </div>
			  <div class="row">
			    <div class="form-group col-sm-6">
	              <label class="col-sm-3 control-label"></label>
	              <div class="col-sm-9">
                    <button type="button" class="btn btn-success" onclick="addCustomLink(this, '<?php echo $custom_link; ?>');"><i class="fa fa-plus-circle"></i> <?php echo $button_add_link; ?></button>
                    <button type="button" class="btn btn-danger" onclick="confirm('<?php echo $text_confirm; ?>') ? deleteCustomLink(this, '<?php echo $delete_custom_link; ?>') : false;"><i class="fa fa-trash-o"></i> <?php echo $button_delete; ?></button>
                    <button type="button" class="btn btn-default" onclick="getCustomLinks();"><i class="fa fa-refresh"></i> <?php echo $button_refresh; ?></button>
                  </div>
	            </div>
			  </div>
			  <table class="table table-bordered">
	            <thead>
			    <tr>
			      <th><input type="checkbox" onclick="$('input[name*=\'custom_link_ids\']').prop('checked', this.checked);" /></th>
			      <th><?php echo $column_store; ?></th>
			      <th><?php echo $column_link; ?></th>
			      <th><?php echo $column_frequency; ?></th>
			      <th><?php echo $column_priority; ?></th>
			    </tr>
			    </thead>
			    <tbody id="custom-link">
			    </tbody>
			  </table>
            </div>
            
			<div class="tab-pane" id="tab-setting">
               <div class="form-group">
                 <label class="col-sm-3 control-label"><span data-toggle="tooltip" title="<?php echo $help_item; ?>"><?php echo $entry_item; ?></span></label>
                 <div class="col-sm-9">
                   <?php foreach ($items  as $key => $value) { ?>
                     <div class="checkbox">
                       <label>
                         <?php if (in_array($key, $boost_sitemap_item)) { ?>
					       <input type="checkbox" name="boost_sitemap_item[]" value="<?php echo $key; ?>" checked="checked" /> <?php echo $value; ?>
						 <?php } else { ?>
                           <input type="checkbox" name="boost_sitemap_item[]" value="<?php echo $key; ?>" /> <?php echo $value; ?>
                         <?php } ?>
                       </label>
                     </div>
                   <?php } ?>
                 </div>
               </div>
			   <div class="form-group">
	             <label class="col-sm-3 control-label" for="input-data-feed"><?php echo $entry_item_limit; ?></label>
	             <div class="col-sm-3">
	               <input type="text" class="form-control" name="boost_sitemap_item_limit" value="<?php echo $boost_sitemap_item_limit; ?>">
	             </div>
	           </div>
	           <div class="form-group">
                 <label class="col-sm-3 control-label"></label>
                 <div class="col-sm-9">
                   <button type="button" class="btn btn-success" onclick="confirm('<?php echo $text_overwrite; ?>') ? generateFiles(this, '<?php echo $generate; ?>') : false;"><i class="fa fa-sitemap"></i> <?php echo $button_generate_file; ?></button>
                 </div>
               </div>
            </div>
            <div class="tab-pane" id="tab-raw-file">
              <div style="margin-bottom:20px">
			    <button type="button" class="btn btn-danger" onclick="confirm('<?php echo $text_confirm; ?>') ? deleteFiles(this, '<?php echo $delete; ?>') : false;"><i class="fa fa-trash-o"></i> <?php echo $button_delete; ?></button>
			  </div>
			  <table class="table table-bordered">
			    <tr>
			      <th><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></th>
			      <th><?php echo $column_file_path; ?></th>
			      <th><?php echo $column_file_size; ?></th>
			      <th><?php echo $column_file_created; ?></th>
			    </tr>
			    <?php if ($xml_files) { ?>
			      <?php foreach ($xml_files as $file) { ?>
			        <tr>
			          <td><input type="checkbox" name="selected[]" value="<?php echo $file['path']; ?>" /></td>
			          <td><a href="<?php echo $file['url']; ?>" target="_blank"><?php echo $file['path']; ?></a></td>
			          <td><?php echo $file['size']; ?></td>
			          <td><?php echo $file['datetime']; ?></td>
			        </tr>
			      <?php } ?>
			    <?php } else { ?>
			      <tr>
			        <td colspan="4" class="text-center"><?php echo $text_empty; ?></td>
			      </tr>
			    <?php } ?>
			  </table>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
	$('.btn-toggle').click(function() {
		$(this).find('.btn').toggleClass('active');
		if ($(this).find('.btn-primary').size()>0) {
			$(this).find('.btn').toggleClass('btn-primary');
		}
		if ($(this).find('.btn-danger').size()>0) {
			$(this).find('.btn').toggleClass('btn-danger');
		}
		if ($(this).find('.btn-success').size()>0) {
			$(this).find('.btn').toggleClass('btn-success');
		}
		if ($(this).find('.btn-info').size()>0) {
			$(this).find('.btn').toggleClass('btn-info');
		}
		$(this).find('.btn').toggleClass('btn-default');
	});
</script>
<script>
	$(document).ready(function() {
		getCustomLinks();
	});
	
	function deleteFiles(elem, action) {
		var $btn = $(elem);
		$.ajax({
			url: action,
			data: $('#form-feed').serialize(),
			dataType: 'json',
			type: 'post',
			beforeSend: function() {
				$btn.button('loading');
			},
			complete: function() {
				$btn.button('reset');
			},
			success: function() {
				location.reload(true);
			}
		});
	}
	
	function generateFiles(elem, action) {
		var $btn = $(elem);
		$.ajax({
			url: action,
			data: $('#form-feed').serialize(),
			dataType: 'json',
			type: 'post',
			beforeSend: function() {
				$btn.button('loading');
			},
			complete: function() {
				$btn.button('reset');
			},
			success: function() {
				location.reload(true);
			}
		});
	}
	
	function getCustomLinks() {
		$.ajax({
			url: "<?php echo $custom_link; ?>",
			dataType: 'html',
			success: function(html) {
				$('#custom-link').html(html);
			}
		});
	}
	
	function addCustomLink(elem, action) {
		var $btn = $(elem);
		$.ajax({
			url: action,
			data: 'custom_link_url=' + $('input[name=\'custom_link_url\']').val() + '&custom_link_frequency=' + $('select[name=\'custom_link_frequency\']').val() + '&custom_link_priority=' + $('select[name=\'custom_link_priority\']').val() + '&custom_link_store_id=' + $('select[name=\'custom_link_store_id\']').val(),
			dataType: 'json',
			type: 'post',
			beforeSend: function() {
				$btn.button('loading');
			},
			complete: function() {
				$btn.button('reset');
			},
			success: function() {
				location.reload(true);
			}
		});
		
		$('input[name=\'custom_link_url\']').val('');
		getCustomLinks();
	}
	
	function deleteCustomLink(elem, action) {
		var $btn = $(elem);
		$.ajax({
			url: action,
			data: $('#form-feed').serialize(),
			dataType: 'json',
			type: 'post',
			beforeSend: function() {
				$btn.button('loading');
			},
			complete: function() {
				$btn.button('reset');
			},
			success: function() {
				getCustomLinks();
			}
		});
	}
</script>
<?php echo $footer; ?>