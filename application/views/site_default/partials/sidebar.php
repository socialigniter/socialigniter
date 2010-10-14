<?php if($this->social_auth->logged_in()) { ?>
	
	<p>You are logged in</p>
		
<?php } else { ?>

	<?php if ($this->uri->segment(1) != 'login') $this->load->view($this->config->item('site_theme').'/partials/login'); ?>

<?php } ?>