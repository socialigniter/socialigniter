<html>
<body>

	<p>Hi <?= $name ?>,</p>
	
	<p>Thanks for signing up on <?= $this->config->item('site_title') ?>.</p>
			
	<p><a href="<?= base_url().'login/'.$pre.'/'.$email.'/"'; ?>">LOGIN NOW</a></p>	
	
	<p>Let the adventure begin!</p>
	
	<p>-<?= $this->config->item('site_title') ?></p>
	
	<p><a href="<?= base_url() ?>"><?= base_url() ?></a></p>

</body>
</html>