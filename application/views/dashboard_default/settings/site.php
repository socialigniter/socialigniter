<form name="settings_update" id="settings_update" method="post" action="<?= base_url() ?>api/settings/modify" enctype="multipart/form-data">

<div class="content_wrap_inner">

	<h3>Meta Tags</h3>

	<p>Title<br>
	<input type="text" name="title" size="40" value="<?= $settings['site']['title'] ?>"></p>

	<p>Tagline<br>
	<input type="text" name="tagline" size="40" value="<?= $settings['site']['tagline'] ?>"></p>

	<p>Keywords<br>
	<input type="text" name="keywords" size="40" placeholder="Dogs, cats, birds, girrafes" value="<?= $settings['site']['keywords'] ?>"></p>

	<p>Description<br>
	<textarea name="description" cols="38" rows="4"><?= $settings['site']['description'] ?></textarea></p>

</div>

<span class="item_separator"></span>

<div class="content_wrap_inner">

	<h3>Technical</h3>

	<p>Domain<br>
	<input type="text" name="url" size="40" placeholder="http://website.com" value="<?= $settings['site']['url'] ?>"></p>

	<p>Admin Email<br>
	<input type="text" name="admin_email" size="40" value="<?= $settings['site']['admin_email'] ?>"></p>

</div>

<span class="item_separator"></span>

<div class="content_wrap_inner">

	<h3>Display</h3>

	<p>Language
	<?= form_dropdown('languages_default', config_item('languages'), $settings['site']['languages_default']) ?>
	</p>

</div>

<span class="item_separator"></span>

<div class="content_wrap_inner">

	<h3>Categories</h3>

	<table border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td>Large</td>
		<td><input class="nullify" type="checkbox" name="images_sizes_large" value="<?= $settings['site']['images_sizes_large'] ?>"></td>
		<td><input type="text" name="images_large_width" value="<?= $settings['site']['images_large_width'] ?>" size="3"> x <input type="text" name="images_large_height" value="<?= $settings['site']['images_large_height'] ?>" size="3"> px</td>
	</tr>
	<tr>
		<td>Medium</td>
		<td><input class="nullify" type="checkbox" name="images_sizes_medium" value="<?= $settings['site']['images_sizes_medium'] ?>"></td>
		<td><input type="text" name="images_medium_width" value="<?= $settings['site']['images_medium_width'] ?>" size="3"> x <input type="text" name="images_medium_height" value="<?= $settings['site']['images_medium_height'] ?>" size="3"> px</td>
	</tr>
	<tr>
		<td>Small</td>
		<td><input class="nullify" type="checkbox" name="images_sizes_small" value="<?= $settings['site']['images_sizes_small'] ?>"></td>
		<td><input type="text" name="images_small_width" value="<?= $settings['site']['images_small_width'] ?>" size="3"> x <input type="text" name="images_small_height" value="<?= $settings['site']['images_small_height'] ?>" size="3"> px</td>
	</tr>
	<tr>
		<td>Original</td>
		<td><input class="nullify" type="checkbox" name="images_sizes_original" value="<?= $settings['site']['images_sizes_original'] ?>"></td>
		<td>Keep original uploaded image</td>		
	</tr>	
	</table>	


	<input type="hidden" name="module" value="site">

	<p><input type="submit" name="save" value="Save" /></p>

</div>

</form>

<?= $shared_ajax ?>