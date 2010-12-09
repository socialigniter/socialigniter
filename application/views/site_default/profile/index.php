<div id="profile_user_head">
	
	<div id="profile_user_thumb">
		<img src="<?= $this->social_igniter->profile_image($user_id, $image, $email, 'bigger'); ?>" border="0">
	</div>
	
	<div id="profile_user_info">
		<ul>
			<li><h1><?= $name ?></h1></li>
			<li><h3>@<?= $username ?></h3></li>
			<?= display_value("li", "", "", $location); ?>
			<?= display_value("li", "", "", display_link("", "", $url, $url)); ?>		
			<?= display_value("li", "", "", $bio); ?>
			<?= display_value("li", "", "", $home_base); ?>
		</ul>	
	</div>
	
	<div class="clear"></div>

</div>

<ul id="profile_tabs">
	<li><a href="#">Recent</a></li>
	<li><a href="#">Favorites</a></li>
	<li><a href="#">Photos</a></li>
</ul>

<div class="clear"></div>

<div id="profile_content">
	<ol id="timeline">
		<?php
		if (!empty($user_timeline)) :
		foreach ($user_timeline as $status) : 
		?>
		<li class="status <?= $username ?>" id="status_<?= $status->status_id; ?>">
			<span class="status_thumbnail">
				<a href="<?= base_url()."profile/".$username ?>"><?= display_image("", "", asset_profiles().$user_id."/normal_", $image, asset_profiles()."normal_nopicture.png", "") ?></a>
			</span>
			<span class="status_text">
				<?= text_linkify($status->text)  ?>
				<span class="status_meta"><?= standard_date("SIMPLE_TIME", $status->created_at) ?></span>
			</span>	
			<ul class="status_actions">
				<?php if ($user_id == $this->session->userdata('user_id')) { ?>
				<li><span class="status_actions delete"><a href="#">Delete</a></span></li>
				<?php } else { ?>
				<li><span class="status_actions reply"><a href="#">Reply</a></span></li>
				<?php } ?>
			</ul>
			<div class="clear"></div>	
		</li>
		<?php endforeach; else : ?>
		<li>No updates from <?= $name ?></li>
		<?php endif; ?>
	</ol>
</div>	