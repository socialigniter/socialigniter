<div id="content" class="wide">
	<div class='mainInfo'>
	
		<h1>Signup</h1>
		
		<div id="infoMessage"><?= $message;?></div>
		
	    <form method="post" action="<?= base_url()."login/signup"; ?>">
	 
		<p>Please enter your information.</p> 
	 
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
		<tr>
		  <td colspan="2">
		  	Or Signup With
		  	  <?= $this->social_igniter->twitter_connect('image'); ?>
			  <?= $this->social_igniter->facebook_connect('image'); ?>
		  </td>
		</tr>
		</table>	
		</form>
	      
	</div>
</div>