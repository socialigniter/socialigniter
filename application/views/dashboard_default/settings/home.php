<form name="settings_update" id="settings_update" method="post" action="<?= base_url() ?>api/settings/modify" enctype="multipart/form-data">

<div class="content_wrap_inner">

	<h3>Permission</h3>

	<p>View
	<?= form_dropdown('view_permission', config_item('users_levels'), $settings['home']['view_permission']) ?>
	</p>

	<p>Create
	<?= form_dropdown('create_permission', config_item('users_levels'), $settings['home']['create_permission']) ?>
	</p>

	<p>Redirect<br>
	<?= base_url() ?> <input type="text" size="20" name="view_redirect" value="<?= $settings['home']['view_redirect'] ?>" />
	</p>

	<input type="hidden" name="module" value="home">

	<p><input type="submit" name="save" value="Save" /></p>

</div>

</form>

<?= $shared_ajax ?>