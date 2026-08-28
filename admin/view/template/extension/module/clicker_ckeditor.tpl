<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
	<div class="page-header">
		<div class="container-fluid">
			<div class="pull-right">
				<button type="submit" form="form-module" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
			</div>
			<div class="panel-body">
				<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-module" class="form-horizontal">
					<ul class="nav nav-tabs">
						<li class="nav-item active"><a href="#tab-main" data-toggle="tab" class="nav-link active"><?php echo $tab_main; ?></a></li>
						<li class="nav-item"><a href="#tab-codemirror" data-toggle="tab" class="nav-link"><?php echo $tab_codemirror; ?></a></li>
						<li class="nav-item"><a href="#tab-advanced" data-toggle="tab" class="nav-link"><?php echo $tab_advanced; ?></a></li>
						<li class="nav-item"><a href="#tab-about" data-toggle="tab" class="nav-link"><?php echo $tab_about; ?></a></li>
					</ul>
					<div class="tab-content">
						<div class="tab-pane active" id="tab-main">
							<div class="form-group">
								<label class="col-md-3 control-label" for="input-status"><?php echo $entry_status; ?></label>
								<div class="col-md-3">
									<div class="btn-group btn-group-toggle" data-toggle="buttons">
										<?php if ($module_clicker_ckeditor_status == 1) { ?>
										<label class="btn btn-default btn-success active"><input type="radio" name="module_clicker_ckeditor_status" value="1" checked="checked"/> CKEditor</label>
										<label class="btn btn-default btn-warning"><input type="radio" name="module_clicker_ckeditor_status" value="0"/> Summernote</label>
										<?php } else { ?>
										<label class="btn btn-default btn-success"><input type="radio" name="module_clicker_ckeditor_status" value="1"/> CKEditor</label>
										<label class="btn btn-default btn-warning active"><input type="radio" name="module_clicker_ckeditor_status" value="0" checked="checked"/> Summernote</label>
										<?php } ?>
									</div>
								</div>
								<label class="col-md-3 control-label" for="input-toolbar"><?php echo $entry_toolbar; ?></label>
								<div class="col-md-3">
									<div class="btn-group btn-group-toggle" data-toggle="buttons">
										<?php if ($settings['toolbar'] == 'full') { ?>
										<label class="btn btn-default btn-success active"><input type="radio" name="module_clicker_ckeditor_settings[toolbar]" value="full" checked="checked"/> <?php echo $text_full; ?></label>
										<label class="btn btn-default btn-danger"><input type="radio" name="module_clicker_ckeditor_settings[toolbar]" value="basic"/> <?php echo $text_basic; ?></label>
										<?php } else { ?>
										<label class="btn btn-default btn-success"><input type="radio" name="module_clicker_ckeditor_settings[toolbar]" value="full"/> <?php echo $text_full; ?></label>
										<label class="btn btn-default btn-danger active"><input type="radio" name="module_clicker_ckeditor_settings[toolbar]" value="basic" checked="checked"/> <?php echo $text_basic; ?></label>
										<?php } ?>
									</div>
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-3 control-label" for="input-height"><?php echo $entry_height; ?></label>
								<div class="col-md-9">
									<input type="number" min="100" name="module_clicker_ckeditor_settings[height]" id="input-height" class="form-control" value="<?php echo !empty($settings['height']) ? $settings['height'] : 300; ?>">
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-3 control-label" for="input-resize_enabled"><span data-toggle="tooltip" title="<?php echo $help_resize; ?>"><?php echo $entry_resize; ?></span></label>
								<div class="col-md-3">
									<div class="btn-group btn-group-toggle" data-toggle="buttons">
										<?php if ($settings['resize_enabled'] == 'true') { ?>
										<label class="btn btn-default btn-success active"><input type="radio" name="module_clicker_ckeditor_settings[resize_enabled]" value="true" checked="checked"/> <?php echo $text_on; ?></label>
										<label class="btn btn-default btn-danger"><input type="radio" name="module_clicker_ckeditor_settings[resize_enabled]" value="false"/> <?php echo $text_off; ?></label>
										<?php } else { ?>
										<label class="btn btn-default btn-success"><input type="radio" name="module_clicker_ckeditor_settings[resize_enabled]" value="true"/> <?php echo $text_on; ?></label>
										<label class="btn btn-default btn-danger active"><input type="radio" name="module_clicker_ckeditor_settings[resize_enabled]" value="false" checked="checked"/> <?php echo $text_off; ?></label>
										<?php } ?>
									</div>
								</div>
								<label class="col-md-3 control-label" for="input-resize_dir"><?php echo $entry_resize_dir; ?></label>
								<div class="col-md-3">
									<select name="module_clicker_ckeditor_settings[resize_dir]" id="input-resize_dir" class="form-control">
										<option value="vertical" <?php if ($settings['resize_dir'] == 'vertical') { ?>selected="selected"<?php } ?>><?php echo $text_vertical; ?></option>
										<option value="horizontal" <?php if ($settings['resize_dir'] == 'horizontal') { ?>selected="selected"<?php } ?>><?php echo $text_horizontal; ?></option>
										<option value="both" <?php if ($settings['resize_dir'] == 'both') { ?>selected="selected"<?php } ?>><?php echo $text_both; ?></option>
									</select>
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-3 control-label" for="input-enterMode"><span data-toggle="tooltip" title="<?php echo $help_enter_mode; ?>"><?php echo $entry_enter_mode; ?></span></label>
								<div class="col-md-3">
									<select name="module_clicker_ckeditor_settings[enterMode]" id="input-enterMode" class="form-control">
										<option value="1" <?php if ($settings['enterMode'] == 1) { ?>selected="selected"<?php } ?>>&#x3C;p&#x3E; <?php echo $text_paragraph; ?></option>
										<option value="2" <?php if ($settings['enterMode'] == 2) { ?>selected="selected"<?php } ?>>&#x3C;br&#x3E; <?php echo $text_br; ?></option>
										<option value="3" <?php if ($settings['enterMode'] == 3) { ?>selected="selected"<?php } ?>>&#x3C;div&#x3E; <?php echo $text_div; ?></option>
									</select>
								</div>
								<label class="col-md-3 control-label" for="input-shiftEnterMode"><span data-toggle="tooltip" title="<?php echo $help_senter_mode; ?>"><?php echo $entry_senter_mode; ?></span></label>
								<div class="col-md-3">
									<select name="module_clicker_ckeditor_settings[shiftEnterMode]" id="input-shiftEnterMode" class="form-control">
										<option value="1" <?php if ($settings['shiftEnterMode'] == 1) { ?>selected="selected"<?php } ?>>&#x3C;p&#x3E; <?php echo $text_paragraph; ?></option>
										<option value="2" <?php if ($settings['shiftEnterMode'] == 2) { ?>selected="selected"<?php } ?>>&#x3C;br&#x3E; <?php echo $text_br; ?></option>
										<option value="3" <?php if ($settings['shiftEnterMode'] == 3) { ?>selected="selected"<?php } ?>>&#x3C;div&#x3E; <?php echo $text_div; ?></option>
									</select>
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-3 control-label" for="input-ck_language"><?php echo $entry_ck_language; ?></label>
								<div class="col-md-9">
									<select name="module_clicker_ckeditor_settings[language]" id="input-ck_language" class="form-control">
										<?php if (empty($settings['codemirror']['theme'])) { ?>
										<option value="" selected="selected"><?php echo $text_auto; ?></option>
										<?php } else { ?>
										<option value=""><?php echo $text_auto; ?></option>
										<?php } ?>
										<?php foreach ($ck_languages as $ck_language) { ?>
										<?php if ($settings['language'] == $ck_language['id']) { ?>
										<option value="<?php echo $ck_language['id']; ?>" selected="selected"><?php echo $ck_language['title']; ?></option>
										<?php } else { ?>
										<option value="<?php echo $ck_language['id']; ?>"><?php echo $ck_language['title']; ?></option>
										<?php } ?>
										<?php } ?>
									</select>
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-3 control-label" for="input-ck_skin"><?php echo $entry_ck_skin; ?></label>
								<div class="col-md-9">
									<select name="module_clicker_ckeditor_settings[skin]" id="input-ck_skin" class="form-control" onchange="CKSkinPreview();">
										<?php foreach ($ck_skins as $ck_skin) { ?>
										<?php if ($settings['skin'] == $ck_skin['id']) { ?>
										<option value="<?php echo $ck_skin['id']; ?>" selected="selected"><?php echo $ck_skin['title']; ?></option>
										<?php } else { ?>
										<option value="<?php echo $ck_skin['id']; ?>"><?php echo $ck_skin['title']; ?></option>
										<?php } ?>
										<?php } ?>
									</select>
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-3 control-label" for="input-ck_skin"><?php echo $entry_ck_skin_preview; ?></label>
								<div class="col-md-9">
									<img id="ck_skin_preview" class="img-fluid" src="" alt="<?php echo $entry_ck_skin_preview; ?>" style="max-width: 100%;">
								</div>
							</div>

							<legend><?php echo $text_autogrow; ?></legend>

							<div class="form-group">
								<label class="col-md-3 control-label" for="input-autoGrow_status"><span data-toggle="tooltip" title="<?php echo $help_autogrow; ?>"><?php echo $text_autogrow; ?></span></label>
								<div class="col-md-9">
									<div class="btn-group btn-group-toggle" data-toggle="buttons">
										<?php if ($settings['autoGrow_status'] == 'true') { ?>
										<label class="btn btn-default btn-success active"><input type="radio" name="module_clicker_ckeditor_settings[autoGrow_status]" value="true" checked="checked"/> <?php echo $text_on; ?></label>
										<label class="btn btn-default btn-danger"><input type="radio" name="module_clicker_ckeditor_settings[autoGrow_status]" value="false"/> <?php echo $text_off; ?></label>
										<?php } else { ?>
										<label class="btn btn-default btn-success"><input type="radio" name="module_clicker_ckeditor_settings[autoGrow_status]" value="true"/> <?php echo $text_on; ?></label>
										<label class="btn btn-default btn-danger active"><input type="radio" name="module_clicker_ckeditor_settings[autoGrow_status]" value="false" checked="checked"/> <?php echo $text_off; ?></label>
										<?php } ?>
									</div>
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-3 control-label" for="input-autoGrow_minHeight"><?php echo $entry_min_height; ?></label>
								<div class="col-md-3">
									<input type="number" min="0" name="module_clicker_ckeditor_settings[autoGrow_minHeight]" id="input-autoGrow_minHeight" class="form-control" value="<?php echo !empty($settings['autoGrow_minHeight']) ? $settings['autoGrow_minHeight'] : 300; ?>">
								</div>
								<label class="col-md-3 control-label" for="input-autoGrow_maxHeight"><?php echo $entry_max_height; ?></label>
								<div class="col-md-3">
									<input type="number" min="0" name="module_clicker_ckeditor_settings[autoGrow_maxHeight]" id="input-autoGrow_maxHeight" class="form-control" value="<?php echo !empty($settings['autoGrow_maxHeight']) ? $settings['autoGrow_maxHeight'] : ''; ?>">
								</div>
							</div>

							<legend><?php echo $text_other; ?></legend>

							<div class="form-group">
								<label class="col-md-3 control-label" for="input-startupOutlineBlocks"><span data-toggle="tooltip" title="<?php echo $help_outline_blocks; ?>"><?php echo $entry_outline_blocks; ?></span></label>
								<div class="col-md-3">
									<div class="btn-group btn-group-toggle" data-toggle="buttons">
										<?php if ($settings['startupOutlineBlocks'] == 'true') { ?>
										<label class="btn btn-default btn-success active"><input type="radio" name="module_clicker_ckeditor_settings[startupOutlineBlocks]" value="true" checked="checked"/> <?php echo $text_on; ?></label>
										<label class="btn btn-default btn-danger"><input type="radio" name="module_clicker_ckeditor_settings[startupOutlineBlocks]" value="false"/> <?php echo $text_off; ?></label>
										<?php } else { ?>
										<label class="btn btn-default btn-success"><input type="radio" name="module_clicker_ckeditor_settings[startupOutlineBlocks]" value="true"/> <?php echo $text_on; ?></label>
										<label class="btn btn-default btn-danger active"><input type="radio" name="module_clicker_ckeditor_settings[startupOutlineBlocks]" value="false" checked="checked"/> <?php echo $text_off; ?></label>
										<?php } ?>
									</div>
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-3 control-label" for="input-leaflet_maps_google_api_key"><?php echo $entry_maps_google_api_key; ?></label>
								<div class="col-md-6">
									<input type="text" name="module_clicker_ckeditor_settings[leaflet_maps_google_api_key]" id="input-leaflet_maps_google_api_key" class="form-control" value="<?php echo $settings['leaflet_maps_google_api_key'] ? $settings['leaflet_maps_google_api_key'] : ''; ?>">
								</div>
								<div class="col-md-3">
									<a class="btn btn-info" href="https://developers.google.com/maps/documentation/javascript/get-api-key" target="_blank"><?php echo $text_documentation; ?></a>
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-3 control-label" for="input-forcePastePopup"><span data-toggle="tooltip" title="<?php echo $help_force_paste_popup; ?>"><?php echo $entry_force_paste_popup; ?></span></label>
								<div class="col-md-3">
									<div class="btn-group btn-group-toggle" data-toggle="buttons">
										<?php if ($settings['forcePastePopup'] == 'true') { ?>
										<label class="btn btn-default btn-success active"><input type="radio" name="module_clicker_ckeditor_settings[forcePastePopup]" value="true" checked="checked"/> <?php echo $text_on; ?></label>
										<label class="btn btn-default btn-danger"><input type="radio" name="module_clicker_ckeditor_settings[forcePastePopup]" value="false"/> <?php echo $text_off; ?></label>
										<?php } else { ?>
										<label class="btn btn-default btn-success"><input type="radio" name="module_clicker_ckeditor_settings[forcePastePopup]" value="true"/> <?php echo $text_on; ?></label>
										<label class="btn btn-default btn-danger active"><input type="radio" name="module_clicker_ckeditor_settings[forcePastePopup]" value="false" checked="checked"/> <?php echo $text_off; ?></label>
										<?php } ?>
									</div>
								</div>
								<label class="col-md-3 control-label" for="input-forceDomainRemove"><span data-toggle="tooltip" title="<?php echo $help_force_domain_remove; ?>"><?php echo $entry_force_domain_remove; ?></span></label>
								<div class="col-md-3">
									<div class="btn-group btn-group-toggle" data-toggle="buttons">
										<?php if ($settings['forceDomainRemove'] == 'true') { ?>
										<label class="btn btn-default btn-success active"><input type="radio" name="module_clicker_ckeditor_settings[forceDomainRemove]" value="true" checked="checked"/> <?php echo $text_on; ?></label>
										<label class="btn btn-default btn-danger"><input type="radio" name="module_clicker_ckeditor_settings[forceDomainRemove]" value="false"/> <?php echo $text_off; ?></label>
										<?php } else { ?>
										<label class="btn btn-default btn-success"><input type="radio" name="module_clicker_ckeditor_settings[forceDomainRemove]" value="true"/> <?php echo $text_on; ?></label>
										<label class="btn btn-default btn-danger active"><input type="radio" name="module_clicker_ckeditor_settings[forceDomainRemove]" value="false" checked="checked"/> <?php echo $text_off; ?></label>
										<?php } ?>
									</div>
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-3 control-label" for="input-disableNativeSpellChecker"><span data-toggle="tooltip" title="<?php echo $help_native_spell_checker; ?>"><?php echo $entry_native_spell_checker; ?></span></label>
								<div class="col-md-3">
									<div class="btn-group btn-group-toggle" data-toggle="buttons">
										<?php if ($settings['disableNativeSpellChecker'] == 'false') { ?>
										<label class="btn btn-default btn-success active"><input type="radio" name="module_clicker_ckeditor_settings[disableNativeSpellChecker]" value="false" checked="checked"/> <?php echo $text_on; ?></label>
										<label class="btn btn-default btn-danger"><input type="radio" name="module_clicker_ckeditor_settings[disableNativeSpellChecker]" value="true"/> <?php echo $text_off; ?></label>
										<?php } else { ?>
										<label class="btn btn-default btn-success"><input type="radio" name="module_clicker_ckeditor_settings[disableNativeSpellChecker]" value="false"/> <?php echo $text_on; ?></label>
										<label class="btn btn-default btn-danger active"><input type="radio" name="module_clicker_ckeditor_settings[disableNativeSpellChecker]" value="true" checked="checked"/> <?php echo $text_off; ?></label>
										<?php } ?>
									</div>
								</div>
								<label class="col-md-3 control-label" for="input-browserContextMenuOnCtrl"><span data-toggle="tooltip" title="<?php echo $help_browser_menu_on_ctrl; ?>"><?php echo $entry_browser_menu_on_ctrl; ?></span></label>
								<div class="col-md-3">
									<div class="btn-group btn-group-toggle" data-toggle="buttons">
										<?php if ($settings['browserContextMenuOnCtrl'] == 'true') { ?>
										<label class="btn btn-default btn-success active"><input type="radio" name="module_clicker_ckeditor_settings[browserContextMenuOnCtrl]" value="true" checked="checked"/> <?php echo $text_on; ?></label>
										<label class="btn btn-default btn-danger"><input type="radio" name="module_clicker_ckeditor_settings[browserContextMenuOnCtrl]" value="false"/> <?php echo $text_off; ?></label>
										<?php } else { ?>
										<label class="btn btn-default btn-success"><input type="radio" name="module_clicker_ckeditor_settings[browserContextMenuOnCtrl]" value="true"/> <?php echo $text_on; ?></label>
										<label class="btn btn-default btn-danger active"><input type="radio" name="module_clicker_ckeditor_settings[browserContextMenuOnCtrl]" value="false" checked="checked"/> <?php echo $text_off; ?></label>
										<?php } ?>
									</div>
								</div>
							</div>

						</div>

						<div class="tab-pane" id="tab-codemirror">
							<div class="form-group">
								<label class="col-md-3 control-label" for="input-cm_theme"><?php echo $entry_cm_theme; ?></label>
								<div class="col-md-9">
									<select name="module_clicker_ckeditor_settings[codemirror][theme]" id="input-cm_theme" class="form-control">
										<?php if ($settings['codemirror']['theme'] == 'default') { ?>
										<option value="default" selected="selected"><?php echo $text_default; ?></option>
										<?php } else { ?>
										<option value="default"><?php echo $text_default; ?></option>
										<?php } ?>
										<?php foreach ($cm_themes as $cm_theme) { ?>
										<?php if ($settings['codemirror']['theme'] == $cm_theme['id']) { ?>
										<option value="<?php echo $cm_theme['id']; ?>" selected="selected"><?php echo $cm_theme['title']; ?></option>
										<?php } else { ?>
										<option value="<?php echo $cm_theme['id']; ?>"><?php echo $cm_theme['title']; ?></option>
										<?php } ?>
										<?php } ?>
									</select>
								</div>
							</div>
						</div>

						<div class="tab-pane" id="tab-advanced">
							<div class="alert alert-warning alert-dismissible"><i class="fa fa-exclamation-circle"></i> <?php echo $help_reset_settings; ?>
								<button type="button" class="close" data-dismiss="alert">&times;</button>
							</div>

							<legend><?php echo $text_fonts; ?></legend>

							<div class="form-group">
								<label class="col-md-3 control-label" for="input-font_names"><span data-toggle="tooltip" title="<?php echo $help_font_names; ?>"><?php echo $entry_font_names; ?></span></label>
								<div class="col-md-9">
									<textarea rows="10" name="module_clicker_ckeditor_settings[font_names]" id="input-font_names" class="form-control"><?php echo $settings['font_names'] ? $settings['font_names'] : ''; ?></textarea>
								</div>
							</div>

							<legend><?php echo $text_content; ?></legend>

							<div class="form-group">
								<label class="col-md-3 control-label" for="input-autoParagraph"><span data-toggle="tooltip" title="<?php echo $help_auto_paragraph; ?>"><?php echo $entry_auto_paragraph; ?></span></label>
								<div class="col-md-3">
									<div class="btn-group btn-group-toggle" data-toggle="buttons">
										<?php if ($settings['autoParagraph'] == 'true') { ?>
										<label class="btn btn-default btn-success active"><input type="radio" name="module_clicker_ckeditor_settings[autoParagraph]" value="true" checked="checked"/> <?php echo $text_on; ?></label>
										<label class="btn btn-default btn-danger"><input type="radio" name="module_clicker_ckeditor_settings[autoParagraph]" value="false"/> <?php echo $text_off; ?></label>
										<?php } else { ?>
										<label class="btn btn-default btn-success"><input type="radio" name="module_clicker_ckeditor_settings[autoParagraph]" value="true"/> <?php echo $text_on; ?></label>
										<label class="btn btn-default btn-danger active"><input type="radio" name="module_clicker_ckeditor_settings[autoParagraph]" value="false" checked="checked"/> <?php echo $text_off; ?></label>
										<?php } ?>
									</div>
								</div>
								<label class="col-md-3 control-label" for="input-fillEmptyBlocks"><span data-toggle="tooltip" title="<?php echo $help_fill_empty_blocks; ?>"><?php echo $entry_fill_empty_blocks; ?></span></label>
								<div class="col-md-3">
									<div class="btn-group btn-group-toggle" data-toggle="buttons">
										<?php if ($settings['fillEmptyBlocks'] == 'true') { ?>
										<label class="btn btn-default btn-success active"><input type="radio" name="module_clicker_ckeditor_settings[fillEmptyBlocks]" value="true" checked="checked"/> <?php echo $text_on; ?></label>
										<label class="btn btn-default btn-danger"><input type="radio" name="module_clicker_ckeditor_settings[fillEmptyBlocks]" value="false"/> <?php echo $text_off; ?></label>
										<?php } else { ?>
										<label class="btn btn-default btn-success"><input type="radio" name="module_clicker_ckeditor_settings[fillEmptyBlocks]" value="true"/> <?php echo $text_on; ?></label>
										<label class="btn btn-default btn-danger active"><input type="radio" name="module_clicker_ckeditor_settings[fillEmptyBlocks]" value="false" checked="checked"/> <?php echo $text_off; ?></label>
										<?php } ?>
									</div>
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-3 control-label" for="input-allowedContent"><span data-toggle="tooltip" title="<?php echo $help_allowed_content; ?>"><?php echo $entry_allowed_content; ?></span></label>
								<div class="col-md-3">
									<div class="btn-group btn-group-toggle" data-toggle="buttons">
										<?php if ($settings['allowedContent'] == 'true') { ?>
										<label class="btn btn-default btn-success active"><input type="radio" name="module_clicker_ckeditor_settings[allowedContent]" value="true" checked="checked"/> <?php echo $text_on; ?></label>
										<label class="btn btn-default btn-danger"><input type="radio" name="module_clicker_ckeditor_settings[allowedContent]" value="false"/> <?php echo $text_off; ?></label>
										<?php } else { ?>
										<label class="btn btn-default btn-success"><input type="radio" name="module_clicker_ckeditor_settings[allowedContent]" value="true"/> <?php echo $text_on; ?></label>
										<label class="btn btn-default btn-danger active"><input type="radio" name="module_clicker_ckeditor_settings[allowedContent]" value="false" checked="checked"/> <?php echo $text_off; ?></label>
										<?php } ?>
									</div>
								</div>
								<label class="col-md-3 control-label" for="input-extraAllowedContent"><span data-toggle="tooltip" title="<?php echo $help_extra_allowed_content; ?>"><?php echo $entry_extra_allowed_content; ?></span></label>
								<div class="col-md-3">
									<input type="text" name="module_clicker_ckeditor_settings[extraAllowedContent]" id="input-extraAllowedContent" class="form-control" value="<?php echo $settings['extraAllowedContent'] ? $settings['extraAllowedContent'] : '*{*}'; ?>">
								</div>
							</div>

							<legend><?php echo $text_plugins; ?></legend>

							<div class="form-group">
								<label class="col-md-3 control-label" for="input-extraPlugins"><span data-toggle="tooltip" title="<?php echo $help_extra_plugins; ?>"><?php echo $entry_extra_plugins; ?></span></label>
								<div class="col-md-9">
									<input type="text" name="module_clicker_ckeditor_settings[extraPlugins]" id="input-extraPlugins" class="form-control" value="<?php echo $settings['extraPlugins'] ? $settings['extraPlugins'] : ''; ?>">
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-3 control-label" for="input-removePlugins"><span data-toggle="tooltip" title="<?php echo $help_remove_plugins; ?>"><?php echo $entry_remove_plugins; ?></span></label>
								<div class="col-md-9">
									<input type="text" name="module_clicker_ckeditor_settings[removePlugins]" id="input-removePlugins" class="form-control" value="<?php echo $settings['removePlugins'] ? $settings['removePlugins'] : ''; ?>">
								</div>
							</div>

							<legend><?php echo $text_html_entities; ?></legend>

							<div class="form-group">
								<label class="col-md-3 control-label" for="input-entities"><span data-toggle="tooltip" title="<?php echo $help_entities; ?>"><?php echo $entry_entities; ?></span></label>
								<div class="col-md-3">
									<div class="btn-group btn-group-toggle" data-toggle="buttons">
										<?php if ($settings['entities'] == 'true') { ?>
										<label class="btn btn-default btn-success active"><input type="radio" name="module_clicker_ckeditor_settings[entities]" value="true" checked="checked"/> <?php echo $text_on; ?></label>
										<label class="btn btn-default btn-danger"><input type="radio" name="module_clicker_ckeditor_settings[entities]" value="false"/> <?php echo $text_off; ?></label>
										<?php } else { ?>
										<label class="btn btn-default btn-success"><input type="radio" name="module_clicker_ckeditor_settings[entities]" value="true"/> <?php echo $text_on; ?></label>
										<label class="btn btn-default btn-danger active"><input type="radio" name="module_clicker_ckeditor_settings[entities]" value="false" checked="checked"/> <?php echo $text_off; ?></label>
										<?php } ?>
									</div>
								</div>
								<label class="col-md-3 control-label" for="input-entities_additional"><span data-toggle="tooltip" title="<?php echo $help_entities_additional ; ?>"><?php echo $entry_entities_additional; ?></span></label>
								<div class="col-md-3">
									<input type="text" name="module_clicker_ckeditor_settings[entities_additional]" id="input-entities_additional" class="form-control" value="<?php echo $settings['entities_additional'] ? $settings['entities_additional'] : '#1049'; ?>">
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-3 control-label" for="input-entities_latin"><span data-toggle="tooltip" title="<?php echo $help_entities_latin; ?>"><?php echo $entry_entities_latin; ?></span></label>
								<div class="col-md-3">
									<div class="btn-group btn-group-toggle" data-toggle="buttons">
										<?php if ($settings['entities_latin'] == 'true') { ?>
										<label class="btn btn-default btn-success active"><input type="radio" name="module_clicker_ckeditor_settings[entities_latin]" value="true" checked="checked"/> <?php echo $text_on; ?></label>
										<label class="btn btn-default btn-danger"><input type="radio" name="module_clicker_ckeditor_settings[entities_latin]" value="false"/> <?php echo $text_off; ?></label>
										<?php } else { ?>
										<label class="btn btn-default btn-success"><input type="radio" name="module_clicker_ckeditor_settings[entities_latin]" value="true"/> <?php echo $text_on; ?></label>
										<label class="btn btn-default btn-danger active"><input type="radio" name="module_clicker_ckeditor_settings[entities_latin]" value="false" checked="checked"/> <?php echo $text_off; ?></label>
										<?php } ?>
									</div>
								</div>

								<label class="col-md-3 control-label" for="input-entities_greek"><span data-toggle="tooltip" title="<?php echo $help_entities_greek; ?>"><?php echo $entry_entities_greek; ?></span></label>
								<div class="col-md-3">
									<div class="btn-group btn-group-toggle" data-toggle="buttons">
										<?php if ($settings['entities_greek'] == 'true') { ?>
										<label class="btn btn-default btn-success active"><input type="radio" name="module_clicker_ckeditor_settings[entities_greek]" value="true" checked="checked"/> <?php echo $text_on; ?></label>
										<label class="btn btn-default btn-danger"><input type="radio" name="module_clicker_ckeditor_settings[entities_greek]" value="false"/> <?php echo $text_off; ?></label>
										<?php } else { ?>
										<label class="btn btn-default btn-success"><input type="radio" name="module_clicker_ckeditor_settings[entities_greek]" value="true"/> <?php echo $text_on; ?></label>
										<label class="btn btn-default btn-danger active"><input type="radio" name="module_clicker_ckeditor_settings[entities_greek]" value="false" checked="checked"/> <?php echo $text_off; ?></label>
										<?php } ?>
									</div>
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-3 control-label" for="input-entities_processNumerical"><span data-toggle="tooltip" title="<?php echo $help_entities_processNumerical; ?>"><?php echo $entry_entities_processNumerical; ?></span></label>
								<div class="col-md-9">
									<div class="btn-group btn-group-toggle" data-toggle="buttons">
										<?php if ($settings['entities_processNumerical'] == 'true') { ?>
										<label class="btn btn-default btn-success active"><input type="radio" name="module_clicker_ckeditor_settings[entities_processNumerical]" value="true" checked="checked"/> <?php echo $text_on; ?></label>
										<label class="btn btn-default btn-warning"><input type="radio" name="module_clicker_ckeditor_settings[entities_processNumerical]" value="force"/> <?php echo $text_force; ?></label>
										<label class="btn btn-default btn-danger"><input type="radio" name="module_clicker_ckeditor_settings[entities_processNumerical]" value="false"/> <?php echo $text_off; ?></label>
										<?php } else if ($settings['entities_processNumerical'] == 'force') { ?>
										<label class="btn btn-default btn-success"><input type="radio" name="module_clicker_ckeditor_settings[entities_processNumerical]" value="true"/> <?php echo $text_on; ?></label>
										<label class="btn btn-default btn-warning active"><input type="radio" name="module_clicker_ckeditor_settings[entities_processNumerical]" value="force" checked="checked"/> <?php echo $text_force; ?></label>
										<label class="btn btn-default btn-danger"><input type="radio" name="module_clicker_ckeditor_settings[entities_processNumerical]" value="false"/> <?php echo $text_off; ?></label>
										<?php } else { ?>
										<label class="btn btn-default btn-success"><input type="radio" name="module_clicker_ckeditor_settings[entities_processNumerical]" value="true"/> <?php echo $text_on; ?></label>
										<label class="btn btn-default btn-warning"><input type="radio" name="module_clicker_ckeditor_settings[entities_processNumerical]" value="force"/> <?php echo $text_force; ?></label>
										<label class="btn btn-default btn-danger active"><input type="radio" name="module_clicker_ckeditor_settings[entities_processNumerical]" value="false" checked="checked"/> <?php echo $text_off; ?></label>
										<?php } ?>
									</div>
								</div>
							</div>

							<legend><?php echo $text_config; ?></legend>

							<div class="form-group">
								<label class="col-md-3 control-label" for="input-custom_css"><span data-toggle="tooltip" title="<?php echo $help_custom_css; ?>"><?php echo $entry_custom_css; ?></span></label>
								<div class="col-md-9">
									<textarea rows="10" name="module_clicker_ckeditor_settings[customCss]" id="input-custom_css" class="form-control" placeholder="<?php echo $help_custom_css; ?>"><?php echo $settings['customCss'] ? $settings['customCss'] : ''; ?></textarea>
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-3 control-label" for="input-custom_config"><span data-toggle="tooltip" title="<?php echo $help_custom_config; ?>"><?php echo $entry_custom_config; ?></span></label>
								<div class="col-md-9">
									<textarea rows="10" name="module_clicker_ckeditor_settings[customConfigJson]" id="input-custom_config" class="form-control" placeholder="<?php echo $help_custom_config; ?>"><?php echo $settings['customConfigJson'] ? $settings['customConfigJson'] : ''; ?></textarea>
								</div>
							</div>

						</div>

						<div class="tab-pane" id="tab-about">
							<div class="row form-group">
								<label class="col-md-3 control-label">Product</label>
								<div class="col-md-9" style="padding-top: 9px;">
									CKEditor Full integration v.<?php echo $version; ?> by Cl!cker
								</div>
							</div>

							<div class="row form-group">
								<label class="col-md-3 control-label">Support</label>
								<div class="col-md-9">
									<a class="btn btn-primary" href="https://opencart.click/open-ticket" target="_blank">Open Ticket</a>
								</div>
							</div>

							<div class="row form-group">
								<label class="col-md-3 control-label">Email</label>
								<div class="col-md-9" style="padding-top: 9px;">
									info@clicker.com.ua
								</div>
							</div>

							<div class="row form-group">
								<label class="col-md-3 control-label">Our extensions</label>
								<div class="col-md-9" style="padding-top: 9px;">
									<a href="https://www.opencart.com/index.php?route=marketplace/extension&filter_member=Cl!cker" target="_blank">OpenCart Marketplace</a> or <a href="https://opencart.click" target="_blank">opencart.click</a>
								</div>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<style>
.btn-group-toggle > .btn {
	opacity: 0.5;
}
.btn-group-toggle > .btn.active {
	opacity: 1;
}
</style>

<script>
$(document).ready(function() {
	CKSkinPreview();
});
function CKSkinPreview() {
	$('#ck_skin_preview').attr('src', 'view/javascript/clicker_ckeditor/clicker/skins-preview/' + $('#input-ck_skin').val() + '.png');
}
</script>
<?php echo $footer; ?>