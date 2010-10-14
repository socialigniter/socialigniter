<li class="item" id="item_<?= $item_id; ?>" rel="timeline">
	<span class="item_thumbnail">
		<a href="<?= $item_profile ?>"><img src="<?= $item_avatar ?>" /></a>
	</span>
	<span class="item_content">
		<b><a href="<?= $item_profile ?>"><?= $item_contributor ?></a></b> <?= $item_content ?>		
		<div class="clear"></div>
		<span class="item_meta"><?= $item_date ?></span>
	</span>
	
	<span class="<?= $item_type ?>"></span>

	<div class="clear"></div>
	<ul class="item_actions" rel="timeline">
		<?php if (config_item('home_comments_allow') == 'TRUE') { ?>
		<li><a href="<?= $item_comment ?>"><span class="item_actions action_comment"></span> Comment</a></li>
		<?php } if (config_item('home_share') == 'TRUE') { ?>
		<li><a href="#"><span class="item_actions action_share"></span> Share</a></li>
		<?php } if (config_item('home_like') == 'TRUE') { ?>
		<li><a href="#"><span class="item_actions action_like"></span> Like</a></li>
		<?php } if ($item_user_id == $logged_user_id) { ?>
		<li><a class="item_delete" href="<?= $item_delete; ?>" id="item_action_delete_<?= $item_id ?>"><span class="item_actions action_delete"></span> Delete</a></li>
		<?php } ?>
	</ul>
	<div class="clear"></div>
	<span class="item_separator"></span>
</li>