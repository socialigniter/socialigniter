<h1>Forgot Password</h1>
<p>Please enter your email address so we can send you an email to reset your password.</p>

<p id="info_message"><?= $message; ?></p>

<form method="post" action="<?= base_url()."login/forgot_password"; ?>">
	<p>Email Address:<br />
	<?php echo form_input($email);?>
	</p>
	<p><input type="submit" name="submit" value="Retrieve" /></p>
</form>