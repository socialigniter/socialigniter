<li class="item" id="item_<?= $item_id; ?>" rel="timeline">
	<span class="item_thumbnail">
		<a href="<?= $item_profile ?>"><img src="<?= $item_avatar ?>" /></a>
	</span>
	<div class="item_content">
		<b><a href="<?= $item_profile ?>"><?= $item_contributor ?></a></b> <?= $item_content ?>		
		<div class="clear"></div>
		<span class="item_meta"><?= $item_date ?></span>
		
		
		<ul class="item_actions" rel="timeline">
			<?php if (config_item('home_comments_allow') == 'TRUE') { ?>
			<li><a class="item_comment" href="<?= $item_comment ?>"><span class="item_actions action_comment"></span> Comment</a></li>
			<?php } if (config_item('home_share') == 'TRUE') { ?>
			<li><a href="#"><span class="item_actions action_share"></span> Share</a></li>
			<?php } if (config_item('home_like') == 'TRUE') { ?>
			<li><a href="#"><span class="item_actions action_like"></span> Like</a></li>
			<?php } if ($item_user_id == $logged_user_id) { ?>
			<li><a class="item_delete" href="<?= $item_delete; ?>" id="item_action_delete_<?= $item_id ?>"><span class="item_actions action_delete"></span> Delete</a></li>
			<?php } ?>
		</ul>
		<div class="clear"></div>
		
		<ol class="comment_list">
			<!-- dynamically added -->
			<li id="comment_1">
				<div class="comment">
					<p><span class="comment_author"><a href="#link-to-userprofile">Brennan Novak</a></span> You are so dumb, you are really dumb</p>
				</div>
				<p class="comment_meta"><span class="comment_date">2 minutes ago</span></p>
			</li>
			<li id="comment_2">
				<div class="comment">
					<p><span class="comment_author"><a href="#link-to-userprofile">Oscar Godson</a></span> I'm gunna to find you! I'm gunna to find you! I'm gunna to find you! I'm gunna to find you! I'm gunna to find you! </p>
					<p>I'm gunna to find you! I'm gunna to find you! I'm gunna to find you! I'm gunna to find you! I'm gunna to find you! </p>
				</div>
				<p class="comment_meta"><span class="comment_date">16 seconds ago</span></p>
			</li>
		</ol>
		
		<div class="comment_form">
			<form method="post" class="item_comment_form" name="item_comment_form" action="<?= base_url() ?>api/comments/write">
				<textarea name="comment" class="comment_write_text"></textarea>
				<div class="clear"></div>
				<input type="hidden" name="reply_to_id" id="reply_to_id" value="0">
				<input type="hidden" name="content_id" value="<?= $item_content_id; ?>">
				<input type="hidden" name="geo_lat" id="geo_lat" value="">
				<input type="hidden" name="geo_long" id="geo_long" value="">
				<input type="hidden" name="geo_accuracy" id="geo_accuracy" value="">				
				<input type="submit" id="comment_submit" value="Comment">		
			</form>
			<div class="clear"></div>
		</div>
		
	</div>
	
	<span class="<?= $item_type ?>"></span>

	<div class="clear"></div>
	
	
	<span class="item_separator"></span>
	
</li>