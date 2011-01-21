<form name="settings" method="post" action="<?= base_url() ?>settings/update" enctype="multipart/form-data">

<div class="content_wrap_inner">

	<div class="content_inner_top_right">
		<h3>Module</h3>
		<p><?= form_dropdown('enabled', config_item('enable_disable'), $settings['pages']['enabled']) ?></p>
	</div>

	<h3>Display</h3>

	<p>Ratings
	<?= form_dropdown('ratings_allow', config_item('yes_or_no'), $settings['pages']['ratings_allow']) ?>
	</p>

	<p>Display Tags
	<?= form_dropdown('tags_display', config_item('yes_or_no'), $settings['pages']['tags_display']) ?>
	</p>
		
</div>

<span class="item_separator"></span>

<div class="content_wrap_inner">
	
	<h3>Comments</h3>	

	<p>Allow
	<?= form_dropdown('comments_allow', config_item('yes_or_no'), $settings['pages']['comments_allow']) ?>	
	</p>

	<p>Comments Per-Page
	<?= form_dropdown('comments_per_page', config_item('amount_increments_five'), $settings['pages']['comments_per_page']) ?>
	</p>

	<input type="hidden" name="module" value="pages">

	<p><input type="submit" name="save" value="Save" /></p>

</div>

</form>