<html>
<body>
	<h1>Reset Password</h1>
	<p>Someone has asked to reset the password for <?php echo $email;?> on <a href="<?php echo $site_url ?>" target="_blank"><?php echo $site ?></a></p>
	<p>If this was you, please click this link to <a href="<?php echo base_url().'reset_password/'. $forgotten_password_code ?>">Reset Your Password</a></p>
</body>
</html>