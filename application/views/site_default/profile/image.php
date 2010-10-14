<div id="content">
	<div class="user_profile_content_left">
	
		<h1><a class="user_profile_name" href="<?= base_url().'profile/'.$username ?>"><?= $name ?></a></h1>
	
		<a class="user_profile_image" href="<?= base_url().'profile/'.$username ?>"><?= display_image("", "", asset_profiles().$user_id."/original_", $image, asset_profiles()."bigger_nopicture.png", $name."'s profile picture") ?></a>
	
	</div>
</div>