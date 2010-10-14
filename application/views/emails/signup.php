<html>
<body>

	<p>Hi <?= $name ?>,</p>
	
	<p>Thanks for signing up <?= $this->config->item('site_title'); ?></p>
	
	<p>Use the following login information for the future:</p>
	
	<p>Email Address:</p>
	
	<blockquote><?= $email ?></blockquote>
		
	<p><a href="<?= base_url() ?>login">Login now</a></p>
	
	<p>You can view your <a href="<?= base_url().'profile/'.$username ?>"> Your Profile</a></p>

	<p><a href="<?= base_url() ?>"><?= base_url() ?></a></p>

</body>
</html>