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
	<textarea name="description" cols="26" rows="6"><?= $settings['site']['description'] ?></textarea></p>

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

	<h3>Technical</h3>

	<p>Domain<br>
	<input type="text" name="url" size="40" placeholder="http://website.com" value="<?= $settings['site']['url'] ?>"></p>

	<p>Admin Email<br>
	<input type="text" name="admin_email" size="40" value="<?= $settings['site']['admin_email'] ?>"></p>

	<input type="hidden" name="module" value="site">

	<p><input type="submit" name="save" value="Save" /></p>

</div>

</form>

<?= $shared_ajax ?>