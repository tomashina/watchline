<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
		<?php if (!$uprade_needed) { ?>
			<button type="submit" form="form-atpresets" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
		<?php } ?>
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
		<div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
    </div>
    <?php } ?>	
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_title; ?></h3>
      </div>
      <div class="panel-body">
	  <?php if (!$uprade_needed) { ?>
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-atpresets" class="form-horizontal">
	
			<input type="hidden" name="atpresets_installed" value="1" />		  

		  <fieldset>	
		  <div class="form-group">
			<label class="col-sm-2 control-label"><?php echo $entry_delete; ?></label>
            <div class="col-sm-10">
                  <label class="radio-inline">
                    <?php if ($atpresets_delete) { ?>
                    <input type="radio" name="atpresets_delete" value="1" checked="checked" />
                    <?php echo $text_yes; ?>
                    <?php } else { ?>
                    <input type="radio" name="atpresets_delete" value="1" />
                    <?php echo $text_yes; ?>
                    <?php } ?>
                  </label>
                  <label class="radio-inline">
                    <?php if (!$atpresets_delete) { ?>
                    <input type="radio" name="atpresets_delete" value="0" checked="checked" />
                    <?php echo $text_no; ?>
                    <?php } else { ?>
                    <input type="radio" name="atpresets_delete" value="0" />
                    <?php echo $text_no; ?>
                    <?php } ?>
                  </label>			
            </div>
          </div>		
		  <div class="form-group">
			<label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_autoupdate; ?>"><?php echo $entry_autoupdate; ?></span></label>
            <div class="col-sm-10">
                  <label class="radio-inline">
                    <?php if ($atpresets_autoupdate) { ?>
                    <input type="radio" name="atpresets_autoupdate" value="1" checked="checked" />
                    <?php echo $text_yes; ?>
                    <?php } else { ?>
                    <input type="radio" name="atpresets_autoupdate" value="1" />
                    <?php echo $text_yes; ?>
                    <?php } ?>
                  </label>
                  <label class="radio-inline">
                    <?php if (!$atpresets_autoupdate) { ?>
                    <input type="radio" name="atpresets_autoupdate" value="0" checked="checked" />
                    <?php echo $text_no; ?>
                    <?php } else { ?>
                    <input type="radio" name="atpresets_autoupdate" value="0" />
                    <?php echo $text_no; ?>
                    <?php } ?>
                  </label>			
            </div>
          </div>
		  <div class="form-group">
			<label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_allow_multiple; ?>"><?php echo $entry_allow_multiple; ?></span></label>
            <div class="col-sm-10">
                  <label class="radio-inline">
                    <?php if ($atpresets_allow_multiple) { ?>
                    <input type="radio" name="atpresets_allow_multiple" value="1" checked="checked" />
                    <?php echo $text_yes; ?>
                    <?php } else { ?>
                    <input type="radio" name="atpresets_allow_multiple" value="1" />
                    <?php echo $text_yes; ?>
                    <?php } ?>
                  </label>
                  <label class="radio-inline">
                    <?php if (!$atpresets_allow_multiple) { ?>
                    <input type="radio" name="atpresets_allow_multiple" value="0" checked="checked" />
                    <?php echo $text_no; ?>
                    <?php } else { ?>
                    <input type="radio" name="atpresets_allow_multiple" value="0" />
                    <?php echo $text_no; ?>
                    <?php } ?>
                  </label>			
            </div>
          </div>			  
		  <div class="form-group">
			<label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_selecttype; ?>"><?php echo $entry_selecttype; ?></span></label>
            <div class="col-sm-10">
                  <label class="radio-inline">
                    <?php if ($atpresets_selecttype) { ?>
                    <input type="radio" name="atpresets_selecttype" value="1" checked="checked" />
                    <?php echo $text_droplist; ?>
                    <?php } else { ?>
                    <input type="radio" name="atpresets_selecttype" value="1" />
                    <?php echo $text_droplist; ?>
                    <?php } ?>
                  </label>
                  <label class="radio-inline">
                    <?php if (!$atpresets_selecttype) { ?>
                    <input type="radio" name="atpresets_selecttype" value="0" checked="checked" />
                    <?php echo $text_autocomplete; ?>
                    <?php } else { ?>
                    <input type="radio" name="atpresets_selecttype" value="0" />
                    <?php echo $text_autocomplete; ?>
                    <?php } ?>
                  </label>			
            </div>
          </div>
<script type="text/javascript"><!--
$('input[name="atpresets_allow_multiple"]').change(function(){
	if ($('input[name="atpresets_allow_multiple"]:checked').val()==1) {
		$('input[name="atpresets_selecttype"][value="1"]').prop('checked',true);
	}
});
$('input[name="atpresets_selecttype"]').change(function(){
	if ($('input[name="atpresets_selecttype"]:checked').val()==0) {
		$('input[name="atpresets_allow_multiple"][value="0"]').prop('checked',true);
	}
});
//--></script> 		  
		  <div class="form-group">
			<label class="col-sm-2 control-label"><?php echo $entry_use_newline; ?></label>
            <div class="col-sm-10">
                  <label class="radio-inline">
                    <?php if ($atpresets_use_newline) { ?>
                    <input type="radio" name="atpresets_use_newline" value="1" checked="checked" />
                    <?php echo $text_newline; ?>
                    <?php } else { ?>
                    <input type="radio" name="atpresets_use_newline" value="1" />
                    <?php echo $text_newline; ?>
                    <?php } ?>
                  </label>
                  <label class="radio-inline">
                    <?php if (!$atpresets_use_newline) { ?>
                    <input type="radio" name="atpresets_use_newline" value="0" checked="checked" />
                    <?php echo $text_other; ?> <input type="text" name="atpresets_other_character" value="<?php echo $atpresets_other_character; ?>"/>
                    <?php } else { ?>
                    <input type="radio" name="atpresets_use_newline" value="0" />
                    <?php echo $text_other; ?> <input type="text" name="atpresets_other_character" value="<?php echo $atpresets_other_character; ?>"/>
                    <?php } ?>
                  </label>	
            </div>
          </div>			  
		  <div class="form-group">
			<label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_limit; ?>"><?php echo $entry_limit; ?></span></label>
            <div class="col-sm-10">
				<input type="text" name="atpresets_limit" value="<?php echo $atpresets_limit; ?>" placeholder="<?php echo $entry_limit; ?>" id="input-limit" class="form-control" />
            </div>
          </div>
          <div class="form-group">
			<label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_get_attribute_values; ?>"><?php echo $entry_get_attribute_values; ?></span></label>
            <div class="col-sm-10">
				<button type="button" class="btn btn-default" onclick="getAttributes('index.php?route=extension/module/atpresets/get_attributes<?php echo "&token=" . $token; ?>',false)" ><?php echo $text_get_attributes; ?></button> &nbsp;<span id="processmessage" style="text-align: left;"></span>	
            </div>
          </div>			  			  
		</fieldset>	
        </form>
<?php } else { ?>
		<fieldset>	
          <div class="form-group">
            <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_upgrade; ?>"><?php echo $entry_upgrade; ?></span></label>
            <div class="col-sm-10">
				<button type="button" class="btn btn-default" onclick="upgrade('index.php?route=extension/module/atpresets/upgrade<?php echo "&token=" . $token; ?>',false)" ><?php echo $text_upgrade; ?></button> &nbsp;<span id="processmessage1" style="text-align: left;"></span>					
            </div>
          </div>				  
		</fieldset>		
<?php } ?>		
      </div>
    </div>
  </div>
  
<script type="text/javascript"><!--
$(document).ajaxStop(function() {
  $( "#processmessage" ).html( "&nbsp" );
});

function getAttributes(url, next){
	if (!next)
		x=window.confirm("<?php echo $text_confirm_get_attributes;?>");
	else
		x=true;
	if (x){
		$( "#processmessage" ).html( "<b>In progress...</b>" );
		$.ajax({		
			url : url,
			dataType: 'json',	
			success: function(json) { 
				$('.alert, .text-danger').remove();

				if (json['error']) {
					if (json['error']['warning']) {
						$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error']['warning'] + '</div>');
					}
				}

				if (json['next']) {
					if (json['success']) {
						$( "#processmessage" ).html( "<b>In progress..."+ json['success']+"</b>" ); 

						getAttributes(json['next'],true);
					}
				} else {
					if (json['success']) {
						$('#content > .container-fluid').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '</div>');
					}
				}	
			}		
		});
	}
}

//--></script>  
<script type="text/javascript"><!--
$(document).ajaxStop(function() {
  $( "#processmessage1" ).html( "&nbsp" );
});

function upgrade(url,next){
	if (!next)
		x=x=window.confirm("<?php echo $text_confirm_upgrade;?>");
	else
		x=true;
	
	if (x){
		$( "#processmessage1" ).html( "<b>In progress...</b>" );
		$.ajax({		
			url : url,
			dataType: 'json',	
			success: function(json) { 
				$('.alert, .text-danger').remove();

				if (json['error']) {
					if (json['error']['warning']) {
						$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error']['warning'] + '</div>');
					}
				}

				if (json['next']) {
					if (json['success']) {
						$( "#processmessage1" ).html( "<b>In progress..."+ json['success']+"</b>" ); 

						upgrade(json['next'],true);
					}
				} else {
					location.reload(); 
				}	
			}			
		});
	}
}

//--></script>   
</div>
<?php echo $footer; ?>