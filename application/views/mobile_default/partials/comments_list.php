<li class="<?= $sub ?>comment" id="comment-<?= $comment_id ?>">
	<a href="<?= $profiles.$comment->username ?>"><span class="<?= $sub ?>comment_thumbnail"><img src="<?= $this->social_igniter->profile_image($comment->user_id, $comment->image, $comment->gravatar) ?>" border="0" /></span></a>
	<span class="<?= $sub ?>comment">
		<a href="<?= $profiles.$comment->username ?>"><?= $comment->name ?></a> <?= $comment_text ?>
		<span class="<?= $sub ?>comment_date"><?= format_datetime(config_item('comments_date_style'), $comment->created_at) ?></span>
		<ul class="comment_actions">
			<?php if (config_item('comments_reply') == 'TRUE'): ?>
			<li><a href="#<?= $comments_write_form ?>" id="reply-<?= $reply_id ?>" class="comment_reply"><span class="item_actions action_reply"></span> Reply</a></li>
			<?php endif; if ($item_can_modify): ?>
			<li><a href="<?= base_url().'api/comments/destroy/id/'.$comment_id ?>" id="delete-<?= $comment_id ?>" class="comment_delete"><span class="item_actions action_delete"></span> Delete</a></li>
			<?php endif; ?>
		</ul>
		<div class="clear"></div>
	</span>
	<div class="clear"></div>
	<?php if (($sub == '') && (config_item('comments_reply') == 'TRUE')): ?>
	<ul id="comment-replies-<?= $comment_id ?>"></ul>
	<?php endif; ?>
</li>