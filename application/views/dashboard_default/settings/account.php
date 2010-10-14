<h3>Account</h3>
	
<form method="post" action="<?= base_url()."settings/account"; ?>">
	<table border="0" cellpadding="0" cellspacing="0">
    <tr>
		<td>Username:</td>
		<td><input type="text" name="username" size="40" value="<?= set_value('username', $username) ?>"></td>
	</tr>
    <tr>
		<td>Email:</td>
		<td><input type="email" name="email" size="40" value="<?= set_value('email', $email) ?>"></td>
	</tr>
	<tr>
		<td>Home Base:</td>
		<td><?= form_dropdown('home_base', $home_base_array, $home_base); ?></td>
	</tr>
	<tr>
		<td>Geo Enable:</td>
		<td><?= form_checkbox($geo_enabled); ?> Add my geographic location to updates</td>
	</tr>
	<tr>
		<td class="txt_right">Language</td>
		<td><?= form_dropdown('language', config_item('languages'), $language); ?></td>		
	</tr>
	<tr>
		<td class="txt_right">Timezone</td>
		<td class="txt_left"><?= timezone_menu($time_zone); ?></td>
	</tr>	
  	<tr>
  		<td>Privacy:</td>
  		<td><?= form_checkbox($privacy); ?> Keep my updates and messages private</td>
  	</tr>
    <tr>		
		<td colspan="2"><input type="submit" value="Save" /></td>
  	</tr>  	
	</table>
</form>
