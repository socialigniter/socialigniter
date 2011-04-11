<li class="item" id="item_<?= $item_id; ?>" rel="timeline">
	<div class="item_thumbnail">
		<a href="<?= $item_profile ?>"><img src="<?= $item_avatar ?>" /></a>
	</div>
	<div class="item_content">
		<span class="item_content_body">
			<b><a href="<?= $item_profile ?>"><?= $item_contributor ?></a></b> <?= $item_content ?>		
		</span>
		
		<?php if ($item_type): ?><span class="item_type<?= $item_type ?>"></span><?php endif; ?>
		
		<div class="clear"></div>
		<span class="item_meta"><?= $item_date ?></span>
			
		<ul class="item_actions" rel="timeline">
			<?php if ((config_item('home_comments_allow') == 'TRUE') && ($item_content_id)): ?>
			<li><a class="item_comment" href="#"><span class="actions action_comment"></span> Comment</a></li>
			<?php endif; ?>
			<?php if (config_item('home_share') == 'TRUE'): ?>
			<li><a href="#"><span class="actions action_share"></span> Share</a></li>
			<?php endif; ?>
			<?php if ((config_item('home_like') == 'TRUE') && ($item_content_id)): ?>
			<li><a href="#"><span class="actions action_like"></span> Like</a></li>
			<?php endif; ?>
			<?php if ($item_can_modify): ?>
			<li><a class="item_edit" href="<?= $item_edit; ?>" id="item_action_edit_<?= $item_id ?>"><span class="actions action_edit"></span> Edit</a></li>
			<li><a class="item_delete" href="<?= $item_delete; ?>" id="item_action_delete_<?= $item_id ?>"><span class="actions action_delete"></span> Delete</a></li>
			<?php endif; ?>
		</ul>
		<div class="clear"></div>
		
		<ol class="comment_list">
			<li id="comment_write">
				<div class="comment_form">
					<form method="post" class="item_comment_form" name="item_comment_form" action="<?= base_url() ?>api/comments/write">
						<img class="comment_thumb" src="<?= $item_comment_avatar; ?>">
						<textarea name="comment" class="comment_write_text"></textarea>
						<div class="clear"></div>
						<input type="hidden" name="reply_to_id" id="reply_to_id" value="0">
						<input type="hidden" name="content_id" value="<?= $item_content_id; ?>">
						<input type="hidden" name="geo_lat" id="geo_lat" value="">
						<input type="hidden" name="geo_long" id="geo_long" value="">
						<input type="submit" id="comment_submit" value="Comment">		
					</form>
					<div class="clear"></div>
				</div>			
			</li>
		</ol>		
	</div>
	<div class="clear"></div>	
	<span class="item_separator"></span>
</li>