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
	
	<p><img class="font_color_picker_icon" src="<?= $dashboard_assets ?>icons/colors_24.png"> Color</p>

	<p><div id="font_color_picker_1" class="design_color_picker"><div></div></div> Normal</p>
	<p><div id="font_color_picker_2" class="design_color_picker"><div></div></div> Hover</p>
	<p><div id="font_color_picker_3" class="design_color_picker"><div></div></div> Visited</p>

	<p><input type="text" maxlength="6" size="6" id="font_color_1" value="00ff00" /> <input type="text" maxlength="6" size="6" id="font_color_2" value="0000ff" /> <input type="text" maxlength="6" size="6" id="font_color_3" value="ff0000" /></p>

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

	<p><img id="background_color_swatch" src="<?= $dashboard_assets ?>icons/colors_24.png"> Color</p>

	<p><div id="background_color_picker_1" class="design_color_picker"><div style="background-color: #ffffff"></div></div></p>

	<input type="text" maxlength="6" size="6" id="background_color_1" value="00ff00" />

	<p><input type="submit" value="Save"></p>
</div>
</form>

<link rel="stylesheet" href="<?= $dashboard_assets ?>colorpicker.css" type="text/css" />
<script type="text/javascript" src="<?= $dashboard_assets ?>colorpicker.js"></script>
<script type="text/javascript">
$(document).ready(function()
{
	$('#font_color_picker_1').ColorPicker({
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
			$('#font_color_picker_1 div').css('backgroundColor', '#' + hex);
			$('#font_color_1').val(hex);			
		}
	});	

	$('#font_color_picker_2').ColorPicker({
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
			$('#font_color_picker_2 div').css('backgroundColor', '#' + hex);
			$('#font_color_2').val(hex);			
		}
	});	
	
	$('#font_color_picker_3').ColorPicker({
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
			$('#font_color_picker_3 div').css('backgroundColor', '#' + hex);
			$('#font_color_3').val(hex);			
		}
	});	

	$('#background_color_picker_1').ColorPicker({
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
			$('#background_color_picker_1 div').css('backgroundColor', '#' + hex);
			$('#background_color_1').val(hex);
		}
	});	

});
</script>
<?= $shared_ajax ?>