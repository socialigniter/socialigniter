<form name="settings_update" id="settings_update" method="post" action="<?= base_url() ?>api/settings/modify" enctype="multipart/form-data">
<div class="content_wrap_inner">
	<h3>Logo</h3>
	<div id="logo_picture">
		<img id="background_thumbnail" src="<?= '' ?>" border="0">
	</div>
	<ul id="logo_picture_upload" class="item_actions_list">
		<li id="uploading_pick"><a id="pickfiles" href="#"><span class="actions action_upload"></span> Upload A Picture</a></li>
		<li id="uploading_status" class="hide"><span class="actions action_sync"></span> Uploading: <span id="file_uploading_progress"></span><span id="file_uploading_name"></span></li>			
	<?php if ($logo_image): ?>
		<li id="uploading_delete"><a id="delete_picture" href="#"><span class="actions action_delete"></span> Delete Picture</a></li>
		<li id="uploading_details" class="small_details hide"><span class="actions_blank"></span> <?= config_item('users_images_max_size') / 1024 ?> MB max size (<?= strtoupper(str_replace('|', ', ', config_item('users_images_formats'))) ?>)</li>
	<?php else: ?>
		<li id="uploading_delete" class="hide"><a id="delete_picture" href="#"><span class="actions action_delete"></span> Delete Picture</a></li>
		<li id="uploading_details" class="small_details"><span class="actions_blank"></span> <?= config_item('users_images_max_size') / 1024 ?> MB max size (<?= strtoupper(str_replace('|', ', ', config_item('users_images_formats'))) ?>)</li>			
	<?php endif; ?>
	</ul>
</div>
<span class="item_separator"></span>

<div class="content_wrap_inner">

	<h3>Links</h3>

	<div class="design_color_widget">
		<p>Normal</p>
		<div id="font_color_picker_normal" class="design_color_picker">
			<div style="background-color: #<?= config_item('design_font_color_normal') ?>"></div>
		</div>
		<div class="design_color_details">
			<input type="text" maxlength="6" size="6" name="font_color_normal" id="font_color_normal" value="<?= config_item('design_font_color_normal') ?>" /><br>
			<img id="font_color_normal_swatch" src="<?= $dashboard_assets ?>icons/colors_24.png">
		</div>
		<div class="clear"></div>
	</div>

	<div class="design_color_widget">	
		<p>Visited</p>
		<div id="font_color_picker_visited" class="design_color_picker">
			<div style="background-color: #<?= config_item('design_font_color_visited') ?>"></div>
		</div>
		<div class="design_color_details">
			<input type="text" maxlength="6" size="6" name="font_color_visited" id="font_color_visited" value="<?= config_item('design_font_color_visited') ?>" /><br>
			<img id="font_color_visited_swatch" src="<?= $dashboard_assets ?>icons/colors_24.png">
		</div>
		<div class="clear"></div>
	</div>
	
	<div class="design_color_widget">	
		<p>Hover</p>
		<div id="font_color_picker_hover" class="design_color_picker">
			<div style="background-color: #<?= config_item('design_font_color_hover') ?>"></div>
		</div>
		<div class="design_color_details">
			<input type="text" maxlength="6" size="6" name="font_color_hover" id="font_color_hover" value="<?= config_item('design_font_color_hover') ?>" /><br>
			<img id="font_color_hover_swatch" src="<?= $dashboard_assets ?>icons/colors_24.png">	
		</div>
		<div class="clear"></div>
	</div>
	<div class="clear"></div>

</div>
<span class="item_separator"></span>

<div class="content_wrap_inner">

	<h3>Background</h3>
	<div id="background_picture">
		<img id="background_thumbnail" src="<?= '' ?>" border="0">
	</div>
	<ul id="background_picture_upload" class="item_actions_list">
		<li id="uploading_pick"><a id="pickfiles" href="#"><span class="actions action_upload"></span> Upload A Picture</a></li>
		<li id="uploading_status" class="hide"><span class="actions action_sync"></span> Uploading: <span id="file_uploading_progress"></span><span id="file_uploading_name"></span></li>			
	<?php if ($background_image): ?>
		<li id="uploading_delete"><a id="delete_picture" href="#"><span class="actions action_delete"></span> Delete Picture</a></li>
		<li id="uploading_details" class="small_details hide"><span class="actions_blank"></span> <?= config_item('users_images_max_size') / 1024 ?> MB max size (<?= strtoupper(str_replace('|', ', ', config_item('users_images_formats'))) ?>)</li>
	<?php else: ?>
		<li id="uploading_delete" class="hide"><a id="delete_picture" href="#"><span class="actions action_delete"></span> Delete Picture</a></li>
		<li id="uploading_details" class="small_details"><span class="actions_blank"></span> <?= config_item('users_images_max_size') / 1024 ?> MB max size (<?= strtoupper(str_replace('|', ', ', config_item('users_images_formats'))) ?>)</li>			
	<?php endif; ?>
	</ul>

	<p>Position<br>
	<?= form_dropdown('background_position', config_item('css_background_position'), $settings['design']['background_position']) ?>
	</p>
	<p>Repeat<br>
	<?= form_dropdown('background_repeat', config_item('css_background_repeat'), $settings['design']['background_repeat']) ?>
	</p>

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
<script type="text/javascript">
$(document).ready(function()
{
	$('#font_color_picker_normal, #font_color_normal_swatch').ColorPicker({
		color: '#0000ff',
		onShow: function (colpkr) {
			$(colpkr).fadeIn(500);
			return false;
		},
		onHide: function (colpkr) {
			$(colpkr).fadeOut(500);
			return false;
		},
		onChange: function (hsb, hex, rgb) {
			$('#font_color_picker_normal div').css('backgroundColor', '#' + hex);
			$('#font_color_normal').val(hex);			
		}
	});	

	$('#font_color_picker_visited, #font_color_visited_swatch').ColorPicker({
		color: '#0000ff',
		onShow: function (colpkr) {
			$(colpkr).fadeIn(500);
			return false;
		},
		onHide: function (colpkr) {
			$(colpkr).fadeOut(500);
			return false;
		},
		onChange: function (hsb, hex, rgb) {
			$('#font_color_picker_visited div').css('backgroundColor', '#' + hex);
			$('#font_color_visited').val(hex);			
		}
	});	
	
	$('#font_color_picker_hover, #font_color_hover_swatch').ColorPicker({
		color: '#0000ff',
		onShow: function (colpkr) {
			$(colpkr).fadeIn(500);
			return false;
		},
		onHide: function (colpkr) {
			$(colpkr).fadeOut(500);
			return false;
		},
		onChange: function (hsb, hex, rgb) {
			$('#font_color_picker_hover div').css('backgroundColor', '#' + hex);
			$('#font_color_hover').val(hex);			
		}
	});	

	$('#background_color_picker, #background_color_swatch').ColorPicker({
		color: '#0000ff',
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
});
</script>
<?= $shared_ajax ?>