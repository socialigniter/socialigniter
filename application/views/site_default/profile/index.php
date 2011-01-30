<div id="profile_user_head">
	
	<div id="profile_user_thumb">
		<img src="<?= $this->social_igniter->profile_image($user_id, $image, $email, 'large'); ?>" border="0">
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
		<?= $timeline_view ?>
	</ol>
</div>	