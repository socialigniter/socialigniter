<p><?= random_element($this->config->item('home_greeting')); ?></p>

<form method="post" action="<?= base_url() ?>status/update">
	<p><span id="character_count"></span></p>
	<p><textarea id="status_update" name="update" rows="3"></textarea></p>
	<p><?= $post_to_social ?></p>
	<p><input type="submit" name="post" value="Post" /></p>
	<input type="hidden" name="reply_to_status_id" value="" />
	<input type="hidden" name="reply_to_user_id" value="" />
	<input type="hidden" name="reply_to_username" value="" />
</form>

<ol class="repeating_content" id="timeline">
	<?php
	if (!empty($timeline)) :
	foreach ($timeline as $status) : 
	?>
	<li class="repeating_item <?= $status->username ?>" id="status_<?= $status->status_id; ?>">
		<span class="status_thumbnail">
			<a href="<?= base_url()."profile/".$status->username ?>"><?= display_image("", "", asset_profiles().$status->user_id."/normal_", $status->image, asset_profiles()."normal_nopicture.png", "") ?> </a>
		</span>
		<span class="status_text">
			<b><a href="<?= base_url()."profile/".$status->username ?>"><?= $status->name ?></a></b> <?= text_linkify($status->text) ?>
			<span class="status_meta"><?= human_date('SIMPLE', mysql_to_unix($status->created_at)) ?></span>
		</span>	
		<ul class="status_actions">
			<?php if ($status->user_id == $this->session->userdata('user_id')) { ?>
			<li><span class="status_actions delete"><a href="#">Delete</a></span></li>
			<?php } else { ?>
			<li><span class="status_actions reply"><a href="#">Reply</a></span></li>
			<?php } ?>
		</ul>
	</li>
	<?php endforeach; else : ?>
	<li>No updates from anyone :(</li>
	<?php endif; ?>
</ol>

<div class="clear"></div>
