<div class="vcard">
<div id="profile_image">
	<img class="photo" src="<?= $this->social_igniter->profile_image($user_id, $image, $gravatar, 'large'); ?>" border="0">
</div>

<div id="profile_info">
	<ul>
		<li><h1 class="fn"><?= $name ?></h1></li>
		<li><h3>@<?= $username ?></h3></li>
		<?= display_value("li", "", "", $company); ?>
		<?= display_value("li", "", "", $location); ?>
		<?= display_value("li", "", "", display_link("", "", $url, $url)); ?>		
		<?= display_value("li", "", "", $bio); ?>
	</ul>	
</div>
<div class="clear"></div>
<div class="norm_separator"></div>

<ol id="profile_feed">
	<?= $timeline_view ?>
</ol>
<div class="clear"></div>

<?php if ($timeline_count > 8): ?>
<input type="button" id="profile_feed_see_more" value="See More">
<?php endif; ?>
</div>