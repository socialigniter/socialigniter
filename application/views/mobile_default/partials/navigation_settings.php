<div class="content_norm_top"></div>	
<div class="content_norm_mid">
	<h1><?= display_image("", "",  asset_profiles().$this->session->userdata('user_id')."/small_", $this->session->userdata('image'), asset_profiles()."small_nopicture.png", "") ?> <?= $this->session->userdata('name'); ?>'s Settings</h1>
	
	<a href="<?= base_url() ?>settings/profile">Profile</a> | 
	<a href="<?= base_url() ?>settings/account">Account</a> | 
	<a href="<?= base_url() ?>settings/password">Password</a> | 
	<a href="<?= base_url() ?>settings/mobile">Mobile</a> | 
	<a href="<?= base_url() ?>settings/connections">Connections</a>
	
</div>
<div class="content_norm_bot"></div>