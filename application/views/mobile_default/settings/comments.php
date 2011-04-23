<form name="settings_update" id="settings_update" method="post" action="<?= base_url() ?>api/settings/modify" enctype="multipart/form-data">

<div class="content_wrap_inner">

	<div class="content_inner_top_right">
		<h3>Module</h3>
		<p><?= form_dropdown('enabled', config_item('enable_disable'), $settings['comments']['enabled']) ?></p>
	</div>	
	
	<h3>Settings</h3>

	<p>Reply
	<?= form_dropdown('reply', config_item('yes_or_no'), $settings['comments']['reply']) ?>
	</p>
	
	<p>Reply Level (Not Implemented)
	<?= form_dropdown('reply_level', config_item('numbers_one_five'), $settings['comments']['reply_level']) ?>
	</p>
	
	<p>Date
	<?= form_dropdown('date_style', config_item('date_style_types'), $settings['comments']['date_style']) ?>
	</p>

</div>

<span class="item_separator"></span>

<div class="content_wrap_inner">

	<h3>Security</h3>

	<p>Akismet
	<?= form_dropdown('akismet', config_item('enable_disable'), $settings['comments']['akismet']) ?></p>

	<p>ReCAPTCHA
	<?= form_dropdown('recaptcha', config_item('enable_disable'), $settings['comments']['recaptcha']) ?></p>	
	
</div>	

<span class="item_separator"></span>

<div class="content_wrap_inner">

	<h3>Email</h3>

	<p>Signup Message (Not Implemented)
	<?= form_dropdown('email_signup', config_item('yes_or_no'), $settings['comments']['email_signup']) ?>
	</p>

	<p>Comment Replies (Not Implemented)
	<?= form_dropdown('email_replies', config_item('yes_or_no'), $settings['comments']['email_replies']) ?>
	</p>

	<input type="hidden" name="module" value="comments">

	<p><input type="submit" name="save" value="Save" /></p>
	
</div>

</form>

<?= $shared_ajax ?>