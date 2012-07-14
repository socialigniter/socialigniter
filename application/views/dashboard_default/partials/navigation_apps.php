<h2 class="content_title"><img src="<?= $dashboard_assets ?>icons/installer_32.png"> Apps</h2>
<ul class="content_navigation">
	<?= navigation_list_btn('apps', 'Installed') ?> 
	<?= navigation_list_btn('apps/inactive', 'Inactive') ?>
	<?= navigation_list_btn('apps/find', 'Find') ?> 
	<?= navigation_list_btn('apps/create', 'Create') ?> 
</ul>