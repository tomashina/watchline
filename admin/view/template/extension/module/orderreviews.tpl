<?php echo $header; ?><?php echo $column_left; ?>
<div id="content" class="OrderReviews">
 <script type="text/javascript">
	NProgress.configure({
		showSpinner: false,
		ease: 'ease',
		speed: 500,
		trickleRate: 0.2,
		trickleSpeed: 200 
	});
 </script>
 <div class="page-header">
    <div class="container-fluid">
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
	<?php echo (empty($moduleData['LicensedOn'])) ? base64_decode('ICAgIDxkaXYgY2xhc3M9ImFsZXJ0IGFsZXJ0LWRhbmdlciBmYWRlIGluIj4NCiAgICAgICAgPGJ1dHRvbiB0eXBlPSJidXR0b24iIGNsYXNzPSJjbG9zZSIgZGF0YS1kaXNtaXNzPSJhbGVydCIgYXJpYS1oaWRkZW49InRydWUiPsOXPC9idXR0b24+DQogICAgICAgIDxoND5XYXJuaW5nISBVbmxpY2Vuc2VkIHZlcnNpb24gb2YgdGhlIG1vZHVsZSE8L2g0Pg0KICAgICAgICA8cD5Zb3UgYXJlIHJ1bm5pbmcgYW4gdW5saWNlbnNlZCB2ZXJzaW9uIG9mIHRoaXMgbW9kdWxlISBZb3UgbmVlZCB0byBlbnRlciB5b3VyIGxpY2Vuc2UgY29kZSB0byBlbnN1cmUgcHJvcGVyIGZ1bmN0aW9uaW5nLCBhY2Nlc3MgdG8gc3VwcG9ydCBhbmQgdXBkYXRlcy48L3A+PGRpdiBzdHlsZT0iaGVpZ2h0OjVweDsiPjwvZGl2Pg0KICAgICAgICA8YSBjbGFzcz0iYnRuIGJ0bi1kYW5nZXIiIGhyZWY9ImphdmFzY3JpcHQ6dm9pZCgwKSIgb25jbGljaz0iJCgnYVtocmVmPSNpc2Vuc2Vfc3VwcG9ydF0nKS50cmlnZ2VyKCdjbGljaycpIj5FbnRlciB5b3VyIGxpY2Vuc2UgY29kZTwvYT4NCiAgICA8L2Rpdj4=') : '' ?>
	<?php if ($error_warning) { ?>
		<div class="alert alert-danger autoSlideUp"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
			<button type="button" class="close" data-dismiss="alert">&times;</button>
		</div>
	<?php } ?>
    <?php if ($success) { ?>
        <div class="alert alert-success autoSlideUp"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        <script>$('.autoSlideUp').delay(3000).fadeOut(600, function(){ $(this).show().css({'visibility':'hidden'}); }).slideUp(600);</script>
    <?php } ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title"><i class="fa fa-list"></i>&nbsp;<span style="vertical-align:middle;font-weight:bold;">Module settings</span></h3>
            <div class="storeSwitcherWidget">
            	
                	<button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">&nbsp;<?php echo $store['name']; if($store['store_id'] == 0) echo " <strong>".$text_default."</strong>"; ?>&nbsp;<span class="caret"></span><span class="sr-only">Toggle Dropdown</span></button>
                	<ul class="dropdown-menu" role="menu">
                    	<?php foreach ($stores as $st) { ?>
                            <li><a href="index.php?route=<?php echo $module_path; ?>&store_id=<?php echo $st['store_id'];?>&token=<?php echo $token; ?>"><?php echo $st['name']; ?></a></li>
                    	<?php } ?> 
                	</ul>
            	
            </div>
        </div>
        <div class="panel-body">
            <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form"> 
                <input type="hidden" name="store_id" value="<?php echo $store['store_id']; ?>" />
				<input type="hidden" name="<?php echo $moduleNameSmall; ?>_status" value="1" />
                <div class="tabbable">
                    <div class="tab-navigation form-inline">
                        <ul class="nav nav-tabs mainMenuTabs" id="mainTabs">
							<li><a href="#controlpanel" role="tab" data-toggle="tab"><i class="fa fa-power-off"></i>&nbsp;Control Panel</a></li>
                            <li><a href="#reviews" role="tab" data-toggle="tab">&nbsp;Received Reviews</a></li>
                            <li><a href="#coupons" role="tab" data-toggle="tab">&nbsp;Coupons</a></li>
                            <li><a href="#logs" role="tab" data-toggle="tab">&nbsp;<?php echo $text_mails_log; ?></a></li>
                            <li><a href="#isense_support" role="tab" data-toggle="tab"><i class="fa fa-external-link"></i>&nbsp;Support</a></li>
                        </ul>
                        <div class="tab-buttons">
                            <button type="submit" class="btn btn-success save-changes"><i class="fa fa-check"></i>&nbsp;Save Changes</button>
                            <a onclick="location = '<?php echo $cancel; ?>'" class="btn btn-warning"><i class="fa fa-times"></i>&nbsp;Cancel</a>
                        </div> 
                    </div><!-- /.tab-navigation --> 
                    <div class="tab-content">
                    	<?php
                        if (!function_exists('modification_vqmod')) {
                        	function modification_vqmod($file) {
                        		if (class_exists('VQMod')) {
                       				return VQMod::modCheck(modification($file), $file);
                        		} else {
                        			return modification($file);
                       			}
                        	}
                        }
						?>
						<div id="controlpanel" class="tab-pane"><?php require_once modification_vqmod(DIR_APPLICATION.'view/template/'.$module_path.'/tab_controlpanel.php'); ?></div>
                        <div id="isense_support" class="tab-pane"><?php require_once modification_vqmod(DIR_APPLICATION.'view/template/'.$module_path.'/tab_support.php'); ?></div>
                        <div id="reviews" class="tab-pane"><?php require_once modification_vqmod(DIR_APPLICATION.'view/template/'.$module_path.'/tab_reviews.php'); ?></div>
                        <div id="coupons" class="tab-pane"><?php require_once modification_vqmod(DIR_APPLICATION.'view/template/'.$module_path.'/tab_coupons.php'); ?></div>
                        <div id="logs" class="tab-pane"><?php require_once modification_vqmod(DIR_APPLICATION.'view/template/'.$module_path.'/tab_logs.php'); ?></div>
                    </div> <!-- /.tab-content --> 
                </div><!-- /.tabbable -->
            </form>
        </div> 
    </div>
  </div>
</div>
<!-- Modal for VIEW MESSAGE -->
<div id="logModal" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
          <h4 class="modal-title" id="myModalLabel">View message</h4>
        </div>
        <div id="logModalBody" class="modal-body">
        	<iframe width="100%" height="400" hspace="0" vspace="0" style="border:none;"></iframe>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>

      </div><!-- /.modal-content -->
    </div>
</div>
<!-- Modal for VIEW MESSAGE -->
<div class="modal cronjobs fade in" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
          <h4 class="modal-title" id="myModalLabel">How to set up the cron job?</h4>
        </div>
        <div class="modal-body">
        	<p>If your server supports cron jobs, you can use this line to set up the cron job for the module:
            	<ul>
                	<li>0 0 * * * php <?php echo dirname(DIR_APPLICATION); ?>/vendors/orderreviews/sendEmails.php <?php echo $cronCmdArgs; ?></li>
                </ul>
            	The script will be executed every day at 00:00.
            <p></p>
            <hr />
        	<p>If your server does not support cron jobs, you can try using services such as <strong>easycron.com</strong>, <strong>setcronjob.com</strong> or others which can provide you this feature.<br /><br />
            In order to do that, you have to register in the selected service and use this URL for execution:
            <ul>
                <li>- <?php echo HTTP_CATALOG; ?>vendors/orderreviews/sendEmails.php</li>
            </ul>
            <p></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>

      </div><!-- /.modal-content -->
    </div>
</div>
<script>
$('#mainTabs a:first').tab('show'); // Select first tab
$('.review-list').children().last().children('a').click();
if (window.localStorage && window.localStorage['currentTab']) {
	$('.mainMenuTabs a[href="'+window.localStorage['currentTab']+'"]').tab('show');
}
if (window.localStorage && window.localStorage['currentSubTab']) {
	$('a[href="'+window.localStorage['currentSubTab']+'"]').tab('show');
}
$('.fadeInOnLoad').css('visibility','visible');
$('.mainMenuTabs a[data-toggle="tab"]').click(function() {
	if (window.localStorage) {
		window.localStorage['currentTab'] = $(this).attr('href');
	}
});
$('a[data-toggle="tab"]:not(.mainMenuTabs a[data-toggle="tab"], .review_tabs a[data-toggle="tab"])').click(function() {
	if (window.localStorage) {
		window.localStorage['currentSubTab'] = $(this).attr('href');
	}
});

function showHideStuff($typeSelector, $toggleArea, $selectStatus) {
	if ($typeSelector.val() === $selectStatus) {
		$toggleArea.show(); 
	} else {
		$toggleArea.hide(); 
	}
    $typeSelector.change(function(){
        if ($typeSelector.val() === $selectStatus) {
            $toggleArea.show(300); 
        }
        else {
            $toggleArea.hide(300); 
        }
    });
}

$(function() {
	showHideStuff($('#Checker'), $('#review_tabs'), 'yes'); 
});
 </script>
<?php echo $footer; ?>