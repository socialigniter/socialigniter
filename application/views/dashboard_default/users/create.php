<h3>Create User</h3>

<div id="infoMessage"><?= $message;?></div>

<form method="post" action="<?= base_url()."users/create";?>">

<table border="0" cellpadding="0" cellspacing="0">
<tr>
	<td>Level:</td>
	<td><select name="level">
		<?php foreach ($users_levels as $level) : ?>
		<option value="<?= $level->level ?>"><?= $level->name ?></option>
		<?php endforeach; ?>
	</select>
	</td>    	
</tr>
<tr>
	<td>Name:</td>
	<td><input type="text" name="name" value="<?= set_value('name', $name) ?>"></td>
</tr>
<tr>
	<td>Username:</td>
	<td><input type="text" name="name" value="<?= set_value('username', $username) ?>"></td>
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
	<td>Phone:</td>
	<td><input type="text" name="phone" value="<?= set_value('phone', $phone) ?>"></td>
</tr>
<tr>		
	<td>Company:</td>
	<td><input type="text" name="company" value="<?= set_value('company', $company) ?>"></td>
</tr>
<tr>		
	<td>Location:</td>
	<td><input type="text" name="detail_1" value="<?= set_value('detail_1', $detail_1) ?>"></td>
</tr>
<tr>
	<td>Website:</td>
	<td><input type="text" name="detail_2" value="<?= set_value('detail_2', $detail_2) ?>"></td>
</tr>
<tr>		
	<td>Bio:</td>
	<td><input type="text" name="detail_3" value="<?= set_value('detail_3', $detail_3) ?>"></td>
</tr>
<tr>		
	<td colspan="2"><input type="submit" value="Create User" /></td>
	</tr>
	</table>

</form>