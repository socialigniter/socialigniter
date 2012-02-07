<form name="settings_update" id="settings_update" method="post" action="<?= base_url() ?>api/settings/modify" enctype="multipart/form-data">
<div class="content_wrap_inner">
	<h3>Logo</h3>
	<div id="logo_picture" class="design_image_thumb">
		<img id="logo_thumbnail" src="<?= $logo_thumb ?>" border="0">
	</div>
	<ul id="logo_picture_upload" class="design_image_uploader item_actions_list">
		<li id="logo_uploading_pick"><a id="pick_logo" href="#"><span class="actions action_upload"></span> Upload A Picture</a></li>
		<li id="logo_uploading_status" class="hide"><span class="actions action_sync"></span> Uploading: <span id="file_uploading_progress"></span><span id="file_uploading_name"></span></li>			
	<?php if ($logo_thumb): ?>
		<li id="logo_uploading_delete"><a id="delete_picture" href="#"><span class="actions action_delete"></span> Delete Picture</a></li>
		<li id="logo_uploading_details" class="small_details hide"><span class="actions_blank"></span> <?= config_item('default_images_max_size') / 1024 ?> MB max size (<?= strtoupper(str_replace('|', ', ', config_item('default_images_formats'))) ?>)</li>
	<?php else: ?>
		<li id="logo_uploading_delete" class="hide"><a id="delete_picture" href="#"><span class="actions action_delete"></span> Delete Picture</a></li>
		<li id="logo_uploading_details" class="small_details"><span class="actions_blank"></span> <?= config_item('default_images_max_size') / 1024 ?> MB max size (<?= strtoupper(str_replace('|', ', ', config_item('default_images_formats'))) ?>)</li>			
	<?php endif; ?>
	</ul>
	<div class="clear"></div>
</div>
<span class="item_separator"></span>

<div class="content_wrap_inner">
	<h3>Links</h3>
	<div class="design_color_widget">
		<p>Normal</p>
		<div id="link_color_picker_normal" class="design_color_picker">
			<div style="background-color: #<?= config_item('design_link_color_normal') ?>"></div>
		</div>
		<div class="design_color_details">
			<input type="text" maxlength="6" size="6" name="link_color_normal" id="link_color_normal" value="<?= config_item('design_link_color_normal') ?>" /><br>
			<img id="link_color_normal_swatch" src="<?= $dashboard_assets ?>icons/colors_24.png">
		</div>
		<div class="clear"></div>
	</div>
	<div class="design_color_widget">	
		<p>Visited</p>
		<div id="link_color_picker_visited" class="design_color_picker">
			<div style="background-color: #<?= config_item('design_link_color_visited') ?>"></div>
		</div>
		<div class="design_color_details">
			<input type="text" maxlength="6" size="6" name="link_color_visited" id="link_color_visited" value="<?= config_item('design_link_color_visited') ?>" /><br>
			<img id="link_color_visited_swatch" src="<?= $dashboard_assets ?>icons/colors_24.png">
		</div>
		<div class="clear"></div>
	</div>
	<div class="design_color_widget">	
		<p>Hover</p>
		<div id="link_color_picker_hover" class="design_color_picker">
			<div style="background-color: #<?= config_item('design_link_color_hover') ?>"></div>
		</div>
		<div class="design_color_details">
			<input type="text" maxlength="6" size="6" name="link_color_hover" id="link_color_hover" value="<?= config_item('design_link_color_hover') ?>" /><br>
			<img id="link_color_hover_swatch" src="<?= $dashboard_assets ?>icons/colors_24.png">	
		</div>
		<div class="clear"></div>
	</div>
	<div class="clear"></div>
</div>
<span class="item_separator"></span>

<div class="content_wrap_inner">
	<h3>Header</h3>
	<div id="header_picture" class="design_image_thumb">
		<img id="header_thumbnail" src="<?= $header_thumb ?>" border="0">
	</div>
	<ul id="header_picture_upload" class="design_image_uploader item_actions_list">
		<li id="header_uploading_pick"><a id="pick_header" href="#"><span class="actions action_upload"></span> Upload A Picture</a></li>
		<li id="header_uploading_status" class="hide"><span class="actions action_sync"></span> Uploading: <span id="file_uploading_progress"></span><span id="file_uploading_name"></span></li>			
	<?php if ($header_thumb): ?>
		<li id="header_uploading_delete"><a id="delete_header" href="#"><span class="actions action_delete"></span> Delete Picture</a></li>
		<li id="header_uploading_details" class="small_details hide"><span class="actions_blank"></span> <?= config_item('default_images_max_size') / 1024 ?> MB max size (<?= strtoupper(str_replace('|', ', ', config_item('default_images_formats'))) ?>)</li>
	<?php else: ?>
		<li id="header_uploading_delete" class="hide"><a id="delete_header" href="#"><span class="actions action_delete"></span> Delete Picture</a></li>
		<li id="header_uploading_details" class="small_details"><span class="actions_blank"></span> <?= config_item('default_images_max_size') / 1024 ?> MB max size (<?= strtoupper(str_replace('|', ', ', config_item('default_images_formats'))) ?>)</li>			
	<?php endif; ?>
	</ul>
	<div class="design_color_widget">
		<p>Position<br>
		<?= form_dropdown('header_position', config_item('css_background_position'), $settings['design']['header_position']) ?>
		</p>
		<p>Repeat<br>
		<?= form_dropdown('header_repeat', config_item('css_background_repeat'), $settings['design']['header_repeat']) ?>
		</p>
	</div>
	<div class="design_color_widget">
		<p>Color</p>
		<div id="header_color_picker" class="design_color_picker">
			<div style="background-color: #<?= config_item('design_header_color') ?>"></div>
		</div>
		<div class="design_color_details">
			<input type="text" maxlength="6" size="6" name="header_color" id="header_color" value="<?= config_item('design_header_color') ?>" /><br>
			<img id="header_color_swatch" src="<?= $dashboard_assets ?>icons/colors_24.png">
		</div>
		<div class="clear"></div>
	</div>
	<div class="clear"></div>
</div>
<span class="item_separator"></span>

<div class="content_wrap_inner">
	<h3>Header Links</h3>
	<div class="design_color_widget">
		<p>Normal</p>
		<div id="header_link_color_picker_normal" class="design_color_picker">
			<div style="background-color: #<?= config_item('design_header_link_color_normal') ?>"></div>
		</div>
		<div class="design_color_details">
			<input type="text" maxlength="6" size="6" name="header_link_color_normal" id="header_link_color_normal" value="<?= config_item('design_header_link_color_normal') ?>" /><br>
			<img id="header_link_color_normal_swatch" src="<?= $dashboard_assets ?>icons/colors_24.png">
		</div>
		<div class="clear"></div>
	</div>
	<div class="design_color_widget">
		<p>Visited</p>
		<div id="header_link_color_picker_visited" class="design_color_picker">
			<div style="background-color: #<?= config_item('design_header_link_color_visited') ?>"></div>
		</div>
		<div class="design_color_details">
			<input type="text" maxlength="6" size="6" name="header_link_color_visited" id="header_link_color_visited" value="<?= config_item('design_header_link_color_visited') ?>" /><br>
			<img id="header_link_color_visited_swatch" src="<?= $dashboard_assets ?>icons/colors_24.png">
		</div>
		<div class="clear"></div>
	</div>
	<div class="design_color_widget">	
		<p>Hover</p>
		<div id="header_link_color_picker_hover" class="design_color_picker">
			<div style="background-color: #<?= config_item('design_header_link_color_hover') ?>"></div>
		</div>
		<div class="design_color_details">
			<input type="text" maxlength="6" size="6" name="header_link_color_hover" id="header_link_color_hover" value="<?= config_item('design_header_link_color_hover') ?>" /><br>
			<img id="header_link_color_hover_swatch" src="<?= $dashboard_assets ?>icons/colors_24.png">	
		</div>
		<div class="clear"></div>
	</div>
	<div class="clear"></div>
</div>
<span class="item_separator"></span>

<div class="content_wrap_inner">
	<h3>Background</h3>
	<div id="background_picture" class="design_image_thumb">
		<img id="background_thumbnail" src="<?= $background_thumb ?>" border="0">
	</div>
	<ul id="background_picture_upload" class="design_image_uploader item_actions_list">
		<li id="background_uploading_pick"><a id="pick_background" href="#"><span class="actions action_upload"></span> Upload A Picture</a></li>
		<li id="background_uploading_status" class="hide"><span class="actions action_sync"></span> Uploading: <span id="file_uploading_progress"></span><span id="file_uploading_name"></span></li>			
	<?php if ($background_thumb): ?>
		<li id="background_uploading_delete"><a id="delete_picture" href="#"><span class="actions action_delete"></span> Delete Picture</a></li>
		<li id="background_uploading_details" class="small_details hide"><span class="actions_blank"></span> <?= config_item('default_images_max_size') / 1024 ?> MB max size (<?= strtoupper(str_replace('|', ', ', config_item('default_images_formats'))) ?>)</li>
	<?php else: ?>
		<li id="background_uploading_delete" class="hide"><a id="delete_picture" href="#"><span class="actions action_delete"></span> Delete Picture</a></li>
		<li id="background_uploading_details" class="small_details"><span class="actions_blank"></span> <?= config_item('default_images_max_size') / 1024 ?> MB max size (<?= strtoupper(str_replace('|', ', ', config_item('default_images_formats'))) ?>)</li>			
	<?php endif; ?>
	</ul>
	<div class="design_color_widget">
		<p>Position<br>
		<?= form_dropdown('background_position', config_item('css_background_position'), $settings['design']['background_position']) ?>
		</p>
		<p>Repeat<br>
		<?= form_dropdown('background_repeat', config_item('css_background_repeat'), $settings['design']['background_repeat']) ?>
		</p>
	</div>
	<div class="design_color_widget">
		<p>Color</p>
		<div id="background_color_picker" class="design_color_picker">
			<div style="background-color: #<?= config_item('design_background_color') ?>"></div>
		</div>
		<div class="design_color_details">
			<input type="text" maxlength="6" size="6" name="background_color" id="background_color" value="<?= config_item('design_background_color') ?>" /><br>
			<img id="background_color_swatch" src="<?= $dashboard_assets ?>icons/colors_24.png">
		</div>
		<div class="clear"></div>
	</div>
	<div class="clear"></div>
	<p><input type="submit" value="Save"></p>
</div>
</form>

<link rel="stylesheet" href="<?= $dashboard_assets ?>colorpicker.css" type="text/css" />
<script type="text/javascript" src="<?= $dashboard_assets ?>colorpicker.js"></script>
<script type="text/javascript" src="<?= base_url() ?>js/plupload.js"></script>
<script type="text/javascript" src="<?= base_url() ?>js/plupload.html5.js"></script>
<script type="text/javascript" src="<?= base_url() ?>js/plupload.flash.js"></script>
<script type="text/javascript">
$(document).ready(function()
{
	// Font Color Pickers
	$('#link_color_picker_normal, #link_color_normal_swatch').ColorPicker({
		color: '#<?= config_item('design_link_color_normal') ?>',
		onShow: function (colpkr) {
			$(colpkr).fadeIn(500);
			return false;
		},
		onHide: function (colpkr) {
			$(colpkr).fadeOut(500);
			return false;
		},
		onChange: function (hsb, hex, rgb) {
			$('#link_color_picker_normal div').css('backgroundColor', '#' + hex);
			$('#link_color_normal').val(hex);			
		}
	});	

	$('#link_color_picker_visited, #link_color_visited_swatch').ColorPicker({
		color: '#<?= config_item('design_link_color_visited') ?>',
		onShow: function (colpkr) {
			$(colpkr).fadeIn(500);
			return false;
		},
		onHide: function (colpkr) {
			$(colpkr).fadeOut(500);
			return false;
		},
		onChange: function (hsb, hex, rgb) {
			$('#link_color_picker_visited div').css('backgroundColor', '#' + hex);
			$('#link_color_visited').val(hex);			
		}
	});	
	
	$('#link_color_picker_hover, #link_color_hover_swatch').ColorPicker({
		color: '#<?= config_item('design_link_color_hover') ?>',
		onShow: function (colpkr) {
			$(colpkr).fadeIn(500);
			return false;
		},
		onHide: function (colpkr) {
			$(colpkr).fadeOut(500);
			return false;
		},
		onChange: function (hsb, hex, rgb) {
			$('#link_color_picker_hover div').css('backgroundColor', '#' + hex);
			$('#link_color_hover').val(hex);			
		}
	});

	// Header Color Pickers
	$('#header_color_picker, #header_color_swatch').ColorPicker({
		color: '#<?= config_item('design_header_color') ?>',
		onShow: function (colpkr) {
			$(colpkr).fadeIn(500);
			return false;
		},
		onHide: function (colpkr) {
			$(colpkr).fadeOut(500);
			return false;
		},
		onChange: function (hsb, hex, rgb) {
			$('#header_color_picker div').css('backgroundColor', '#' + hex);
			$('#header_color').val(hex);
		}
	});
	
	// Header Link Color Pickers
	$('#header_link_color_picker_normal, #header_link_color_normal_swatch').ColorPicker({
		color: '#<?= config_item('design_header_link_color_normal') ?>',
		onShow: function (colpkr) {
			$(colpkr).fadeIn(500);
			return false;
		},
		onHide: function (colpkr) {
			$(colpkr).fadeOut(500);
			return false;
		},
		onChange: function (hsb, hex, rgb) {
			$('#header_link_color_picker_normal div').css('backgroundColor', '#' + hex);
			$('#header_link_color_normal').val(hex);			
		}
	});	

	$('#header_link_color_picker_visited, #header_link_color_visited_swatch').ColorPicker({
		color: '#<?= config_item('design_header_link_color_visited') ?>',
		onShow: function (colpkr) {
			$(colpkr).fadeIn(500);
			return false;
		},
		onHide: function (colpkr) {
			$(colpkr).fadeOut(500);
			return false;
		},
		onChange: function (hsb, hex, rgb) {
			$('#header_link_color_picker_visited div').css('backgroundColor', '#' + hex);
			$('#header_link_color_visited').val(hex);			
		}
	});	
	
	$('#header_link_color_picker_hover, #header_link_color_hover_swatch').ColorPicker({
		color: '#<?= config_item('design_font_color_hover') ?>',
		onShow: function (colpkr) {
			$(colpkr).fadeIn(500);
			return false;
		},
		onHide: function (colpkr) {
			$(colpkr).fadeOut(500);
			return false;
		},
		onChange: function (hsb, hex, rgb) {
			$('#header_link_color_picker_hover div').css('backgroundColor', '#' + hex);
			$('#header_link_color_hover').val(hex);			
		}
	});	

	// Background Color Pickers
	$('#background_color_picker, #background_color_swatch').ColorPicker({
		color: '#<?= config_item('design_background_color') ?>',
		onShow: function (colpkr) {
			$(colpkr).fadeIn(500);
			return false;
		},
		onHide: function (colpkr) {
			$(colpkr).fadeOut(500);
			return false;
		},
		onChange: function (hsb, hex, rgb) {
			$('#background_color_picker div').css('backgroundColor', '#' + hex);
			$('#background_color').val(hex);
		}
	});
	
	// Upload Picture
	$('#pick_logo').mediaUploader(
	{
		max_size	: '<?= $upload_size ?>mb',
		create_url	: base_url + 'api/settings/upload_site_picture/type/site_logo',
		formats		: {title : 'Allowed Files', extensions : '<?= $upload_formats ?>'},
		start		: function(files)
		{
			// Show Upload Link
			$('#logo_uploading_pick').hide(); 
			$('#logo_uploading_delete').hide();
			$('#logo_uploading_details').hide();
			$('#logo_uploading_status').show();
			$('#file_uploading_name').html(files[0].name);
		},
		complete	: function(response)
		{
			// Replace Uploading Status
			$('#logo_uploading_status').delay(500).fadeOut();
			$('#logo_uploading_pick').delay(1250).fadeIn(); 
			$('#logo_uploading_delete').delay(1250).fadeIn();
		
			if (response.status == 'success')
			{
				$('#logo_thumbnail').attr('src', base_url + 'uploads/sites/1/small_' + response.upload_info.file_name)
				if ($('#name_link img').length > 0)
				{		
					$('#name_link img').attr('src', base_url + 'uploads/sites/1/small_' + response.upload_info.file_name);
				}
				else
				{
					var name_text = $('#name_link').html();
					$('#name_link').html('<img src="' + base_url + 'uploads/sites/1/small_' + response.upload_info.file_name + '" border="0">' + name_text);
				}
			}
			else
			{			
				$('#content_message').notify({status:response.status,message:response.message});	
			}		
		}
	});	

	// Upload Header
	$('#pick_header').mediaUploader(
	{
		max_size	: '<?= $upload_size ?>mb',
		create_url	: base_url + 'api/settings/upload_site_picture/type/header_image',
		formats		: {title : 'Allowed Files', extensions : '<?= $upload_formats ?>'},
		start		: function(files)
		{
			// Show Upload Link
			$('#header_uploading_pick').hide(); 
			$('#header_uploading_delete').hide();
			$('#header_uploading_details').hide();
			$('#header_uploading_status').show();
			$('#file_uploading_name').html(files[0].name);
		},
		complete	: function(response)
		{
			// Replace Uploading Status
			$('#header_uploading_status').delay(500).fadeOut();
			$('#header_uploading_pick').delay(1250).fadeIn(); 
			$('#header_uploading_delete').delay(1250).fadeIn();
		
			if (response.status == 'success')
			{
				$('#header_thumbnail').attr('src', base_url + 'uploads/sites/1/small_' + response.upload_info.file_name)
			}
			else
			{			
				$('#content_message').notify({status:response.status,message:response.message});	
			}		
		}
	});		


	// Upload Background
	$('#pick_background').mediaUploader(
	{
		max_size	: '<?= $upload_size ?>mb',
		create_url	: base_url + 'api/settings/upload_site_picture/type/background_image',
		formats		: {title : 'Allowed Files', extensions : '<?= $upload_formats ?>'},
		start		: function(files)
		{
			// Show Upload Link
			$('#background_uploading_pick').hide(); 
			$('#background_uploading_delete').hide();
			$('#background_uploading_details').hide();
			$('#background_uploading_status').show();
			$('#file_uploading_name').html(files[0].name);
		},
		complete	: function(response)
		{
			// Replace Uploading Status
			$('#background_uploading_status').delay(500).fadeOut();
			$('#background_uploading_pick').delay(1250).fadeIn(); 
			$('#background_uploading_delete').delay(1250).fadeIn();
		
			if (response.status == 'success')
			{
				$('#background_thumbnail').attr('src', base_url + 'uploads/sites/1/small_' + response.upload_info.file_name)
			}
			else
			{			
				$('#content_message').notify({status:response.status,message:response.message});	
			}		
		}
	});		
	
	
	// Delete Picture
	$('#delete_picture').live('click', function(e)
	{	
		e.preventDefault();
		$.oauthAjax(
		{
			oauth 		: user_data,
			url			: base_url + 'api/users/delete_profile_picture/id/' + user_data.user_id,
			type		: 'GET',
			dataType	: 'json',
	  		success		: function(result)
	  		{			
				if (result.status == 'success')
				{
					$('#profile_thumbnail').attr('src', base_url + 'uploads/profiles/medium_nopicture.png');				
					$('#uploading_delete').fadeOut('slow', function()
					{
						$('#uploading_details').delay(500).fadeIn();
					});					
				}
				else
				{
					$('#content_message').notify({status:result.status,message:result.message});			
				}
		 	}
		});
	});	
});
</script>
<?= $shared_ajax ?>