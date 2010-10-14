<form name="settings" method="post" action="<?= base_url() ?>settings/update" enctype="multipart/form-data">

<div class="content_wrap_inner">

	<h3>Signups</h3>
	
	<p>Enabled
	<?= form_dropdown('signup', config_item('enable_disable'), $settings['users']['signup']) ?>
	</p>	

	<p>ReCAPTCHA
	<?= form_dropdown('signup_recaptcha', config_item('enable_disable'), $settings['users']['signup_recaptcha']) ?></p>	
	
</div>	

<span class="item_separator"></span>

<div class="content_wrap_inner">

	<h3>Login</h3>
	
	<p>Enabled
	<?= form_dropdown('login', config_item('enable_disable'), $settings['users']['login']) ?>
	</p>

	<p>ReCAPTCHA
	<?= form_dropdown('login_recaptcha', config_item('enable_disable'), $settings['users']['login_recaptcha']) ?></p>	
	
</div>	

<span class="item_separator"></span>

<div class="content_wrap_inner">

	<h3>Profiles</h3>

	<p>Enabled
	<?= form_dropdown('profile', config_item('enable_disable'), $settings['users']['profile']) ?>
	</p>

	<p>Activity
	<?= form_dropdown('profile_activity', config_item('yes_or_no'), $settings['users']['profile_activity']) ?>
	</p>

	<p>Friends / Followers
	<?= form_dropdown('profile_relationships', config_item('yes_or_no'), $settings['users']['profile_relationships']) ?>
	</p>
		
	<p>Content
	<?= form_dropdown('profile_content', config_item('yes_or_no'), $settings['users']['profile_content']) ?>
	</p>		
		
</div>

<span class="item_separator"></span>

<div class="content_wrap_inner">
	
	<h3>Messages</h3>	

	<p>Allow
	<?= form_dropdown('message_allow', config_item('yes_or_no'), $settings['users']['message_allow']) ?>	
	</p>

	<p>ReCAPTCHA
	<?= form_dropdown('message_recaptcha', config_item('amount_increments_five'), $settings['users']['message_recaptcha']) ?>
	</p>
	
</div>

<span class="item_separator"></span>

<div class="content_wrap_inner">
	
	<h3>Comments</h3>	

	<p>Allow
	<?= form_dropdown('comments_allow', config_item('yes_or_no'), $settings['users']['comments_allow']) ?>	
	</p>

	<p>Comments Per-Page
	<?= form_dropdown('comments_per_page', config_item('amount_increments_five'), $settings['users']['comments_per_page']) ?>
	</p>

	<input type="hidden" name="module" value="users">

	<p><input type="submit" name="save" value="Save" /></p>
	
</div>

</form>