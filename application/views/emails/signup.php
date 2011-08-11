<html>
<body>
	<p>Hi <?= $name ?>,</p>
	<p>Thanks for signing up <?= config_item('site_title') ?></p>
	<p>Use the following login information for the future:</p>
	<p>Email Address:</p>
	<blockquote><?= $email ?></blockquote>
	<p><a href="<?= base_url() ?>login">Login now</a></p>
	<p>You can view your <a href="<?= base_url().'profile/'.$username ?>"> Your Profile</a></p>
	<p><a href="<?= base_url() ?>"><?= config_item('site_title') ?></a></p>
</body>
</html>