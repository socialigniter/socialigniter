<li class="<?= $item_viewed ?>" id="item_<?= $item_id; ?>" rel="<?= $feed_type ?>">
	<div class="item_thumbnail">
		<a href="<?= $item_profile ?>"><img src="<?= $item_avatar ?>" /></a>
	</div>
	<div class="item_content">
		<span class="item_content_body_small">
			<b><a href="<?= $item_profile ?>"><?= $item_contributor ?></a></b> <span class="item_verb"><?= $item_verb ?> <?= $item_article ?></span> <span class="feed_content_view"><a href="<?= $item_view ?>"><?= $item_object ?></a></span><br>			
			<?= $item_text ?>
		</span>
		<?php if ($item_type): ?><span class="item_type<?= $item_type ?>"></span><?php endif; ?>

		<span class="item_alerts" id="item_alerts_<?= $item_id ?>"><?= $item_alerts ?></span>
		<div class="clear"></div>	
		
		<span class="item_meta"><?= $item_date ?></span>
		
		<ul class="item_actions" rel="<?= $feed_type ?>">
			<li><a href="<?= $item_view ?>"><span class="actions action_link"></span> View</a></li>
			<li><a href="<?= $item_reply ?>"><span class="actions action_reply"></span> Reply</a></li>
			<?php if ($item_approval == 'A'): ?>
			<li><a class="item_approve" href="<?= $item_approve; ?>" rel="comments" id="item_action_approve_<?= $item_id ?>"><span class="actions action_approve"></span> Approve</a></li>
			<?php endif; ?>
			<li><a class="item_delete" href="<?= $item_delete; ?>" rel="comments" id="item_action_delete_<?= $item_id ?>"><span class="actions action_delete"></span> Delete</a></li>
		</ul>
	</div>
	<div class="clear"></div>
	<span class="item_separator"></span>
</li>