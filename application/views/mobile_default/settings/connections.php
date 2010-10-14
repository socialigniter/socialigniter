<div class="content_norm_top"></div>	
<div class="content_norm_mid">

	<table border="0" cellpadding="0" cellspacing="0" width="450">
	<?php foreach($social_connections as $connection): $connection_set[$connection] = FALSE; ?>
	<tr>
		<td width="30"><img src="<?= asset_images().'icons/'.$connection ?>_24x24.png" border="0" /></td>
		<td><?= ucwords($connection) ?></td>
		<?php if ($user_connections): foreach($user_connections as $exists): if ($exists->type == $connection): $connection_set[$connection] = TRUE; ?>
		<td><?= $exists->connection_username ?></td>
		<td><a href="<?= base_url().'connections/'.$connection ?>_remove">Remove</a></td>
		<?php endif; endforeach; endif;	if (!$connection_set[$connection]): ?>
 		<td></td>
 		<td><a href="<?= base_url().'connections/'.$connection; ?>">Add</a></td>
 		<?php endif; ?>			
  	</tr>		
	<?php endforeach; ?>	
	</table>

</div>
<div class="content_norm_bot"></div>