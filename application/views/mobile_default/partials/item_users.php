<li class="item_manage" id="item_<?= $user_id; ?>" rel="users">

		<span class="item_manage_avatar"><a href="<?= $profile ?>"><img src="<?= $avatar ?>"></a></span>
		<span class="item_manage_name"><a href="<?= $profile ?>"><?= $name ?></a></span>
		
		<span class="item_alerts" id="item_alerts_<?= $user_id ?>"><?= $user_alerts ?></span>
		<div class="clear"></div>
		
		<span class="item_manage_user_meta">
			<span class="item_manage_last_login">Last seen <?= $last_login ?></span>
			<span class="item_manage_publish">Joined <?= $created_on ?></span>
		</span>

		<ul class="item_actions" rel="timeline">
			<li><a class="item_<?= $user_state ?>" href="<?= $user_state ?>" rel="users" id="item_action_<?= $user_state.'_'.$user_id ?>"><span class="actions action_<?= $user_state ?>"></span> <?= ucwords($user_state) ?></a></li>
			<li><a class="item_edit" href="<?= $user_edit ?>" id="item_action_edit_<?= $user_id ?>"><span class="actions action_edit"></span> Edit</a></li>
			<li><a class="item_delete" href="<?= $user_delete ?>" id="item_action_delete_<?= $user_id ?>"><span class="actions action_delete"></span> Delete</a></li>
		</ul>

	<div class="clear"></div>	
	<span class="item_separator"></span>		
</li>