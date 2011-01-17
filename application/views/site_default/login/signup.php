<h1>Signup</h1>

<?php if (config_item('users_signup') == 'TRUE'): ?>

<p>Please enter your information.</p> 

<div id="info_message"><?= $message; ?></div>

<form method="post" action="<?= base_url()."login/signup"; ?>">
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