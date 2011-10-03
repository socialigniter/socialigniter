<li class="item_manage" id="item_<?= $item_id; ?>" rel="<?= $item_rel ?>">
	<span class="item_manage_title"><?= $item_title ?></span>	
	<div class="clear"></div>
	<span class="item_manage_meta_generic">
		<?= $item_meta ?>
	</span>
	<ul class="item_actions" rel="manage">
		<li><a class="item_edit" href="<?= $item_edit ?>" id="item_action_edit_<?= $item_id ?>"><span class="actions action_edit"></span> Edit</a></li>
		<li><a class="item_delete" href="<?= $item_delete ?>" id="item_action_delete_<?= $item_id ?>"><span class="actions action_delete"></span> Delete</a></li>
	</ul>
	<div class="clear"></div>	
</li>