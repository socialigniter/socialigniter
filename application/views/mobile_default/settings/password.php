<div class="content_norm_top"></div>	
<div class="content_norm_mid">

		<form method="post" action="<?= base_url()."settings/password"; ?>">
		<table border="0" cellpadding="0" cellspacing="0">
	    <tr>
			<td valign="top">Current Password:</td>
			<td>
				<input type="password" name="old_password" value="<?= set_value('old_password', $old_password) ?>"><br>
				<a href="<?= base_url() ?>login/forgot_password">Forgot your password?</a>
			</td>
		</tr>
	    <tr>
			<td>New Password:</td>
			<td><input type="password" name="new_password" value="<?= set_value('new_password', $new_password) ?>"></td>
		</tr>
	    <tr>	
			<td>Confirm New Password:</td>
			<td><input type="password" name="new_password_confirm" value="<?= set_value('new_password_confirm', $new_password_confirm) ?>"></td>
		</tr>
	    <tr>		
			<td colspan="2"><input type="submit" value="Change" /></td>
	  	</tr>			
		</table>
		</form>
	
</div>
<div class="content_norm_bot"></div>