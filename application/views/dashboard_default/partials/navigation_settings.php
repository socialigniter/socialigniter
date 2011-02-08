<?php if (is_uri_value($this->uri->segment(2), array('profile','account','password','mobile','connections'))): ?>
<h2 class="content_title"><img src="<?= $dashboard_assets ?>icons/users_32.png"> Your Settings</h2>
<ul class="content_navigation">
	<?= navigation_list_btn('settings/profile', 'Profile') ?>
	<?= navigation_list_btn('settings/account', 'Account') ?>
	<?= navigation_list_btn('settings/password', 'Password') ?>
	<?= navigation_list_btn('settings/mobile', 'Mobile') ?>
	<?= navigation_list_btn('settings/connections', 'Connections') ?>
</ul>
<?php elseif (is_uri_value($this->uri->segment(2), config_item('core_modules'))): ?>
<h2 class="content_title">
	<img src="<?= $dashboard_assets.'icons/'.$this->uri->segment(2) ?>_32.png"> <?= ucwords($this->uri->segment(2)) ?> Settings
</h2>
<ul class="content_navigation">
	 <?= navigation_list_btn('settings/modules', 'Back To Modules') ?>
</ul>
<?php elseif (is_uri_value($this->uri->segment(2), config_item('site_modules'))): ?>
<h2 class="content_title"><img src="<?= $dashboard_assets ?>icons/site_32.png">  Site</h2>
<ul class="content_navigation">
	<?= navigation_list_btn('settings/site', 'Settings') ?>
	<?= navigation_list_btn('settings/themes', 'Themes') ?>	
	<?= navigation_list_btn('settings/widgets', 'Widgets') ?>	
	<?= navigation_list_btn('settings/services', 'Services') ?>
</ul>
<?php elseif ($this->uri->segment(2) == 'api'): ?>
<h2 class="content_title">
	<img src="<?= $dashboard_assets ?>icons/api_32.png"> Api Settings
</h2>
<ul class="content_navigation">
	 <?= navigation_list_btn('settings/modules', 'Back To Modules') ?>
</ul>
<?php elseif ($this->uri->segment(2) != 'modules'): ?>
<h2 class="content_title">
	<img src="<?= $modules_assets.$this_module ?>_32.png"> <?= ucwords(url_word_parser('_', $this_module)) ?> Settings
</h2>
<ul class="content_navigation">
	 <?= navigation_list_btn('settings/modules', 'Back To Modules') ?>
</ul>
<?php else: ?>
<h2 class="content_title">
	<img src="<?= $dashboard_assets ?>icons/modules_32.png"> Module Settings
</h2> 
<ul class="content_navigation">
</ul>
<?php endif; ?>