<?php if ($this->social_auth->logged_in()): ?>
<div id="header_avatar">	
	<a href="<?= $link_profile ?>"><img src="<?= $profile_image ?>" border="0" /></a> 
</div>
<ul id="header_logged">
	<li><a class="dark_nav" href="<?= $link_home ?>">Home</a></li>	
	<li><a class="dark_nav" href="<?= $link_profile ?>">Profile</a></li>	
	<li><a class="dark_nav" href="<?= $link_settings ?>">Settings</a></li>	
	<li><a class="dark_nav" href="<?= $link_logout ?>">Log Out</a></li>
</ul>
<?php else: ?>
<ul id="header_not_logged">
	<?php if (config_item('users_signup') == 'TRUE'): ?>
	<li><a class="dark_nav" href="<?= base_url().'login/signup' ?>">Sign Up</a></li>
	<?php endif; ?>
	<li><a class="dark_nav" href="<?= base_url().'login' ?>">Log In</a></li>
</ul>
<?php endif; ?>
<div class="clear"></div>
