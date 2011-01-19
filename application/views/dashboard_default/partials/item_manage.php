<li class="<?= $item_viewed ?>" id="item_<?= $item_id; ?>" rel="manage">

		<span class="item_manage_type type_<?= $item_type ?>"></span>
		<span class="item_manage_title"><a href="<?= $title_link ?>"><?= $title ?></a></span>
		
		<?= $item_alerts ?>
		<div class="clear"></div>
		
		<span class="item_manage_meta">
			<span class="item_manage_comments"><span class="actions action_comment"></span><?= $comments_count ?></span>
			<span class="item_manage_publish"><?= $publish_date ?></span>
		</span>

		<ul class="item_actions" rel="timeline">
			<li><a href="#"><span class="actions action_share"></span> Share</a></li>
			<?php if ($item_approval == 'A'): ?>
			<li><a class="item_approve" href="<?= $item_approve ?>" rel="<?= $item_module ?>" id="item_action_approve_<?= $item_id ?>"><span class="actions action_approve"></span> Approve</a></li>
			<?php endif; ?>				
			<li><a class="item_edit" href="<?= $item_edit ?>" id="item_action_edit_<?= $item_id ?>"><span class="actions action_edit"></span> Edit</a></li>
			<li><a class="item_delete" href="<?= $item_delete ?>" id="item_action_delete_<?= $item_id ?>"><span class="actions action_delete"></span> Delete</a></li>
		</ul>

	<div class="clear"></div>	
	<span class="item_separator"></span>		
</li>