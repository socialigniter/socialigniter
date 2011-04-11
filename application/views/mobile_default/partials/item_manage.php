<li class="<?= $item_viewed ?>" id="item_<?= $item_id; ?>" rel="content">

	<span class="item_manage_type type_<?= $item_type ?>"></span>
	<span class="item_manage_title"><a href="<?= $title_link ?>"><?= $title ?></a></span>
	
	<span class="item_alerts" id="item_alerts_<?= $item_id ?>"><?= $item_alerts ?></span>
	<div class="clear"></div>
	
	<span class="item_manage_meta">
		<span class="item_manage_comments"><span class="actions action_comment"></span><?= $comments_count ?></span>
		<span class="item_manage_publish"><?= $publish_date ?></span>
	</span>

	<ul class="item_actions" rel="manage">
		<?php if ($item_approval == 'N'): ?>
		<li><a class="item_approve" href="<?= $item_approve ?>" rel="content" id="item_action_approve_<?= $item_id ?>"><span class="actions action_approve"></span> Approve</a></li>
		<?php endif; ?>							
		<li><a class="item_<?= $item_status ?>" href="<?= $item_status ?>" rel="content" id="item_action_<?= $item_status.'_'.$item_id ?>"><span class="actions action_<?= $item_status ?>"></span> <?= ucwords($item_status) ?></a></li>
		<li><a class="item_edit" href="<?= $item_edit ?>" id="item_action_edit_<?= $item_id ?>"><span class="actions action_edit"></span> Edit</a></li>
		<li><a class="item_delete" href="<?= $item_delete ?>" id="item_action_delete_<?= $item_id ?>"><span class="actions action_delete"></span> Delete</a></li>
	</ul>

	<div class="clear"></div>	
	<span class="item_separator"></span>		
</li>