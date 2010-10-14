<form method="post" action="<?= base_url()."site/themes";?>">

<h3>Theme</h3>
<?php foreach($site_themes as $theme): ?>

	<p>
	<?php if (config_item('site_theme') == $theme): ?>
		<img src="<?= $views.$theme ?>/assets/thumbnail.jpg" border="0" />
		<img src="<?= $shared_images ?>green_check_24.png" border="0" />
	<?php else: ?>
		<a class="select_theme" href="#"><img src="<?= $views.$theme ?>/assets/thumbnail.jpg" border="0" /></a>	
		<img src="<?= $shared_images ?>alert_24.png" border="0" />	
	<?php endif; ?>
	</p>

<?php endforeach; ?>

<div class="content_line"></div>	

<h3>Dashboard Theme</h3>
<?php foreach($dashboard_themes as $theme): ?>

	<p>
	<?php if (config_item('dashboard_theme') == $theme): ?>
		<img src="<?= $views.$theme ?>/assets/thumbnail.jpg" border="0" />
		<img src="<?= $shared_images ?>green_check_24.png" border="0" />
	<?php else: ?>
		<a class="select_theme" href="#"><img src="<?= $views.$theme ?>/assets/thumbnail.jpg" border="0" /></a>	
		<img src="<?= $shared_images ?>alert_24.png" border="0" />	
	<?php endif; ?>
	</p>

<?php endforeach; ?>

<div class="content_line"></div>

<h3>Mobile Theme</h3>

<?php foreach($mobile_themes as $theme): ?>

	<p>
	<?php if (config_item('mobile_theme') == $theme): ?>
		<img src="<?= $views.$theme ?>/assets/thumbnail.jpg" border="0" />
		<img src="<?= $shared_images ?>green_check_24.png" border="0" />
	<?php else: ?>
		<a class="select_theme" href="#"><img src="<?= $views.$theme ?>/assets/thumbnail.jpg" border="0" /></a>
		<img src="<?= $shared_images ?>alert_24.png" border="0" />	
	<?php endif; ?>
	</p>

<?php endforeach; ?>

<input type="text" name="site_theme" id="site_theme" value="<?= config_item('site_theme') ?>">
<input type="text" name="dashboard_theme" id="dashboard_theme" value="<?= config_item('dashboard_theme') ?>">
<input type="text" name="mobile_theme" id="mobile_theme" value="<?= config_item('mobile_theme') ?>">

<p><input type="submit" value="Update" /></p>

</form>