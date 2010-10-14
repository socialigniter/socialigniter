<h3>Password</h3>

<form method="post" action="<?= base_url()."settings/password"; ?>">
	<table border="0" cellpadding="0" cellspacing="0">
    <tr>
		<td valign="top">Current:</td>
		<td>
			<input type="password" name="old_password" size="32" value="<?= set_value('old_password', $old_password) ?>">
			<p><a href="<?= base_url() ?>login/forgot_password">Forgot your password?</a></p>
		</td>
	</tr>
    <tr>
		<td>New:</td>
		<td><input type="password" name="new_password" size="32" value="<?= set_value('new_password', $new_password) ?>"></td>
	</tr>
    <tr>	
		<td>Confirm:</td>
		<td><input type="password" name="new_password_confirm" size="32" value="<?= set_value('new_password_confirm', $new_password_confirm) ?>"></td>
	</tr>
    <tr>		
		<td colspan="2"><input type="submit" value="Change" /></td>
  	</tr>			
	</table>
</form>
