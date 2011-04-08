<form name="settings_update" id="settings_update" method="post" action="<?= base_url() ?>api/settings/modify" enctype="multipart/form-data">

<div class="content_wrap_inner">
	
	<div class="content_inner_top_right">
		<h3>Module</h3>
		<p><?= form_dropdown('enabled', config_item('enable_disable'), $settings['places']['enabled']) ?></p>
	</div>

	<h3>Permissions</h3>

	<p>Create
	<?= form_dropdown('create_permission', config_item('users_levels'), $settings['places']['create_permission']) ?>
	</p>

	<p>Publish
	<?= form_dropdown('publish_permission', config_item('users_levels'), $settings['places']['publish_permission']) ?>	
	</p>

	<p>Manage All	
	<?= form_dropdown('manage_permission', config_item('users_levels'), $settings['places']['manage_permission']) ?>	
	</p>	

</div>	
	

<span class="item_separator"></span>

<div class="content_wrap_inner">
			
	<h3>Display</h3>
	
	<p>Ratings
	<?= form_dropdown('ratings_allow', config_item('yes_or_no'), $settings['places']['ratings_allow']) ?>
	</p>
				
			
	<h3>Comments</h3>	

	<p>Allow
	<?= form_dropdown('comments_allow', config_item('yes_or_no'), $settings['places']['comments_allow']) ?>
	</p>

	<p>Comments Per-Page
	<?= form_dropdown('comments_per_page', config_item('amount_increments_five'), $settings['places']['comments_per_page']) ?>
	</p>

	<input type="hidden" name="module" value="places">

	<p><input type="submit" name="save" value="Save" /></p>

</div>

</form>

<?= $shared_ajax ?>