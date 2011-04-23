<form name="settings_update" id="settings_update" method="post" action="<?= base_url() ?>api/settings/modify" enctype="multipart/form-data">

<div class="content_wrap_inner">

	<h3>Display</h3>

	<p>Public Timeline
	<?= form_dropdown('public_timeline', config_item('yes_or_no'), $settings['home']['public_timeline']) ?>
	</p>

	<p>Date
	<?= form_dropdown('date_style', config_item('date_style_types'), $settings['home']['date_style']) ?>
	</p>

	<p>Status<br>
	<input type="text" size="4" name="status_length" value="<?= $settings['home']['status_length'] ?>" /> characters
	</p>
		
	<p>Description<br>
	<input type="text" size="4" name="description_length" value="<?= $settings['home']['description_length'] ?>" /> characters
	</p>
		
</div>

<span class="item_separator"></span>

<div class="content_wrap_inner">

	<h3>Actions</h3>

	<p>Share
	<?= form_dropdown('share', config_item('yes_or_no'), $settings['home']['share']) ?>
	</p>
	
	<p>Like
	<?= form_dropdown('like', config_item('yes_or_no'), $settings['home']['like']) ?>
	</p>
		
</div>

<span class="item_separator"></span>

<div class="content_wrap_inner">
	
	<h3>Comments</h3>	

	<p>Allow
	<?= form_dropdown('comments_allow', config_item('yes_or_no'), $settings['home']['comments_allow']) ?>	
	</p>

	<p>Comments Per-Status
	<?= form_dropdown('comments_per_page', config_item('numbers_one_five'), $settings['home']['comments_per_page']) ?>
	</p>

	<input type="hidden" name="module" value="home">

	<p><input type="submit" name="save" value="Save" /></p>

</div>

</form>

<?= $shared_ajax ?>