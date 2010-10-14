<h3>Search</h3>

<form method="post" action="<?= base_url()."site/create";?>">
<table border="0" cellpadding="0" cellspacing="0">
<tr>
	<td>By name, username, email</td>	
	<td><input type="text" name="search_users" size="50" /></td>
	<td><input type="submit" value="Go" /></td>
</tr>
</table>
</form>

<h3>Newest</h3>

<table cellpadding="0" cellspacing="0" width="650">
	<?php foreach ($users as $user): ?>
		<tr>
			<td><?= $user['name']?></td>
			<td><?= $user['email'];?></td>
			<td><?= $user['description'];?></td>
			<td><a href="<?= base_url()."users/edit/".$user['user_id'] ?>"><img src="<?= $dashboard_assets ?>icons/edit_24.png" border="0" /></a></td>
			<td>
			<?php if ($user['active']): ?> 			
				<a href="<?= base_url().'users/deactivate/'.$user['user_id'] ?>"><img src="<?= $dashboard_assets ?>icons/green_check_24.png" border="0" /></a>
			<?php else : ?>
				<a href="<?= base_url().'users/activate/'.$user['user_id'] ?>"><img src="<?= $dashboard_assets ?>icons/alert_24.png" border="0" /></a>
			<?php endif ?>
			</td>
		</tr>
	<?php endforeach;?>
</table>