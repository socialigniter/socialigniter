<div id="signup_ajax">
<h1>Signup</h1>

<p>Please enter your information.</p> 

<form method="post" action="<?= base_url()."login/signup_ajax"; ?>">
	<table border="0" id="signup_ajax_form" cellpadding="0" cellspacing="0">
	<tr>
	  <td>Name:</td>
	  <td><input type="text" name="name" value="<?= set_value('name', $name) ?>">
	  	<?= form_error('name', '<div class="error">*', '</div>'); ?>
	  </td>
	</tr>
	<tr>
	  <td>Email:</td>
	  <td><input type="text" name="email" value="<?= set_value('email', $email) ?>">
	  	<?= form_error('email', '<div class="error">*', '</div>'); ?>
	  
	  </td>
	</tr>
	<tr>  
	  <td>Password:</td>
	  <td><input type="password" name="password" value="<?= set_value('password', $password) ?>">
	  	<?= form_error('password', '<div class="error">*', '</div>'); ?>	  
	  </td>
	</tr>
	<tr> 
	  <td colspan="2"><input type="submit" name="submit" value="Signup"></td>
	</tr>
	<tr>
	  <td colspan="2">
		<?= $this->social_igniter->get_social_logins('<div class="social_login">', '</div>'); ?>
	  </td>
	</tr>
	</table>	
</form>
