<h1>Forgot Password</h1>
<p>Please enter your email address</p>

<p id="info_message"><?= $message; ?></p>

<form method="post" action="<?= base_url()."forgot_password"; ?>">
	<p><input type="text" name="email" value=""></p>	
	<p><input type="submit" name="submit" value="Retrieve" /></p>
</form>

<script type="text/javascript">
$(document).ready(function()
{
	doPlaceholder('[name=email]', 'your@email.com');
});
</script>