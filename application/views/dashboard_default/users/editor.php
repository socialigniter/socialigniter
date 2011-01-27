<h3>Create User</h3>

<div id="message"><?= $message;?></div>

<form id="user_create" method="post" action="<?= base_url()."users/create";?>">

<table border="0" cellpadding="0" cellspacing="0">
<tr>
	<td>Level:</td>
	<td><select name="level">
		<?php foreach ($users_levels as $level) : ?>
		<option value="<?= $level->level ?>"><?= $level->level_name ?></option>
		<?php endforeach; ?>
	</select>
	</td>    	
</tr>
<tr>
	<td>Name:</td>
	<td><input type="text" name="name" value="<?= $name ?>"></td>
</tr>
<tr>
	<td>Username:</td>
	<td><input type="text" name="name" value="<?= $username ?>"></td>
</tr>
<tr>
	<td>Email:</td>
	<td><input type="text" name="email" value="<?= $email ?>"></td>
</tr>
<tr>
	<td>Password:</td>
	<td><input type="password" name="password" value=""></td>
</tr>
<tr>	
	<td>Confirm Password:</td>
	<td><input type="password" name="password_confirm" value=""></td>
</tr>		
<tr>		
	<td>Phone:</td>
	<td><input type="text" name="phone" value="<?= $phone ?>"></td>
</tr>
<tr>		
	<td>Company:</td>
	<td><input type="text" name="company" value="<?= $company ?>"></td>
</tr>
<tr>		
	<td>Location:</td>
	<td><input type="text" name="location" value="<?= $location ?>"></td>
</tr>
<tr>
	<td>Website:</td>
	<td><input type="text" name="url" value="<?= $url ?>"></td>
</tr>
<tr>		
	<td>Bio:</td>
	<td><input type="text" name="bio" value="<?= $bio ?>"></td>
</tr>
<tr>		
	<td colspan="2"><input type="submit" value="<?= $sub_title ?> User" /></td>
	</tr>
	</table>

</form>