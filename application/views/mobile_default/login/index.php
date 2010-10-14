<div id="content" class="wide">
	<div class='mainInfo'>
	
		<h1>Login</h1>
	
		<p>Please login with your email address and password below.</p>
		
		<div id="infoMessage"><?= $message; ?></div>
		
	    <form method="post" action="<?= base_url()."login";?>">
	 	<table border="0" cellpadding="0" cellspacing="0">
	    <tr>
	      <td>Email:</td>
	      <td><input type="text" name="email" value="<?= set_value('email', $email) ?>"></td>
	    </tr>
	    <tr>  
	      <td>Password:</td>
	      <td><input type="password" name="password" value="<?= set_value('password', $password) ?>"></td>
	    </tr>
	    <tr>
	      <td colspan="2">
	      Remember Me: <?= form_checkbox('remember', '1', FALSE);?> 
		  &nbsp;&nbsp;<a href="<?= base_url()."login/forgot_password"; ?>">Forgot password?</a>      
	      </td>
	    </tr>     
	    <tr>
	      <td colspan="2">
	      	<input type="submit" name="submit" value="Login">
	      </td>
	    </tr>
		<tr>
		  <td colspan="2">
		  <?= $this->social_igniter->twitter_connect('image'); ?>
		  <?= $this->social_igniter->facebook_connect('image'); ?>
		  </td>
		</tr>
	    </table>
	    </form>
	
	</div>
</div>