<html>
<body>

	<p>Hi <?= $name ?>,</p>
	
	<p>Thanks for signing up on <?= config_item('site_title') ?>.</p>
			
	<p><a href="<?= base_url().'login/'.$pre.'/'.$email.'/"'; ?>">LOGIN NOW</a></p>	
	
	<p>Let the adventure begin!</p>
	
	<p>-<?= config_item('site_title') ?></p>
	
	<p><a href="<?= base_url() ?>"><?= base_url() ?></a></p>

</body>
</html>