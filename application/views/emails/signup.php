<html>
<body>
	<p>Hi <?= $name ?>,</p>
	<p>An account has been created for you on <?= config_item('site_title') ?>. Your username is <strong><?= $username ?></strong></p>
	<p>Use the following information to log in:</p>
	<p>Email Address: <strong><?= $email ?></strong></p>
	<p>Password: <strong><?= $password ?></strong></p>
	<p>To log in now go to <a href="<?= base_url() ?>login">this page</a></p>
	<p>You can <a href="<?= base_url().'settings/profile' ?>"> edit your profile</a> once logged in</p>
	<p>Sincerely,<br><a href="<?= base_url() ?>"><?= config_item('site_title') ?></a></p>
</body>
</html>