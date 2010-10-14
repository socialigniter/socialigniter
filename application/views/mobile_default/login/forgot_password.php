<div id="content" class="wide">
	<h1>Forgot Password</h1>
	<p>Please enter your email address so we can send you an email to reset your password.</p>
	
	<div id="infoMessage"><?php echo $message;?></div>
	
	<form method="post" action="<?= base_url()."login/forgot_password"; ?>">
	      <p>Email Address:<br />
	      <?php echo form_input($email);?>
	      </p>
	      <p><input type="submit" name="submit" value="Retrieve" /></p>
	</form>
</div>