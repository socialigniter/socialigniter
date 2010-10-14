<form method="post" id="status_update" action="<?= base_url() ?>status/add">
	<p><textarea id="status_update_text" name="status_update"><?= $status_update ?></textarea></p>
	
	<div id="status_update_options">
		<?php if ($geo_locate): ?>
		<div id="status_update_geo">
			<a href="#" class="find_location" id="status_find_location"><span>Get Location</span></a>
			<?= $social_checkin ?>
		</div>
		<?php endif; ?>
		<?= $social_post ?>
		<div class="clear"></div>
	</div>

	<div id="status_update_post">
		<input type="submit" name="post" id="status_post" value="Share" />
		<span id="character_count"></span>
	</div>

	<input type="hidden" name="access" id="access" value="E" />
	<input type="hidden" name="latitude" id="latitude" value="" />
	<input type="hidden" name="longitude" id="longitude" value="" />
	<input type="hidden" name="accuracy" id="accuracy" value="" />

</form>