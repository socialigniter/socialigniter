<form name="settings_update" id="settings_update" method="post" action="<?= base_url() ?>api/settings/modify" enctype="multipart/form-data">

<div class="content_wrap_inner">
	
	<div class="content_inner_top_right">
		<h3>Module</h3>
		<p><?= form_dropdown('enabled', config_item('enable_disable'), $settings['places']['enabled']) ?></p>
	</div>
		
	<p>User Lookup
	<?= form_dropdown('user_lookup', config_item('yes_or_no'), $settings['places']['user_lookup']) ?>
	</p>	

	<h3>User</h3>
	
	<p>Ratings
	<?= form_dropdown('ratings_allow', config_item('yes_or_no'), $settings['places']['ratings_allow']) ?>
	</p>

	<p>URL
	<?= form_dropdown('url_style', config_item('url_style_posts'), $settings['places']['url_style']) ?>
	</p>			

</div>	
	

<span class="item_separator"></span>

<div class="content_wrap_inner">
			
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