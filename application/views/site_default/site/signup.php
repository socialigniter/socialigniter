<h1>Signup</h1>

<div id="info_message"><?= $message; ?></div>

<?php if (config_item('users_signup') == 'TRUE'): ?>

<form method="post" action="<?= base_url()."signup"; ?>">
	<table border="0" cellpadding="0" cellspacing="0">
	<tr>
	  <td>Name:</td>
	  <td><input type="text" name="name" value="<?= set_value('name', $name) ?>"></td>
	</tr>
	<tr>
	  <td>Email:</td>
	  <td><input type="text" name="email" value="<?= set_value('email', $email) ?>"></td>
	</tr>
	<tr>  
	  <td>Password:</td>
	  <td><input type="password" name="password" value="<?= set_value('password', $password) ?>"></td>
	</tr>
	<tr>  
	  <td>Confirm Password:</td>
	  <td><input type="password" name="password_confirm" value="<?= set_value('password_confirm', $password_confirm) ?>"></td>
	</tr>
	<tr> 
	  <td colspan="2"><input type="submit" name="submit" value="Signup"></td>
	</tr>
	</table>	
</form>

<?= $this->social_igniter->get_social_logins('<div class="social_login">', '</div>'); ?>

<?php else: ?>

<p>Sorry user signup is currently turned off</p>

<?php endif; ?>

<script type="text/javascript">
$(document).ready(function()
{
	doPlaceholder('[name=name]', "Joe Smith");
	doPlaceholder('[name=email]', 'your@email.com');
	doPlaceholder('[name=password]', 'password');
	doPlaceholder('[name=password_confirm]', 'password');
});
</script>