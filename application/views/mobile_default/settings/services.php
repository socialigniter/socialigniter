<form name="settings_update" id="settings_update" method="post" action="<?= base_url() ?>api/settings/modify" enctype="multipart/form-data">

<div class="content_wrap_inner">

	<h3>Email</h3>
	
	<p>Configure your email settings to use special email sending solutions</p>
	
	<p><?= form_dropdown('email_protocol', config_item('email_protocol'), $settings['services']['email_protocol']) ?></p>	

	<div id="email_smtp_options">
		<p><input type="text" name="smtp_host" value="<?= $settings['services']['smtp_host'] ?>"> Hostname</p>
		
		<p><input type="text" name="smtp_user" value="<?= $settings['services']['smtp_user'] ?>"> Username</p>
	
		<p><input type="text" name="smtp_pass" value="<?= $settings['services']['smtp_pass'] ?>"> Password</p>
	
		<p><input type="text" name="smtp_port" value="<?= $settings['services']['smtp_port'] ?>"> Port</p>
	</div>

</div>

<span class="item_separator"></span>

<div class="content_wrap_inner">

	<h3>Mobile</h3>
	
	<p>Configure SMS & Voice settings to use a module that supports mobile</p>
	
	<p><?= form_dropdown('mobile_enabled', config_item('enable_disable'), $settings['services']['mobile_enabled']) ?></p>
	
	<p><?= form_dropdown('mobile_module', $mobile_modules, $settings['services']['mobile_module']) ?></p>

</div>

<span class="item_separator"></span>

<div class="content_wrap_inner">

	<h3>SEO</h3>
	
	<p>Shorten, share and track your links with <a href="http://bit.ly" target="_blank">Bit.ly</a>. You will need to <a href="http://bit.ly/a/sign_up" target="_blank">signup</a> and obtain an <a href="http://bit.ly/a/your_api_key" target="_blank">API Key</a> to use it.</p>

	<p><input type="text" name="google_webmaster" value="<?= $settings['services']['google_webmaster'] ?>"> Google Webmaster</p>
	
	<p><input type="text" name="google_analytics" value="<?= $settings['services']['google_analytics'] ?>"> Google Analytics</p>

	<p><input type="text" name="bing_webmaster" value="<?= $settings['services']['bing_webmaster'] ?>"> Bing Webmaster</p>

</div>

<span class="item_separator"></span>

<div class="content_wrap_inner">

	<h3>Gravatar</h3>
	
	<p>Over 1 million people have accounts on <a href="http://gravatar.com" target="_blank">Gravatar</a> keep this enabled to display user's profile picture based on their email address.</p>
		
	<p><?= form_dropdown('gravatar_enabled', config_item('enable_disable'), $settings['services']['gravatar_enabled']) ?></p>

</div>

<span class="item_separator"></span>

<div class="content_wrap_inner">

	<h3>Bit.ly</h3>
	
	<p>Shorten, share and track your links with <a href="http://bit.ly" target="_blank">Bit.ly</a>. You will need to <a href="http://bit.ly/a/sign_up" target="_blank">signup</a> and obtain an <a href="http://bit.ly/a/your_api_key" target="_blank">API Key</a> to use it.</p>

	<p><input type="text" name="bitly_login" value="<?= $settings['services']['bitly_login'] ?>"> Login</p>
	
	<p><input type="text" name="bitly_api_key" value="<?= $settings['services']['bitly_api_key'] ?>"> API Key</p>
	
	<p>Domain 
	<?= form_dropdown('bitly_domain', config_item('bitly_domain'), $settings['services']['bitly_domain']) ?>
	</p>	

	<p><?= form_dropdown('bitly_enabled', config_item('enable_disable'), $settings['services']['bitly_enabled']) ?></p>
	
</div>

<span class="item_separator"></span>

<div class="content_wrap_inner">

	<h3>Askimet</h3>
	
	<p>The best defense against comment spam is <a href="http://akismet.com" target="_blank">Askimet</a>. Obtain an <a href="http://akismet.com/personal/" target="_blank">API Key</a> to use it.</p>
	
	<p><input type="text" name="akismet_key" value="<?= $settings['services']['akismet_key'] ?>"> API Key </p>
	
</div>

<span class="item_separator"></span>

<div class="content_wrap_inner">

	<h3>ReCAPTCHA</h3>
	
	<p>Block spam & bogus user signups with <a href="http://www.google.com/recaptcha" target="_blank">ReCAPTCHA</a>. You must obtain <a href="https://www.google.com/recaptcha/admin/create" target="_blank">API Keys</a> to use it.</p>
	
	<p><input type="text" name="recaptcha_public" value="<?= $settings['services']['recaptcha_public'] ?>"> Public Key</p>
	
	<p><input type="text" name="recaptcha_private" value="<?= $settings['services']['recaptcha_private'] ?>"> Private Key</p>

	<p><input type="text" name="recaptcha_theme" value="<?= $settings['services']['recaptcha_theme'] ?>"> Theme</p>
			
	<input type="hidden" name="module" value="services">		
	
	<p><input type="submit" name="Save" value="Save"></p>

</div>

</form>

<script type="text/javascript">
$(document).ready(function()
{
	doPlaceholder('.repeating_title', "How to Grow a Garden");
	doPlaceholder('.repeating_body', "Ever wanted to grow your own fruits and vegetables?");
	doPlaceholder('#more_details', "Additional details about your class");
	
	// Add Category
	$('[name=email_protocol]').change(function()
	{	
		if($(this).val() == 'smtp')
		{
			$('#email_smtp_options').fadeIn('normal');
		}
		else
		{
			$('#email_smtp_options').fadeOut('normal');
			$('[name=smtp_host]').val('');
			$('[name=smtp_user]').val('');
			$('[name=smtp_pass]').val('');
			$('[name=smtp_port]').val('');						
		}
	});

});
</script>

<?= $shared_ajax ?>