<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-attemplate" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
<style>
#page-wrap ul li img {
	margin: 0px 3px 6px 3px;
}
</style>  
  <div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $heading_title; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-attemplate" class="form-horizontal">
			<input type="hidden" name="attemplate_id" value="<?php echo $attemplate_id; ?>" />
          <div class="tab-content">
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-name"><?php echo $entry_name; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="name" value="<?php echo $name; ?>" placeholder="<?php echo $text_name; ?>" id="input-name" class="form-control" />
                  <?php if ($error_name) { ?>
                  <div class="text-danger"><?php echo $error_name; ?></div>
                  <?php } ?>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
                <div class="col-sm-10">
				  <select name="status" id="input-status" class="form-control">
					<?php if ($status) { ?>
					<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
					<option value="0"><?php echo $text_disabled; ?></option>
					<?php } else { ?>
					<option value="1"><?php echo $text_enabled; ?></option>
					<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
					<?php } ?>
				  </select>
                </div>
              </div>
  
			  <fieldset>
			  <legend><?php echo $entry_attributes ?></legend>
              <div class="table-responsive">
                <table id="attribute" class="table table-striped table-bordered table-hover">
                  <thead>
                    <tr>
                      <td class="text-left"><?php echo $entry_attribute; ?></td>
                      <td class="text-left"><?php echo $entry_presets; ?></td>
                      <td></td>
                    </tr>
                  </thead>
                  <tbody>
                    <?php $attribute_row = 0; ?>
                    <?php foreach ($attributes as $attribute) { ?>
                    <tr id="attribute-row<?php echo $attribute_row; ?>">
                      <td class="text-left" style="width: 40%;"><input type="text" name="attributes[<?php echo $attribute_row; ?>][name]" value="<?php echo $attribute['name']; ?>" placeholder="<?php echo $entry_attribute; ?>" class="form-control" />
                        <input type="hidden" name="attributes[<?php echo $attribute_row; ?>][attribute_id]" value="<?php echo $attribute['attribute_id']; ?>" /></td>
                      <td class="text-left">		
						<?php if ($atpresets_installed==1) { ?>
							<?php if ($atpresets_selecttype==0) { ?>
								<br><div class="test"><input type="text" name="attributes[<?php echo $attribute_row; ?>][preset]" value="<?php echo htmlentities($attribute['preset'],ENT_QUOTES); ?>" placeholder="<?php echo $text_preset_value; ?>" class="form-control" />
								<input type="hidden" name="attributes[<?php echo $attribute_row; ?>][preset_id][]" value="<?php if (count($attribute['preset_id'])==1) echo $attribute['preset_id']; ?>" /></div>
								
							<?php } else { ?>
								<br><div>
									<?php if ($atpresets_allow_multiple==1) { ?>
									
										<input type="checkbox" id="allow_multiple<?php echo $attribute_row; ?>" name="attributes[<?php echo $attribute_row; ?>][allow_multiple]"
											<?php if ($attribute['allow_multiple']) { ?>checked="checked"<?php } ?>
											onchange="changeSelectionMode(<?php echo $attribute_row; ?>);"/>
										<label for="allow_multiple<?php echo $attribute_row; ?>"><?php echo $text_allow_multiple; ?></label>										
									<?php } ?>
									<select <?php if ($attribute['allow_multiple']) { ?>multiple style="height:200px"<?php } ?> alt="<?php echo $attribute_row; ?>" name="attributes[<?php echo $attribute_row; ?>][preset_id][]" id="input-preset<?php echo $attribute_row; ?>" class="form-control" onchange="select_preset(this);" onfocus="check_attribute(this);">								
									<option value="-1"></option>								
									<?php $current_att=0;foreach ($all_presets as $preset) { ?>									
										<?php if ($current_att != $preset['attribute_id']) { $current_att = $preset['attribute_id'];?>
										<option color="red" class="att<?php echo $attribute_row; ?> att<?php echo $attribute_row; ?>-<?php echo $preset['attribute_id']; ?>" value="0" disabled="disabled" style="color:red"><?php echo $preset['attribute_name']; ?></option>
										<?php } ?>									
										<?php if (in_array($preset['preset_id'],$attribute['preset_id'])) { ?>
										<option alt="<?php echo $preset['attribute_id']; ?>" class="pre<?php echo $attribute_row; ?> pre<?php echo $attribute_row; ?>-<?php echo $preset['attribute_id']; ?>" value="<?php echo $preset['preset_id']; ?>" selected="selected"><?php echo htmlentities(html_entity_decode($preset['text'],ENT_QUOTES, 'UTF-8'),ENT_QUOTES); ?></option>
										<?php } else { ?>
										<option alt="<?php echo $preset['attribute_id']; ?>" class="pre<?php echo $attribute_row; ?> pre<?php echo $attribute_row; ?>-<?php echo $preset['attribute_id']; ?>" value="<?php echo $preset['preset_id']; ?>"><?php echo htmlentities(html_entity_decode($preset['text'],ENT_QUOTES, 'UTF-8'),ENT_QUOTES); ?></option>
										<?php } ?>
									<?php } ?>
								  </select>
								<script>

					
									$('.att<?php echo $attribute_row; ?>').hide();
									$('.pre<?php echo $attribute_row; ?>').hide();
									$('.pre<?php echo $attribute_row; ?>-<?php echo $attribute['attribute_id']; ?>').show();
								</script>
								</div>
							<?php } ?>							
						<?php } ?>	
					  </td>
						<td class="text-left">
							<button type="button" onclick="$('#attribute-row<?php echo $attribute_row; ?>').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button><br>
						</td>					  
                    </tr>
                    <?php $attribute_row++; ?>
                    <?php } ?>
                  </tbody>
                  <tfoot>
                    <tr>
                      <td colspan="2"></td>
                      <td class="text-left"><button type="button" onclick="addAttribute();" data-toggle="tooltip" title="<?php echo $button_attribute_add; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
                    </tr>
                  </tfoot>
                </table>
              </div>			  
          </div>
        </form>
      </div>
    </div>
  </div>
  <script type="text/javascript"><!--
var attribute_row = <?php echo $attribute_row; ?>;

function addAttribute() {
    html  = '<tr id="attribute-row' + attribute_row + '">';
	html += '  <td class="text-left" style="width: 20%;"><input type="text" name="attributes[' + attribute_row + '][name]" value="" placeholder="<?php echo $entry_attribute; ?>" class="form-control" /><input type="hidden" name="attributes[' + attribute_row + '][attribute_id]" value="" /></td>';
	html += '  <td class="text-left"><button type="button" onclick="$(\'#attribute-row' + attribute_row + '\').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';

    html += '</tr>';

	$('#attribute tbody').append(html);

	addPresetField(attribute_row);

	attributeautocomplete(attribute_row);

	attribute_row++;
}

function addPresetField(attribute_row) {

	<?php if ($atpresets_selecttype==0) { ?>
		html  = '<td><input type="text" name="attributes[' + attribute_row + '][preset]" value="" placeholder="<?php echo $text_preset_value; ?>" class="form-control" />';
		html += '<input type="hidden" name="attributes[' + attribute_row + '][preset_id][]" value="" /></td>';
	<?php } else { ?>
		html = '<td>';
		<?php if ($atpresets_allow_multiple==1) { ?>
		
		html += '	<input type="checkbox" id="allow_multiple' + attribute_row + '" name="attributes[' + attribute_row + '][allow_multiple]"';
		html += '		onchange="changeSelectionMode('+attribute_row+')"/>';
		html += '	<label for="allow_multiple' + attribute_row + '"><?php echo $text_allow_multiple; ?></label>'; 
			
		<?php } ?>	
		html += '<br><select alt="' + attribute_row + '" name="attributes[' + attribute_row + '][preset_id][]" id="input-preset' + attribute_row + '" class="form-control" onchange="select_preset(this);"  onfocus="check_attribute(this);">';
		html += '<option value="-1"></option>';
			<?php $current_att=0;foreach ($all_presets as $preset) { ?>
				
				<?php if ($current_att != $preset['attribute_id']) { $current_att = $preset['attribute_id'];?>
				html += '<option class="att' + attribute_row + ' att' + attribute_row + '-<?php echo $preset['attribute_id']; ?>" value="0" disabled="disabled" style="color:red"><?php echo htmlentities($preset['attribute_name'],ENT_QUOTES); ?></option>';
				<?php } ?>
				html += '<option alt="<?php echo $preset['attribute_id']; ?>" class="pre' + attribute_row + ' pre' + attribute_row + '-<?php echo $preset['attribute_id']; ?>" value="<?php echo $preset['preset_id']; ?>"><?php echo htmlentities(html_entity_decode(str_replace(array("\r\n", "\n", "\r"), '', $preset['text']),ENT_QUOTES, 'UTF-8'),ENT_QUOTES); ?></option>';
			<?php } ?>
		html  += '  </select></td>';
								
	<?php } ?>	
	$('input[name=\'attributes[' + attribute_row + '][name]\']').parent().after(html);

	<?php if ($atpresets_allow_multiple==1) { ?>	
		addMultiSelectFunctionality(attribute_row);
	<?php } ?>

	<?php if ($atpresets_selecttype==0) { ?>
		presetautocomplete(attribute_row);
	<?php } ?>
}

<?php if ($atpresets_selecttype==0) { ?>
function presetautocomplete(attribute_row) {

	var attribute_id = $('input[name=\'attributes[' + attribute_row + '][attribute_id]\']').val();
	$('#attribute-row'+attribute_row+' .test ul').remove();
	if (attribute_id != '') {
		$('input[name=\'attributes[' + attribute_row + '][preset]\']').autocomplete({
			'source': function(request, response) {
				$.ajax({
					url: 'index.php?route=extension/module/atpresets/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request) + '&attribute_id=' + attribute_id,
					dataType: 'json',			
					success: function(json) {
						response($.map(json, function(item) {
							return {
								label: item.not_decoded_text,
								label_decoded: item.text,								
								values: item.texts,
								value: item.preset_id	
							}
						}));
					}
				});
			},
			'select': function(item) {
				$('input[name=\'attributes[' + attribute_row + '][preset]\']').val(item['label_decoded']);
				$('input[name=\'attributes[' + attribute_row + '][preset_id][]\']').val(item['value']);
			}
		});
	
	} else {
		$('input[name=\'attributes[' + attribute_row + '][preset]\']').autocomplete({
			'source': function(request, response) {
				$.ajax({
					url: 'index.php?route=extension/module/atpresets/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request) + '&attribute_id=' + attribute_id,
					dataType: 'json',			
					success: function(json) {
						response($.map(json, function(item) {
							return {
								category: item.attribute_name,
								attribute_id: item.attribute_id,
								label: item.not_decoded_text,
								label_decoded: item.text,
								values: item.texts,
								value: item.preset_id					
							}
						}));
					}
				});
			},
			select: function(item) {
				
				$('input[name=\'attributes[' + attribute_row + '][preset]\']').val(item['label_decoded']);
				$('input[name=\'attributes[' + attribute_row + '][preset_id][]\']').val(item['value']);
				$('input[name=\'attributes[' + attribute_row + '][name]\']').val(item['category']);
				$('input[name=\'attributes[' + attribute_row + '][attribute_id]\']').val(item['attribute_id']);				

				presetautocomplete(attribute_row);
			}
		});	
	}
}

$('#attribute tbody tr').each(function(index, element) {
	presetautocomplete(index);
});
<?php } else { ?>
function select_preset(select_node) {
	var att_row = $(select_node).attr('alt');
	var preset_id = $(select_node).find(":selected").val();
	var attribute_id = $(select_node).find(":selected").attr('alt');
	
	$.ajax({
		url: 'index.php?route=extension/module/atpresets/getPresetTexts&token=<?php echo $token; ?>&preset_id=' + preset_id,
		dataType: 'json',			
		success: function(json) {
			$('input[name=\'attributes[' + att_row + '][name]\']').val($('.att' + att_row + '-' + attribute_id).text());
			$('input[name=\'attributes[' + att_row + '][attribute_id]\']').val(attribute_id);	
				
			if (preset_id != -1) {
				$('.att' + att_row).hide();
				$('.pre' + att_row).hide();
				$('.pre' + att_row + '-' + attribute_id).show();			
			} else {
				$('.att' + att_row).show();
				$('.pre' + att_row).show();			
			}
				
		}
	});	

}

function check_attribute(select_node) {
	var att_row = $(select_node).attr('alt');
	var att_text = $('input[name=\'attributes[' + att_row + '][name]\']').val();
	
	if (att_text=='') {
		$('.att' + att_row).show();
		$('.pre' + att_row).show();	
		$('input[name=\'attributes[' + att_row + '][attribute_id]\']').val('');
		$('select[name=\'attributes[' + att_row + '][preset_id][]\']').val(-1);
	}
}

function changeSelectionMode(attribute_row) {
	if (!$('#allow_multiple' + attribute_row ).is(':checked')) {
		$('#input-preset' + attribute_row).css('height','auto');
		$('#input-preset' + attribute_row).removeAttr('multiple'); 
		$('#attribute-row'+attribute_row+' textarea').attr('readonly', false);		
		
		var ids = '';
		$('#input-preset' + attribute_row + ' option').each(function(index) {
			if ($(this).prop('selected')) {
				ids += '_'+$(this).val();		
			}
			if ($(this).val() == -1) {
				$(this).prop('selected', false);		
			}			
		});		
	} else  {
		$('#input-preset' + attribute_row).attr('multiple','multiple');
		$('#input-preset' + attribute_row).css('height','200px');
		$('#attribute-row'+attribute_row+' textarea').attr('readonly', true);		
	}
}

function addMultiSelectFunctionality(attribute_row) {
$('#input-preset' + attribute_row + ' option').unbind( "mousedown");
$('#input-preset' + attribute_row + ' option').mousedown(function(e) {
if ($('input[name="attributes[' + attribute_row + '][allow_multiple]"]').is(':checked')) {
    e.preventDefault();
	if ($(this).val() != -1) {
		$(this).prop('selected', !$(this).prop('selected'));

		if ($(this).prop('selected')) {
			var attribute_id = $(this).attr('alt');
			if ($('input[name=\'attributes[' + attribute_row + '][attribute_id]\']').val() != attribute_id) {
				$('input[name=\'attributes[' + attribute_row + '][name]\']').val($('.att' + attribute_row + '-' + attribute_id).text());
				$('input[name=\'attributes[' + attribute_row + '][attribute_id]\']').val(attribute_id);	
				$('.att' + attribute_row).hide();
				$('.pre' + attribute_row).hide();
				$('.pre' + attribute_row + '-' + attribute_id).show();					
			}
		}
		
		var ids = '';
		$('#input-preset' + attribute_row + ' option').each(function(index) {
			if ($(this).prop('selected')) {
				ids += '_'+$(this).val();		
			}
		});
			
	} else {
		$(this).prop('selected', false);
	}
}
	return false;
});	
}

<?php for ($i=0;$i<$attribute_row;$i++) { ?>
addMultiSelectFunctionality(<?php echo $i; ?>);
if ($('#allow_multiple<?php echo $i; ?>').attr("checked"))
	$('#attribute-row<?php echo $i; ?> textarea').attr('readonly', true);
<?php } ?>
<?php } ?>
			
function attributeautocomplete(attribute_row) {
	$('input[name=\'attributes[' + attribute_row + '][name]\']').autocomplete({
		'source': function(request, response) {
			$.ajax({
				url: 'index.php?route=catalog/attribute/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
				dataType: 'json',
				success: function(json) {

				<?php if ($atpresets_installed==1) { ?>
					<?php if ($atpresets_selecttype==0) { ?>
						if (encodeURIComponent(request)=='') {
							$('input[name=\'attributes[' + attribute_row + '][attribute_id]\']').val('');
							$('input[name=\'attributes[' + attribute_row + '][preset]\']').unbind("blur");
							$('input[name=\'attributes[' + attribute_row + '][preset]\']').unbind("focus");
							$('input[name=\'attributes[' + attribute_row + '][preset]\']').unbind("keydown");					
							$('input[name=\'attributes[' + attribute_row + '][preset]\']').val('');
							$('input[name=\'attributes[' + attribute_row + '][preset_id][]\']').val(0);			
							presetautocomplete(attribute_row);							
						}
					<?php } else {?>
						if (encodeURIComponent(request)=='') {
							$('.att' + attribute_row).show();
							$('.pre' + attribute_row).show();
							$('select[name=\'attributes[' + attribute_row + '][preset_id][]\']').val(-1);							
						}						
					<?php } ?>
				<?php } ?>
			
					response($.map(json, function(item) {
						return {
							category: item.attribute_group,
							label: item.name,
							value: item.attribute_id
						}
					}));
				}
			});
		},
		'select': function(item) {
			$('input[name=\'attributes[' + attribute_row + '][name]\']').val(item['label']);
						
			if (item['value'] != $('input[name=\'attributes[' + attribute_row + '][attribute_id]\']').val()) {				
				$('input[name=\'attributes[' + attribute_row + '][attribute_id]\']').val(item['value']);			
				<?php if ($atpresets_installed==1) { ?>
					<?php if ($atpresets_selecttype==0) { ?>
						$('input[name=\'attributes[' + attribute_row + '][preset]\']').unbind("blur");
						$('input[name=\'attributes[' + attribute_row + '][preset]\']').unbind("focus");
						$('input[name=\'attributes[' + attribute_row + '][preset]\']').unbind("keydown");					
						$('input[name=\'attributes[' + attribute_row + '][preset]\']').val('');
						$('input[name=\'attributes[' + attribute_row + '][preset_id]\']').val(0);			
						presetautocomplete(attribute_row);
					<?php } else {?>
						$('.att' + attribute_row).hide();
						$('.pre' + attribute_row).hide();
						$('.pre' + attribute_row + '-' + item['value']).show();	
						$('select[name=\'attributes[' + attribute_row + '][preset_id][]\']').val(-1);						
					<?php } ?>
				<?php } ?>
			}
			
		}
	});
}

$('#attribute tbody tr').each(function(index, element) {
	attributeautocomplete(index);
});
//--></script>  
<?php echo $footer; ?>