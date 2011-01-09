<?php if($this->social_auth->logged_in()) { ?>
	
<div class="sidebar_section">
	<p><a href="<?= base_url()."profile/".$this->session->userdata('username'); ?>"><?= display_image("", "",  asset_profiles().$this->session->userdata('user_id')."/medium_", $this->session->userdata('image'), asset_profiles()."medium_nopicture.png", "") ?></a></p>
	<p><a href="<?= base_url()."profile/".$this->session->userdata('username'); ?>"><?= $this->session->userdata('name'); ?></a></p>
</div>

<div class="separator"></div>

<h2>Messages</h2>
<ul>
<?php if(in_array('twitter', $this->config->item('social_connections'))){ ?>
  <li><a href="home/twitter">Twitter</a></li>
<?php } if(in_array('facebook', $this->config->item('social_connections'))){ ?>
  <li><a href="#">Facebook</a></li>  
<?php } ?>
</ul>

<?php } else { ?>

	<?php if ($this->uri->segment(1) != 'login') $this->load->view($this->config->item('theme').'/partials/login'); ?>

<?php } ?>