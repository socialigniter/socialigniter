<div id="content">
	<ol class="repeating_content" id="timeline">
		<?php
		if (!empty($user_timeline)) :
		foreach ($user_timeline as $status) : 
		?>
		<li class="status <?= $username ?>" id="status_<?= $status->status_id; ?>">
			<span class="status_thumbnail">
				<a href="<?= base_url()."profile/".$username ?>"><?= display_image("", "", asset_profiles().$user_id."/normal_", $image, asset_profiles()."normal_nopicture.png", "") ?> </a>
			</span>
			<span class="status_text">
				<b><a href="<?= base_url()."profile/".$username ?>"><?= $name ?></a></b> <?= text_linkify($status->text)  ?>
				<span class="status_meta"><?= standard_date("SIMPLE_TIME", $status->created_at) ?></span>
			</span>	
			<ul class="status_actions">
				<?php if ($user_id == $this->session->userdata('user_id')) { ?>
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
</div>
<div id="sidebar">
	<div class="mainInfo">
		<?= display_image("", "", asset_profiles().$user_id."/bigger_", $image, asset_profiles()."bigger_nopicture.png", $name."'s profile picture") ?> 
		<ul>
			<?= display_value("li", "", "", "", $name); ?>
			<?= display_value("li", "", "", "Location: ", $location); ?>
			<?= display_value("li", "", "", "Web: ", $url, $url, "_blank"); ?>		
			<?= display_value("li", "", "", "Bio: ", $bio); ?>
			<?= display_value("li", "", "", "Home: ", $home_base); ?>
		</ul>
	</div>
</div>