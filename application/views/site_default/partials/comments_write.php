<form action="<?= $comments_post ?>" method="post" name="comments_write_form" id="<?= $comments_write_form ?>">
	<?php if($this->social_auth->logged_in()): ?>
	<a href="<?= $link_profile ?>"><span class="comment_thumbnail"><img src="<?= $profile_image ?>" border="0" /></span></a>
	<span class="comment_write">
		<a href="<?= $link_profile ?>"><?= $profile_name; ?></a> says:
	<?php else: ?>
	<span class="comment_thumbnail"><img src="<?= $profile_image ?>" border="0" /></span>
	<span class="comment_write">
		<span id="comment_name_email">
		<input type="text" name="comment_name" id="comment_name" value="<?= $comment_name; ?>">
		<input type="text" name="comment_email" id="comment_email" value="<?= $comment_email; ?>">
		<div id="comment_email_error" class="error"></div>
		</span>
	<?php endif; ?>
		<textarea name="comment_write_text" id="comment_write_text" rows="3" cols="38"><?= $comment_write_text; ?></textarea>
		<?= $recaptcha ?>
		<div id="comment_error" class="error"><?= $comment_error ?></div>
		<input type="submit" id="comment_submit" value="Comment">
	</span>
	<div class="clear"></div>
	<input type="hidden" name="comment_write_url" id="comment_write_url" value="<?= $comments_post ?>">
	<input type="hidden" name="reply_to_id" id="reply_to_id" value="<?= $reply_to_id ?>">
	<input type="hidden" name="content_id" value="<?= $content_id ?>">
	<input type="hidden" name="comment_type" value="<?= $comment_type ?>">		
	<input type="hidden" name="geo_lat" id="geo_lat" value="<?= $geo_lat ?>">
	<input type="hidden" name="geo_long" id="geo_long" value="<?= $geo_long ?>">
	<input type="hidden" name="geo_accuracy" id="geo_accuracy" value="<?= $geo_accuracy ?>">
</form>
<div class="clear"></div>