<form name="settings" method="post" action="<?= base_url() ?>settings/update" enctype="multipart/form-data">

<div class="content_wrap_inner">

	<h3>Images</h3>
	
	<table border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td>Large</td>
		<td><input type="checkbox" name="images_sizes_large" value="yes"></td>
		<td><input type="text" name="images_large_width" value="<?= $settings['media']['images_large_width'] ?>" size="3"> x <input type="text" name="images_large_height" value="<?= $settings['media']['images_large_height'] ?>" size="3"> px</td>
	</tr>
	<tr>
		<td>Medium</td>
		<td><input type="checkbox" name="images_sizes_medium" value="yes"></td>
		<td><input type="text" name="images_medium_width" value="<?= $settings['media']['images_medium_width'] ?>" size="3"> x <input type="text" name="images_medium_height" value="<?= $settings['media']['images_medium_height'] ?>" size="3"> px</td>
	</tr>
	<tr>
		<td>Small</td>
		<td><input type="checkbox" name="images_sizes_small" value="yes"></td>
		<td><input type="text" name="images_small_width" value="<?= $settings['media']['images_small_width'] ?>" size="3"> x <input type="text" name="images_small_height" value="<?= $settings['media']['images_small_height'] ?>" size="3"> px</td>
	</tr>
	<tr>
		<td>Original</td>
		<td><input type="checkbox" name="images_sizes_original" value="yes"></td>
		<td>Keep original uploaded image</td>		
	</tr>	
	</table>

	<p>Formats</p>
	<p><input type="text" name="images_formats" value="<?= $settings['media']['images_formats'] ?>" ></p>	
	
	<p><input type="text" name="images_max_size" value="<?= $settings['media']['images_max_size'] ?>" size="5"> max file size</p>
	
</div>	

<span class="item_separator"></span>


<div class="content_wrap_inner">

	<h3>Signups</h3>

	<div class="content_inner_top_right">	
		<p>Enabled
		<?= form_dropdown('signup', config_item('enable_disable'), $settings['users']['signup']) ?>
		</p>
	</div>	

	<p>ReCAPTCHA
	<?= form_dropdown('signup_recaptcha', config_item('enable_disable'), $settings['users']['signup_recaptcha']) ?></p>	
	
</div>	

<span class="item_separator"></span>

<div class="content_wrap_inner">

	<h3>Login</h3>

	<div class="content_inner_top_right">	
		<p>Enabled
		<?= form_dropdown('login', config_item('enable_disable'), $settings['users']['login']) ?>
		</p>
	</div>

	<p>ReCAPTCHA
	<?= form_dropdown('login_recaptcha', config_item('enable_disable'), $settings['users']['login_recaptcha']) ?></p>	
	
</div>	

<span class="item_separator"></span>

<div class="content_wrap_inner">

	<h3>Profiles</h3>

	<div class="content_inner_top_right">	
		<p>Enabled
		<?= form_dropdown('profile', config_item('enable_disable'), $settings['users']['profile']) ?>
		</p>
	</div>

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