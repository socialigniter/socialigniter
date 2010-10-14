<?php if($this->social_auth->logged_in()) { ?>
<div id="header_avatar">	
	<a href="<?= $link_profile ?>"><img src="<?= $profile_image ?>" border="0" /></a> 
</div>
<ul id="logged">
	<li><a href="<?= base_url()."home"; ?>">Home</a></li>	
	<li><a href="<?= base_url()."profile/".$this->session->userdata('username'); ?>">Profile</a></li>	
	<li><a href="<?= base_url()."settings/"; ?>">Settings</a></li>	
	<li><a href="<?= base_url().'login/logout'; ?>">Log Out</a></li>
</ul>
<?php } else { ?>
<ul id="not_logged">
	<li><a href="<?= base_url().'login/signup'; ?>">Sign Up</a></li>
	<li><a href="<?= base_url().'login'; ?>">Log In</a></li>
</ul>
<?php } ?>
<div class="clear"></div>