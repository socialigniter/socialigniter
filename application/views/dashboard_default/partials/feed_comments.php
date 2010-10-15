<li class="<?= $item_viewed ?>" id="item_<?= $item_id; ?>" rel="<?= $feed_type ?>">
	<span class="item_thumbnail">
		<a href="<?= $item_profile ?>"><img src="<?= $item_avatar ?>" /></a>
	</span>
	<span class="item_content_small">
		<b><a href="<?= $item_profile ?>"><?= $item_contributor ?></a></b> <span class="item_verb"><?= $item_verb ?></span> <span class="feed_content_view"><a href="<?= $item_view ?>"><?= $item_object ?></a></span><br>
		<?= $item_text ?>
		<span class="item_meta"><?= $item_date ?></span>
		<div class="clear"></div>
	</span>
	<span class="<?= $item_type ?>"></span>
	<?= $item_alerts ?>	
	<div class="clear"></div>
	<ul class="item_actions" rel="<?= $feed_type ?>">
		<li><a href="<?= $item_view ?>"><span class="item_actions action_link"></span> View</a></li>
		<li><a href="<?= $item_reply ?>"><span class="item_actions action_reply"></span> Reply</a></li>
		<?php if ($item_approval == 'A'): ?>
		<li><a class="item_approve" href="<?= $item_approve; ?>" id="item_action_approve_<?= $item_id ?>"><span class="item_actions action_approve"></span> Approve</a></li>
		<?php endif; ?>
		<li><a class="item_delete" href="<?= $item_delete; ?>" id="item_action_delete_<?= $item_id ?>"><span class="item_actions action_delete"></span> Delete</a></li>
	</ul>
	<div class="clear"></div>
	<span class="item_separator"></span>
</li>