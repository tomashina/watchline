<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
 <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
		<a class="btn btn-primary" data-toggle="tooltip" href="<?php echo $email_template; ?>"><i class="fa fa-television fw"></i> Email Template</a>
		<button onclick="$('.stay').val(1);" type="submit" form="form-recovert-cart" data-toggle="tooltip" title="<?php echo $button_save; ?> & stay" class="btn btn-success"><i class="fa fa-save"></i> <?php echo $button_save; ?> & stay </button>
        <button type="submit" form="form-recovert-cart" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i> <?php echo $button_save; ?></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
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
	<?php if ($success) { ?>
    <div class="alert alert-success"><i class="fa fa-exclamation-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
	<?php /*NEW*/ ?>
	<div class="successmessage"></div>
	<?php /*NEW*/ ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
		<div class="pull-right">
			<b>Stores : </b><select onchange="location = this.options[this.selectedIndex].value;" name="store_id">
			<option value="<?php echo $store_action.'&store_id=0'; ?>"><?php echo $text_default; ?></option>
			<?php foreach($stores as $store){ 
				if($store['store_id']==$store_id){
					 $select = 'selected=selected';
				}else{
					 $select = '';
				}
			?>
			<option <?php echo $select; ?> value="<?php echo $store_action .'&store_id='. $store['store_id']; ?>"><?php echo $store['name']; ?></option>
			<?php } ?>
			</select>
		</div>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-account" class="form-horizontal">
		<ul class="nav nav-tabs">
            <li class="active"><a href="#tab-control_panel" data-toggle="tab"> <i class="fa fa-cog" aria-hidden="true"></i> <?php echo $tab_control_panel; ?> </a></li>
			<li class="dropdown"><a href="#" data-toggle="dropdown"><i class="fa fa-cart-arrow-down" aria-hidden="true"></i> <?php echo $tab_abanoned_carts; ?> <span class="caret"></span></a>
				<ul class="dropdown-menu">
					<li><a href="#tab-abandoned_cart" data-toggle="tab">   <b><?php echo 'Un-Notify Cart Record'; ?></b></a></li>
					<li><a href="#tab-notified_abandoned_cart" data-toggle="tab"><b><?php echo 'Notified Cart Record'; ?></b></a></li>
				</ul>
			</li>
            <li><a href="#tab-abandoned_orders" data-toggle="tab"> <i class="fa fa-shopping-cart" aria-hidden="true"></i> <?php echo $tab_abandoned_orders; ?></a></li>
			<li class="dropdown"><a href="#" data-toggle="dropdown"><i class="fa fa-tags"></i> <?php echo $tab_coupon_history; ?> <span class="caret"></span></a>
				<ul class="dropdown-menu">
					<li><a href="#tab-unused-coupons" data-toggle="tab"><b><?php echo $tab_unused_coupons; ?></b></a></li>
					<li><a href="#tab-used-coupons" data-toggle="tab"><b><?php echo $tab_used_coupon; ?></b></a></li>
				</ul>
			</li>
			<li><a href="#tab-language" data-toggle="tab"><i class="fa fa-language"></i>  <?php echo $tab_languge; ?></a></li>
			<li><a href="#tab-cron" data-toggle="tab"> <i class="fa fa-envelope-o" aria-hidden="true"></i> <?php echo $tab_cron; ?></a></li>
			<li><a href="#tab-analytics" data-toggle="tab" aria-expanded="true"><i class="fa fa-line-chart"></i> Analytics</a></li>
			<li><a href="#tab-support" data-toggle="tab"><i class="fa fa-life-ring" aria-hidden="true"></i> <?php echo $tab_support; ?></a></li>
          </ul>
		  <div class="tab-content">
			<input type="hidden" name="stay" class="stay" value="0"/>
			<div class="tab-pane active" id="tab-control_panel">
			  <div class="form-group">
				<label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
				<div class="col-sm-5">
				  <select name="module_recover_carts_status" id="input-status" class="form-control">
					<?php if ($module_recover_carts_status) { ?>
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
				<label class="col-sm-2 control-label" for="input-protocol"><?php echo $entry_mail_protocol; ?></label>
				<div class="col-sm-5">
				  <select name="module_recover_carts_protocol" id="input-protocol" class="form-control">
					<?php if ($module_recover_carts_protocol=='default') { ?>
					<option value="default" selected="selected"><?php echo $text_default_mail; ?></option>
					<option value="sendgrid"><?php echo $text_sendgrid; ?></option>
					<?php } else { ?>
					<option value="default"><?php echo $text_default_mail; ?></option>
					<option value="sendgrid" selected="selected"><?php echo $text_sendgrid; ?></option>
					<?php } ?>
				  </select>
				</div>
			  </div>
			  <div class="sendgrid">
				  <div class="form-group required">
					<label class="col-sm-2 control-label" for="input-user"><?php echo $entry_username; ?></label>
					  <div class="col-sm-5">
						<input type="text" class="form-control" name="module_recover_carts_username" value="<?php echo $module_recover_carts_username; ?>"/> 
						<?php if($error_module_recover_carts_username){ ?>
							 <div class="text-danger"><?php echo $error_module_recover_carts_username; ?></div>
						<?php } ?>
					  </div>
					  <div class="col-sm-5">
						 <a  target="_blank" href="https://app.sendgrid.com/signup?id=71713987-9f01-4dea-b3d4-8d0bcd9d53ed">Get SendGrid's Username & Password</a>
					  </div>
				 </div>
				  <div class="form-group required">
					<label class="col-sm-2 control-label" for="input-password"><?php echo $entry_password; ?></label>
					<div class="col-sm-5">
						<input type="text" class="form-control" name="module_recover_carts_password" value="<?php echo $module_recover_carts_password; ?>"/>
						<?php if($error_module_recover_carts_password){ ?>
							 <div class="text-danger"><?php echo $error_module_recover_carts_password; ?></div>
						<?php } ?>
					 </div>
				  </div>
		     </div>
			 
			 <div class="form-group">
				<label class="col-sm-2 control-label" for="input-record"><span data-toggle="tooltip" title="" data-original-title="Add Primary Menu In admin">Add Menu</span></label>
				<div class="col-sm-5">
					<label class="radio-inline"><input name="module_recover_carts_menu" value="1" <?php if($module_recover_carts_menu){ ?> checked="checked" <?php } ?> type="radio"> <?php echo $text_yes; ?> </label>
					<label class="radio-inline"><input name="module_recover_carts_menu" value="0" <?php if(!$module_recover_carts_menu){ ?> checked="checked" <?php } ?> type="radio"> <?php echo $text_no; ?> </label>
				</div>
			 </div>
			 <div class="form-group">
				<label class="col-sm-2 control-label" for="input-record"><span data-toggle="tooltip" title="" data-original-title="Show total un notify abandoned carts in menu or tab">Count Carts</span></label>
				<div class="col-sm-5">
					<label class="radio-inline"><input name="module_recover_carts_count" value="1" <?php if($module_recover_carts_count){ ?> checked="checked" <?php } ?> type="radio"> <?php echo $text_yes; ?> </label>
					<label class="radio-inline"><input name="module_recover_carts_count" value="0" <?php if(!$module_recover_carts_count){ ?> checked="checked" <?php } ?> type="radio"> <?php echo $text_no; ?> </label>
				</div>
			 </div>
			 <?php /* NEW */ ?>
			 <div class="form-group">
				<label class="col-sm-2 control-label" for="input-showunknown"><span data-toggle="tooltip" title="" data-original-title="with this functionality, you can show Unknown user Record in abandoned Carts tab, which hasn't any Personal Information.">Unknown Record</span></label>
				<div class="col-sm-5">
					<label class="radio-inline"><input name="module_recover_carts_unknown_user" value="1" <?php if($module_recover_carts_unknown_user==1){ ?> checked="checked" <?php } ?> type="radio"> <?php echo $text_yes; ?> </label>
					<label class="radio-inline"><input name="module_recover_carts_unknown_user" value="2" <?php if($module_recover_carts_unknown_user==2){ ?> checked="checked" <?php } ?> type="radio"> <?php echo $text_no; ?> </label>
				</div>
			 </div>
			 <div class="form-group">
				<label class="col-sm-2 control-label" for="input-showunknown"><span data-toggle="tooltip" title="" data-original-title="with this functionality, you can Clean Unknown user Record from Database, which hasn't any Personal Information.">Clean Unknown Record</span></label>
				<div class="col-sm-5">
				  <a id="cleanrecord" class="btn btn-danger">Clean Unknown Record (<?php echo $totalunknownrecord; ?>)</a>
				</div>
			 </div>
			 <div class="form-group">
				<label class="col-sm-2 control-label" for="input-showunknown"><span data-toggle="tooltip" title="" data-original-title="with this functionality, you can clean expired coupons, which is generated by abandoned cart. <br/> Note: it's applies all used or unused coupons">Clean Expired Coupons</span></label>
				<div class="col-sm-5">
				  <a id="cleancoupons" class="btn btn-danger">Clean Expired Coupons (<?php echo $totalexpirecoupons; ?>)</a>
				</div>
			 </div>
			 <?php /* NEW */ ?>
		  </div>
		  <div class="tab-pane" id="tab-abandoned_cart">
			<div class="alert alert-info"><i class="fa fa-exclamation-circle"></i> Un-Notify Cart Record</div>
			 <div class="well well-sm">
			   <div class="row">
					 <div class="col-sm-4">
						<label class="control-label" for="input-form-date"><?php echo $entry_from_date; ?></label>
						<div class="input-group date">
							<input type="text" name="filter_from" value="" placeholder="<?php echo $entry_from_date; ?>" id="input-from" data-date-format="YYYY-MM-DD" class="form-control" />
							<span class="input-group-btn">
							  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
							</span>
						</div>
					 </div>
					 <div class="col-sm-4">
						<label class="control-label" for="input-to"><?php echo $entry_to_date; ?></label>
						<div class="input-group date">
							<input type="text" name="filter_to" value="" placeholder="<?php echo $entry_to_date; ?>" id="input-to" data-date-format="YYYY-MM-DD" class="form-control" />
							<span class="input-group-btn">
							  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
							</span>
						</div>
						<label class="control-label" for="input-button"></label>
						<button style="margin-top:7px;" type="button" id="button-filter" class="btn btn-primary btn-block"><i class="fa fa-filter"></i> <?php echo $button_filter; ?></button>
					</div>
					 <div class="col-sm-4">
						<label class="control-label" for="input-vistor"><?php echo $entry_vistor; ?></label>
						<select class="form-control" name="filter_vistor">
							<option value="*"></option>
							<option value="1"><?php echo $text_registered; ?></option>
							<option value="2"><?php echo $text_guest; ?></option>
						</select>
						
						<label class="control-label" for="input-button"></label>
						<button rel="abandoned_cart" style="margin-top:7px;" type="button" class="btn btn-danger btn-block bulk_delete"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</button>
					 </div>
				</div>
			</div>
			<div class="well well-sm">
				<div class="row">
					<div class="col-sm-4 pull-right">
						<label class="control-label" for="input-button"></label>
						<button style="margin-top:7px;" type="button" id="notifycustomer-button" class="btn btn-info btn-block"><i class="fa fa-envelope" aria-hidden="true"></i> <?php echo $entry_Bulk_email; ?></button>
					 </div>
			   </div>
			</div>
			<div id="abandoned_cart"></div>
		  </div>
		  <div class="tab-pane" id="tab-notified_abandoned_cart">
			<div class="alert alert-info"><i class="fa fa-exclamation-circle"></i> Notified Cart Record</div>
			 <div class="well well-sm">
			   <div class="row">
					 <div class="col-sm-4">
						<label class="control-label" for="input-form-date"><?php echo $entry_from_date; ?></label>
						<div class="input-group date">
							<input type="text" name="filter_from" value="" placeholder="<?php echo $entry_from_date; ?>" id="input-from" data-date-format="YYYY-MM-DD" class="form-control" />
							<span class="input-group-btn">
							  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
							</span>
						</div>
					</div>
					 <div class="col-sm-4">
						<label class="control-label" for="input-to"><?php echo $entry_to_date; ?></label>
						<div class="input-group date">
							<input type="text" name="filter_to" value="" placeholder="<?php echo $entry_to_date; ?>" id="input-to" data-date-format="YYYY-MM-DD" class="form-control" />
							<span class="input-group-btn">
							  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
							</span>
						</div>
						<label class="control-label" for="input-button"></label>
						
						<button style="margin-top:7px;" type="button" id="button-notified-filter" class="btn btn-primary btn-block"><i class="fa fa-filter"></i> <?php echo $button_filter; ?></button>
					 </div>
					 <div class="col-sm-4">
						<label class="control-label" for="input-vistor"><?php echo $entry_vistor; ?></label>
						<select class="form-control" name="filter_vistor">
							<option value="*"></option>
							<option value="1"><?php echo $text_registered; ?></option>
							<option value="2"><?php echo $text_guest; ?></option>
						</select>
					 </div>
					 <div class="col-sm-4">
						<label class="control-label" for="input-button"></label>
						<button rel="notified_abandoned_cart" style="margin-top:7px;" type="button" class="btn btn-danger btn-block bulk_delete"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</button>
					 </div>
				</div>
			</div>
			
			<div id="notified_abandoned_cart"></div>
		  </div>
		  <div class="tab-pane" id="tab-abandoned_orders">
			<div class="well well-sm">
			   <div class="row">
					 <div class="col-sm-3">
						<label class="control-label" for="input-form-date"><?php echo $entry_from_date; ?></label>
						<div class="input-group date">
							<input type="text" name="filter_from" value="" placeholder="<?php echo $entry_from_date; ?>" id="input-from" data-date-format="YYYY-MM-DD" class="form-control" />
							<span class="input-group-btn">
							  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
							</span>
						</div>
					 </div>
					 <div class="col-sm-3">
						<label class="control-label" for="input-to"><?php echo $entry_to_date; ?></label>
						<div class="input-group date">
							<input type="text" name="filter_to" value="" placeholder="<?php echo $entry_to_date; ?>" id="input-to" data-date-format="YYYY-MM-DD" class="form-control" />
							<span class="input-group-btn">
							  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
							</span>
						</div>
					 </div>
					 <div class="col-sm-3">
						<label class="control-label" for="input-button"></label>
						<button style="margin-top:7px;" type="button" id="button-orderfilter" class="btn btn-primary btn-block"><i class="fa fa-filter"></i> <?php echo $button_filter; ?></button>
					 </div>
					 <div class="col-sm-3">
						<label class="control-label" for="input-button"></label>
					     <button style="margin-top:7px;" type="button" id="button-deleteorder" class="btn btn-danger btn-block"><i class="fa fa-trash-o"></i> <?php echo $button_delete; ?></button>
					 </div>
				</div>
			</div>
			<div id="complete_orders"></div>
		  </div>
		  <div class="tab-pane" id="tab-unused-coupons">
			<div class="well well-sm">
				<div class="row">
					<div class="col-sm-4">
						<label class="control-label" for="input-email"><?php echo $entry_customer_email; ?></label>
						<input type="text" name="filter_email" value="" placeholder="<?php echo $entry_customer_email; ?>" id="input-email" class="form-control" />
					</div>
					<div class="col-sm-4">
						<label class="control-label"> &nbsp; </label>
						<button type="button" id="button-order-filter" class="btn btn-primary btn-block pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
					</div>
					<div class="col-sm-4">
						<label class="control-label"> &nbsp; </label>
						<button type="button" class="btn btn-danger btn-block pull-right button_delete_coupon"><i class="fa fa-trash-o"></i> <?php echo $button_delete; ?></button>
					</div>
				</div>
			</div>
			<div id="customer_loadhtml"></div>
		</div>
		<div class="tab-pane" id="tab-used-coupons">
			<div class="well well-sm">
				<div class="row">
					<div class="col-sm-4">
						<label class="control-label" for="input-email"><?php echo $entry_customer_email; ?></label>
						<input type="text" name="filter_email" value="" placeholder="<?php echo $entry_customer_email; ?>" id="input-email" class="form-control" />
					</div>
					<div class="col-sm-4">
						<label class="control-label"> &nbsp; </label>
						<button type="button" id="button-customer-filter" class="btn btn-primary btn-block pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
					</div>
					<div class="col-sm-4">
						<label class="control-label"> &nbsp; </label>
						<button type="button" class="btn btn-danger btn-block pull-right button_delete_coupon"><i class="fa fa-trash-o"></i> <?php echo $button_delete; ?></button>
					</div>
				</div>
			</div>
			<div id="coupons_loadhtml"></div>
		</div>
		<div class="tab-pane" id="tab-language">
		   <div class="form-group">
				<label class="col-sm-2 control-label" for="input-button_label"><span data-toggle="tooltip" title="admin menu">Admin Menu</span></label>
				<div class="col-sm-10">
					 <ul class="nav nav-tabs" id="language">
						<?php foreach ($languages as $language) { ?>
						<li><a href="#language<?php echo $language['language_id']; ?>" data-toggle="tab">
						<?php if(VERSION >= '2.2.0.0') { ?>
						<img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" title="<?php echo $language['name']; ?>" /> 
						<?php } else { ?> 
						<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> 
						<?php } ?> <?php echo $language['name']; ?></a></li>
						<?php } ?>
					 </ul>
					 <div class="tab-content">
						<div class="form-group">
							<label class="col-sm-2 control-label" for="input-button_label"><span data-toggle="tooltip" title="Primary Menu Name"><?php echo $entry_menu; ?></span></label>
							<div class="col-sm-8">
							<?php foreach ($languages as $language) { ?>
								<div class="tab-pane" id="language<?php echo $language['language_id']; ?>">
									<input type="text" class="form-control" name="module_recover_carts_label<?php echo $language['language_id']; ?>" value="<?php echo isset(${'module_recover_carts_label' . $language['language_id']}) ? ${'module_recover_carts_label' . $language['language_id']} : ''; ?>"/>
								</div>
								<?php } ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="tab-pane" id="tab-cron">
			<div class="form-group">
			  <label class="col-sm-2 control-label">Email Template</label>
				<div class="col-sm-5">
				   <select class="form-control" name="template">
					<?php foreach($templates as $template){ ?>
					  <option value="<?php echo $template['abandonedcart_email_template_id']; ?>"><?php echo $template['title']; ?></option>
					<?php } ?>
				   </select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">Vistor Type</label>
				<div class="col-sm-5">
					<select class="form-control" name="vistor_type">
						<option value="all">All Visitor</option>
						<option value="registered"><?php echo $text_registered; ?></option>
						<option value="guest"><?php echo $text_guest; ?></option>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">Condition</label>
				<div class="col-sm-5">
					<select class="form-control" name="condition">
						<option value="unnotified">Only Unnotified Visitor</option>
						<option value="notified">Only Notified Visitor</option>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">Links</label>
				<div class="col-sm-10">
				 <div id="addcron">
					<?php if( $module_recover_carts_cronlinks ): foreach($module_recover_carts_cronlinks as $link){ ?>
					<div class="alert form-control"><?php echo $link; ?> <button type="button" class="close" data-dismiss="alert">&times;</button><input type="hidden" name="module_recover_carts_cronlinks[]" value="<?php echo $link; ?>"/></div>
					<?php } endif; ?>
				 </div>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label"></label>
				<div class="col-sm-5">
					<button style="margin-top:7px;" type="button" id="button-generate" class="btn btn-success btn-block"><i class="fa fa-plus-circle" aria-hidden="true"></i> Generate Link</button>
				</div>
			</div>
			<div class="alert alert-info">
				<h2 style="margin: 0px;"><a target="_blank" href="http://support.hostgator.in/articles/cpanel/what-are-cron-jobs"><b style="font-size: 18px;">What are Cron Jobs?</b></a></h2>  <br/>
			</div>
			<div class="alert alert-info">
				<h2 style="margin: 0px;"><a target="_blank" href="http://support.hostgator.in/articles/cpanel/what-do-i-put-for-the-cron-job-command"><b style="font-size: 18px;">What do I put for the cron job Command ?</b></a></h2>  <br/>
			</div>
		</div>
			<div class="tab-pane" id="tab-analytics">
				<div class="col-sm-5 well">
					<div id="piechart_3d" style="width: 900px; height: 335px;"></div>
					<div id="drawnotificationChart" style="width: 900px; height: 335px;"></div>
					<div id="drawcouponsChart" style="width: 900px; height: 335px;"></div>
				</div>
				<div class="col-sm-7">
					<table  class="table table-bordered">
						<thead>
						  <tr>
							<td>Vistor Type</td>
							<td>Totals</td>
						  </tr>
						</thead>
						 <tbody>
							  <tr>
								<td>Registered Vistor</td>
								<td><?php echo $registeredebcarts; ?></td>
							  </tr>
							  <tr>
								<td>Guest Vistor</td>
								<td><?php echo $guestebcarts; ?></td>
							  </tr>
						 </tbody>
						  <tfoot>
							<tr>
								<td>Total</td>
								<td><?php echo $totalebcarts; ?></td>
							</tr>
						  </tfoot>
					</table>
					<table class="table table-bordered">
						<thead>
						  <tr>
							<td>Notification</td>
							<td>Total</td>
						  </tr>
						</thead>
						 <tbody>
							  <tr>
								<td>Notified</td>
								<td><?php echo $totalnotify; ?></td>
							  </tr>
							  <tr>
								<td>Unnotified</td>
								<td><?php echo $totalunnotify; ?></td>
							  </tr>
						 </tbody>
					</table>
					<table  class="table table-bordered">
						<thead>
						  <tr>
							<td>Given Coupons</td>
							<td>Total</td>
						  </tr>
						</thead>
						 <tbody>
							  <tr>
								<td>Unused Coupons</td>
								<td><?php echo $total_unused_coupons; ?></td>
							  </tr>
							  <tr>
								<td>Used Coupons</td>
								<td><?php echo $total_used_coupons; ?></td>
							  </tr>
							  <tr>
								<td>Expired Coupons</td>
								<td><?php echo $totalexpirecoupons; ?></td>
							  </tr>
						 </tbody>
					</table>
					<table class="table table-bordered">
					 <tr>
						<th>Top 10 Last Visited Pages</th>
						<th>Date & Time</th>
						<th>No. of Visit</th>
					 </tr>
					<?php foreach($visitor_hisorys as $history): ?>
					  <tr>
						<td><a target="_new" href="<?php echo $history['visit_page']; ?>">../<?php echo substr($history['visit_page'],-30); ?></a></td>
						<td><?php echo $history['visit_date']; ?></td>
						<td><?php echo $history['total_count']; ?></td>
					  </tr>
					<?php endforeach; ?>
					</table>
				</div>
			</div>
			<div class="tab-pane" id="tab-support">
				<p class="text-center">For Support and Query Feel Free to contact:<br /><strong>extensionsbazaar@gmail.com</strong></p>
			</div>
		  </div>
        </form>
      </div>
    </div>
  </div>
</div>
<div id="notifycustomer" class="modal fade" role="dialog">
  <div class="modal-dialog">
	<!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Notify Customer</h4>
      </div>
      <div class="modal-body">
		<form class="form-horizontal">
		 <div class="form-group">
			<label class="col-sm-12">Choose Email Template</label>
			<div class="col-sm-12">
			   <select class="form-control" name="template_id">
				<?php foreach($templates as $template){ ?>
				  <option value="<?php echo $template['abandonedcart_email_template_id']; ?>"><?php echo $template['title']; ?></option>
				<?php } ?>
			   </select>
			</div>
		 </div>
		 <div class="form-group">
			<label class="col-sm-12">Delete Record After Notify</label>
			<div class="col-sm-12">
			   <label class="radio-inline">
				 <input type="radio" name="remove_record" value="1"/> Yes
			   </label>
			   <label class="radio-inline">
				 <input type="radio" checked="checked" name="remove_record" value="0"/> No
			   </label>
			</div>
		 </div>
		  <input type="hidden" name="sendmailrecord" value="abandoned_cart"/>
		</form>
      </div>
      <div class="modal-footer">
        <button id="sendemail" class="btn btn-success btn-block">Send</button>
      </div>
    </div>
  </div>
</div>
<div id="vistordata"></div>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
  google.charts.load("current", {packages:["corechart"]});
  google.charts.setOnLoadCallback(drawChart);
  function drawChart(){
	var data = google.visualization.arrayToDataTable([
	  ['Task', 'Hours per Day'],
	  ['Guest',     <?php echo $guestebcarts; ?>],
	  ['Registered',  <?php echo $registeredebcarts; ?>],
	]);

	var options = {
	  title: 'Abandoned Carts',
	  is3D: true,
	  'width':400,
      'height':300
	};

	var chart = new google.visualization.PieChart(document.getElementById('piechart_3d'));
	chart.draw(data, options);
  }
  google.charts.load("current", {packages:["corechart"]});
  google.charts.setOnLoadCallback(drawcouponsChart);
  function drawcouponsChart(){
	var data = google.visualization.arrayToDataTable([
	  ['Task', 'Hours per Day'],
	  ['Unused Coupons',     <?php echo $total_unused_coupons; ?>],
	  ['Used Coupons',  <?php echo $total_used_coupons; ?>],
	]);

	var options = {
	  title: 'Given Coupons',
	  is3D: true,
	  'width':400,
      'height':300
	};

	var chart = new google.visualization.PieChart(document.getElementById('drawcouponsChart'));
	chart.draw(data, options);
  }
  
  google.charts.load("current", {packages:["corechart"]});
  google.charts.setOnLoadCallback(drawnotificationChart);
  function drawnotificationChart(){
	var data = google.visualization.arrayToDataTable([
	  ['Task', 'Hours per Day'],
	  ['Notified',     <?php echo $totalnotify; ?>],
	  ['Unnotified',  <?php echo $totalunnotify; ?>],
	]);

	var options = {
	  title: 'Notification',
	  is3D: true,
	  'width':400,
      'height':300
	};

	var chart = new google.visualization.PieChart(document.getElementById('drawnotificationChart'));
	chart.draw(data, options);
  }
</script>
<script type="text/javascript"><!--
$('#button-generate').on('click',function(){
	var template_id = $('select[name="template"]').val();
	var vistor_type = $('select[name="vistor_type"]').val();
	var condition = $('select[name="condition"]').val();
	var url  = '<?php echo HTTP_CATALOG ?>index.php?route=extension/ebabandoned_cron&template_id='+template_id+'&vistor_type='+vistor_type+'&condition='+condition+'&store_id=<?php echo $store_id; ?>';
	html = '<div class="alert form-control"><?php echo HTTP_CATALOG ?>index.php?route=extension/ebabandoned_cron&template_id='+template_id+'&vistor_type='+vistor_type+'&condition='+condition+'&store_id=<?php echo $store_id; ?> <button type="button" class="close" data-dismiss="alert">&times;</button><input type="hidden" name="module_recover_carts_cronlinks[]" value="'+url+'"/></div>'; 
	$('#addcron').append(html);
});

$(document).delegate('.deletecart', 'click', function(e) {
	if (confirm("Are you sure?") == true) {
		var ebabandonedcart_id = $(this).attr('rel');
		var node = this;
		$.ajax({
			url: 'index.php?route=extension/module/recover_carts/deletecart&token=<?php echo $token; ?>&ebabandonedcart_id='+ebabandonedcart_id,
			dataType: 'json',
			beforeSend: function() {
				$(node).button('loading');
			},
			complete: function() {
				$(node).button('reset');
			},
			success: function(json){
				$('.alert, .text-danger').remove();
				$('.form-group').removeClass('has-error');
				if(json['success']){
					loadebcart();
					loadebnotifiedcart();
				}
			}
		});
	}
});

$('.bulk_delete').on('click',function(){
	var id = $(this).attr('rel');
	node = this;
	var allchecked  = $('#'+id+' input[type="checkbox"]:checked').map(function(){return $(this).val();}).get();
	if(allchecked!=''){
		$.ajax({
			url: 'index.php?route=extension/module/recover_carts/deletecart&token=<?php echo $token; ?>',
			type:'post',
			dataType: 'json',
			data: '&ebcartids='+allchecked,
			dataType: 'json',
			beforeSend: function() {
				$(node).button('loading');
			},
			complete: function() {
				$(node).button('reset');
			},
			success: function(json){
				$('.alert, .text-danger').remove();
				$('.form-group').removeClass('has-error');
				if(json['success']){
					loadebcart();
					loadebnotifiedcart();
				}
			}
		});
	}else{
		alert("Please select the Cart Customers");
	}
});

$(document).delegate('.sendnotify', 'click', function(e){
	var ebabandonedcart_id = $(this).attr('rel');
	$('#abandoned_cart input[type="checkbox"]:checked').prop('checked',false);
	$('#notified_abandoned_cart input[type="checkbox"]:checked').prop('checked',false);
	$('#'+ebabandonedcart_id+' input[type="checkbox"]').prop('checked',true);
	var id  = $('#'+ebabandonedcart_id).parent().parent().parent().parent().attr('id');
	$('input[name="sendmailrecord"]').attr('value',id);
	$('#notifycustomer').modal('show');
});

$('#notifycustomer-button').on('click',function(){
	var allchecked  = $('#abandoned_cart input[type="checkbox"]:checked').map(function(){return $(this).val();}).get();
	if(allchecked!=''){
		$('input[name="sendmailrecord"]').attr('value','abandoned_cart');
		$('#notifycustomer').modal('show');
	}else{
		alert("Please select the Cart Customers");
	}
});

$('#sendemail').on('click',function(){
	var id = $('input[name="sendmailrecord"]').val();
	var ebcartids  = $('#'+id+' input[type="checkbox"]:checked').map(function(){return $(this).val();}).get();
	var template_id = $('select[name="template_id"]').val();
	var remove_record = $('input[name="remove_record"]:checked').val();
	$.ajax({
		url: 'index.php?route=extension/module/recover_carts/sendemail&token=<?php echo $token; ?>&store_id=<?php echo $store_id; ?>',
		type:'post',
		dataType: 'json',
		data: 'template_id='+template_id+'&ebcartids='+ebcartids+'&remove_record='+remove_record,
		beforeSend: function() {
			$('#sendemail').button('loading');
		},
		complete: function() {
			$('#sendemail').button('reset');
		},
		success: function(json){
			$('.alert, .text-danger').remove();
			$('.form-group').removeClass('has-error');
			if(json['success']){
				$('#notifycustomer').modal('hide');
				//$('.panel-heading').before('<div class="alert alert-success"><i class="fa fa-exclamation-circle"></i> '+ json['success'] +'<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
				
				loadebcart();
				loadebnotifiedcart();
			}
		}
	});
});

/*Start XML*/
$('#notified_abandoned_cart').load('index.php?route=extension/module/recover_carts/getebcarts&token=<?php echo $token; ?>&store_id=<?php echo $store_id; ?>&filter_notify=1');
$('#button-notified-filter').on('click', function(){
	loadebnotifiedcart();
});

function loadebnotifiedcart(){
	var url = 'index.php?route=extension/module/recover_carts/getebcarts&token=<?php echo $token; ?>&store_id=<?php echo $store_id; ?>&filter_notify=1';

	var filter_from = $('#tab-notified_abandoned_cart input[name=\'filter_from\']').val();

	if (filter_from) {
		url += '&filter_from=' + encodeURIComponent(filter_from);
	}

	var filter_to = $('#tab-notified_abandoned_cart input[name=\'filter_to\']').val();

	if (filter_to) {
		url += '&filter_to=' + encodeURIComponent(filter_to);
	}
	
	var filter_vistor = $('#tab-notified_abandoned_cart select[name=\'filter_vistor\']').val();

	if (filter_vistor != '*') {
		url += '&filter_vistor=' + encodeURIComponent(filter_vistor);
	}
	
	getebcarts(url,'notified_abandoned_cart');
}

$(document).delegate('#notified_abandoned_cart .pagination a', 'click', function(e) {
	e.preventDefault();
	getebcarts(this.href,'notified_abandoned_cart');
});
/*END XML*/

$('#abandoned_cart').load('index.php?route=extension/module/recover_carts/getebcarts&token=<?php echo $token; ?>&store_id=<?php echo $store_id; ?>');
$('#button-filter').on('click', function(){
	loadebcart();
});


function loadebcart(){
	var url = 'index.php?route=extension/module/recover_carts/getebcarts&token=<?php echo $token; ?>&store_id=<?php echo $store_id; ?>';

	var filter_from = $('#tab-abandoned_cart input[name=\'filter_from\']').val();

	if (filter_from) {
		url += '&filter_from=' + encodeURIComponent(filter_from);
	}

	var filter_to = $('#tab-abandoned_cart input[name=\'filter_to\']').val();

	if (filter_to) {
		url += '&filter_to=' + encodeURIComponent(filter_to);
	}
	
	var filter_vistor = $('#tab-abandoned_cart select[name=\'filter_vistor\']').val();

	if (filter_vistor != '*') {
		url += '&filter_vistor=' + encodeURIComponent(filter_vistor);
	}

	getebcarts(url,'abandoned_cart');
}

function getebcarts(url,id){
	$.ajax({
		url: url,
		dataType: 'html',
		beforeSend: function() {
			$('#'+id).html('<div class="loader text-center"><img src="view/image/ebcart/load.gif" /></div>');
		},
		complete: function() {
			$('.loader').remove();
		},
		success: function(html) {
			$('#'+id).html(html);
		}
	});
}

$(document).delegate('#abandoned_cart .pagination a', 'click', function(e) {
	e.preventDefault();

	getebcarts(this.href,'abandoned_cart');
});

//Orders
$('#complete_orders').load('index.php?route=extension/module/recover_carts/getOrders&token=<?php echo $token; ?>');

$('#button-orderfilter').on('click', function(){
	loadorder();
});


function loadorder(){
	var url = 'index.php?route=extension/module/recover_carts/getOrders&token=<?php echo $token; ?>&store_id=<?php echo $store_id; ?>';

	var filter_from = $('#tab-abandoned_orders input[name=\'filter_from\']').val();

	if (filter_from) {
		url += '&filter_from=' + encodeURIComponent(filter_from);
	}

	var filter_to = $('#tab-abandoned_orders input[name=\'filter_to\']').val();

	if (filter_to) {
		url += '&filter_to=' + encodeURIComponent(filter_to);
	}
	
	getOrders(url);
}

function getOrders(url){
	$.ajax({
		url: url,
		dataType: 'html',
		beforeSend: function() {
			$('#complete_orders').html('<div class="loader text-center"><img src="view/image/ebcart/load.gif" /></div>');
		},
		complete: function() {
			$('.loader').remove();
		},
		success: function(html) {
			$('#complete_orders').html(html);
		}
	});
}

$(document).delegate('#complete_orders .pagination a', 'click', function(e) {
	e.preventDefault();

	getOrders(this.href);
});

$('#button-deleteorder').on('click',function(){
	var allchecked  = $('#complete_orders input[type="checkbox"]:checked').map(function(){return $(this).val();}).get();
	if(allchecked!=''){
		$.ajax({
			url: 'index.php?route=extension/module/recover_carts/deleteOrder&token=<?php echo $token; ?>',
			type:'post',
			dataType: 'json',
			data: '&orderids='+allchecked,
			dataType: 'json',
			beforeSend: function(){
				$('#button-deleteorder').button('loading');
			},
			complete: function(){
				$('#button-deleteorder').button('reset');
			},
			success: function(json){
				$('.alert, .text-danger').remove();
				$('.form-group').removeClass('has-error');
				if(json['success']){
					loadorder();
				}
			}
		});
	}else{
		alert("Please select the Orders");
	}
});


/* used-coupons */
$('#button-customer-filter').on('click', function() {
	var url = 'index.php?route=extension/module/recover_carts/getUsedCoupons&token=<?php echo $token; ?>';
	
	var filter_customer_name = $('#tab-used-coupons input[name=\'filter_customer_name\']').val();
	if (filter_customer_name) {
		url += '&filter_customer_name=' + encodeURIComponent(filter_customer_name);
	}

	
	docouponsAction(url);
});

$(document).ready(function() {
	var url = 'index.php?route=extension/module/recover_carts/getUsedCoupons&token=<?php echo $token; ?>';
	
	docouponsAction(url);
});

$('#coupons_loadhtml').delegate('.pagination a', 'click', function(e) {
	e.preventDefault();

	
	docouponsAction(this.href);
});

$('#coupons_loadhtml').delegate('thead tr td.sortorder a', 'click', function(e) {
	e.preventDefault();

	
	docouponsAction(this.href);
});

function docouponsAction(url) {
	$.ajax({
		url: url,
		dataType: 'html',
		beforeSend: function() {
			$('#coupons_loadhtml').html('<div class="loader text-center"><img src="view/image/ebcart/load.gif" /></div>');
		},
		complete: function() {
			$('.loader').remove();
		},
		success: function(html){
			$('#coupons_loadhtml').html(html);
		}
	});
}


//unusecoupon
$('#button-order-filter').on('click', function() {
	var url = 'index.php?route=extension/module/recover_carts/getunuseCoupons&token=<?php echo $token; ?>';
	
	var filter_customer_name = $('#tab-unused-coupons input[name=\'filter_customer_name\']').val();
	if (filter_customer_name) {
		url += '&filter_customer_name=' + encodeURIComponent(filter_customer_name);
	}
	
	var filter_email = $('#tab-unused-coupons input[name=\'filter_email\']').val();
	if (filter_email) {
		url += '&filter_email=' + encodeURIComponent(filter_email);
	}
	
	doCustomerAction(url);
});


$(document).ready(function() {
	var url = 'index.php?route=extension/module/recover_carts/getunuseCoupons&token=<?php echo $token; ?>';
	
	doCustomerAction(url);
});

$('#customer_loadhtml').delegate('.pagination a', 'click', function(e) {
	e.preventDefault();

	
	doCustomerAction(this.href);
});

$('#customer_loadhtml').delegate('thead tr td.sortorder a', 'click', function(e) {
	e.preventDefault();

	
	doCustomerAction(this.href);
});

function doCustomerAction(url) {
	$.ajax({
		url: url,
		dataType: 'html',
		beforeSend: function() {
			$('#customer_loadhtml').html('<div class="loader text-center"><img src="view/image/ebcart/load.gif" /></div>');
		},
		complete: function() {
			$('.loader').remove();
		},
		success: function(html){
			$('#customer_loadhtml').html(html);
		}
	});
}

//unused delete Coupons
$('#tab-unused-coupons .button_delete_coupon').on('click',function(){
	var couponids  = $('#tab-unused-coupons input[type="checkbox"]:checked').map(function(){return $(this).val();}).get();
	if(couponids!=''){
		
		
		var url = 'index.php?route=extension/module/recover_carts/getunuseCoupons&token=<?php echo $token; ?>';
	
		var filter_email = $('#tab-unused-coupons input[name=\'filter_email\']').val();
		if (filter_email) {
			url += '&filter_email=' + encodeURIComponent(filter_email);
		}
	
		deletecoupon(couponids,url);
	
	}else{
		alert("Choose coupons which you want to delete.");
	}
});

$('#tab-unused-coupons').delegate('.deletecoupononebyeone', 'click', function(e){
	var coupon_id = $(this).attr('rel');
	$('#tab-unused-coupons input[type="checkbox"]:checked').prop('checked',false);
	$('#tab-unused-coupons #'+coupon_id+' input[type="checkbox"]').prop('checked',true);
	$('#tab-unused-coupons .button_delete_coupon').trigger('click');
});

//used delete Coupons
$('#tab-used-coupons .button_delete_coupon').on('click',function(){
	var couponids  = $('#tab-used-coupons input[type="checkbox"]:checked').map(function(){return $(this).val();}).get();
	if(couponids!=''){
		
		var url = 'index.php?route=extension/module/recover_carts/getUsedCoupons&token=<?php echo $token; ?>';
	
		var filter_customer_name = $('#tab-used-coupons input[name=\'filter_customer_name\']').val();
		if (filter_customer_name) {
			url += '&filter_customer_name=' + encodeURIComponent(filter_customer_name);
		}
	
		deletecoupon(couponids,url);
	
	}else{
		alert("Choose coupons which you want to delete.");
	}
});

$('#tab-used-coupons').delegate('.deletecoupononebyeone', 'click', function(e){
	var coupon_id = $(this).attr('rel');
	$('#tab-used-coupons input[type="checkbox"]:checked').prop('checked',false);
	$('#tab-used-coupons #'+coupon_id+' input[type="checkbox"]').prop('checked',true);
	$('#tab-used-coupons .button_delete_coupon').trigger('click');
});

//delete action
function deletecoupon(couponids,url){
	if (confirm("Are you sure?") == true){
		$.ajax({
			url: 'index.php?route=extension/module/recover_carts/deletecoupons&token=<?php echo $token; ?>',
			type:'post',
			data:'coupon_ids='+couponids,
			dataType: 'json',
			success: function(json){
				$('.alert, .text-danger').remove();
				$('.form-group').removeClass('has-error');
				if(json['success']){
					doCustomerAction(url);
				}
			}
		});
	}
}

//Vistor history
function vistorhistory(ebabandonedcart_id){
	$.ajax({
		url: 'index.php?route=extension/module/recover_carts/vistorhistory&token=<?php echo $token; ?>',
		type:'post',
		data:'ebabandonedcart_id='+ebabandonedcart_id,
		dataType: 'html',
		success: function(html){
			$('#vistordata').html(html);
			$('#vistorhistory').modal();
		}
	});
}

//Clean Record
$('#cleanrecord').on('click',function(){
  $.ajax({
		url: 'index.php?route=extension/module/recover_carts/cleanrecord&token=<?php echo $token; ?>',
		type:'post',
		dataType: 'json',
		success: function(json){
			 location.reload();
		}
	});
});

$('#cleancoupons').on('click',function(){
  $.ajax({
		url: 'index.php?route=extension/module/recover_carts/cleancoupons&token=<?php echo $token; ?>',
		type:'post',
		dataType: 'json',
		success: function(json){
			 location.reload();
		}
	});
});

// Drag Feilds
$(document).ready(function() {
$("#table-fields tbody").sortable({
	cursor: "move",
	stop: function() {
		$('#table-feilds tbody .row-group').each(function() {
			$(this).find('.mydragsort').val($(this).index());
		});
	}
});
});
//--></script>
<script src="view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
<link href="view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css" type="text/css" rel="stylesheet" media="screen"/>
<script type="text/javascript"><!--
$('.date').datetimepicker({
	pickTime: false
});
//--></script>
<script>
$('select[name="module_recover_carts_protocol"]').on('change',function(){
	if($(this).val()=='sendgrid'){
		$('.sendgrid').removeClass('hide');
	}else{
		$('.sendgrid').addClass('hide');
	}
});
$('select[name="module_recover_carts_protocol"]').trigger('change');
$('#language a:first').tab('show');
$('#languagex a:first').tab('show');
</script>
<?php echo $footer; ?>