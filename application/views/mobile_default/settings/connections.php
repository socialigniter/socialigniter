<?php if ($social_connections): ?>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<?php foreach($social_connections as $social): $connection_set[$social->module] = FALSE; ?>
<tr>
	<td width="30"><img src="<?= base_url().'/application/modules/'.$social->module.'/assets/'.$social->module ?>_24.png" border="0" /></td>
	<td><?= ucwords($social->module) ?></td>
	<?php if ($user_connections): foreach($user_connections as $exists): if ($exists->module == $social->module): $connection_set[$social->module] = TRUE; ?>
	<td><a href="<?= $exists->url.$exists->connection_username ?>" target="_blank"><?= $exists->connection_username ?></a></td>
	<td width="80"><a class="button_75 button_basic_link" href="<?= base_url().'connections/delete/'.$exists->connection_id; ?>"><span>Remove</span></a></td>
	<?php endif; endforeach; endif;	if (!$connection_set[$social->module]): ?>
	<td></td>
	<td><a class="button_75 button_basic_link" href="<?= base_url().'connections/'.$social->module.'/add'; ?>"><span>Add</span></a></td>
	<?php endif; ?>
	</tr>		
<?php endforeach; ?>	
</table>
<?php else: ?>
<p>No social connections exist to add</p>
<?php endif; ?>