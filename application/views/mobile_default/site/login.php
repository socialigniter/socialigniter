<h1>Login</h1>

<form method="post" action="<?= base_url() ?>login">
<table border="0" cellpadding="0" cellspacing="0">
<tr>
  <td>Email</td>
  <td><input type="text" name="email" value="<?= set_value('email', $email) ?>"></td>
</tr>
<tr>  
  <td>Password</td>
  <td><input type="password" name="password" value="<?= set_value('password', $password) ?>"></td>
</tr>
<tr>
  <td>Remember</td> 
  <td><?= form_checkbox('remember', '1', TRUE, 'id="login_remember"');?> 
  <a href="<?= base_url()."forgot_password"; ?>">Forgot password?</a>      
  </td>
</tr>    
<tr>
  <td colspan="2">
  	<input type="submit" name="submit" value="Login">
  </td>
</tr>
<tr>
  <td colspan="2">

  </td>
</tr>
</table>
</form>

<?= $this->social_igniter->get_social_logins('<div class="social_login">', '</div>'); ?>

<script type="text/javascript">
$(document).ready(function()
{
	doPlaceholder('[name=email]', 'your@email.com');
	doPlaceholder('[name=password]', 'password');
});
</script>