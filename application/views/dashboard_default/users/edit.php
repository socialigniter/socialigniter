<h3>Edit User: </h3>

<div id="infoMessage"><?= $message;?></div>

<form method="post" action="<?= base_url()."users/create";?>">

<h3>User Info:</h3>

	<p>Level:<br />
	<select name="level">
		<?php foreach ($users_levels as $level) : ?>
		<option value="<?= $level->level ?>"><?= $level->level_name ?></option>
		<?php endforeach; ?>
	</select>
	</p>    	

	<p>Name:<br />
	<input type="text" name="name" value="<?= set_value('name', $name) ?>">
	</p>

	<p>Userame:<br />
	<input type="text" name="name" value="<?= set_value('username', $username) ?>">
	</p>
	
	<p>Email:<br />
	<input type="text" name="email" value="<?= set_value('email', $email) ?>">
	</p>

	<p>Password:<br />
	<input type="text" name="password" value="<?= set_value('password', $password) ?>">
	</p>
	
	<p>Confirm Password:<br />
	<input type="text" name="password_confirm" value="<?= set_value('password_confirm', $password_confirm) ?>">
	</p>		

<h3>User Details:</h3>
	
	<p>Phone:<br />
	<input type="text" name="phone" value="<?= set_value('phone', $phone) ?>">
	</p>
	
	<p>Company:<br />
	<input type="text" name="company" value="<?= set_value('company', $company) ?>">
	</p>
	
	<p>Location:<br />
	<input type="text" name="detail_1" value="<?= set_value('detail_1', $detail_1) ?>">
	</p>
	

	<p>Website:<br />
	<input type="text" name="detail_2" value="<?= set_value('detail_2', $detail_2) ?>">
	</p>
	
	<p>Bio:<br />
	<input type="text" name="detail_3" value="<?= set_value('detail_3', $detail_3) ?>">
	</p>
	      
	<p><input type="submit" value="Create User" /></p>

</form>

