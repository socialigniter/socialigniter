<h2 class="content_title"><img src="<?= $dashboard_assets ?>icons/pages_32.png"> Pages</h2>
<ul class="content_navigation">
	<?= navigation_list_btn('home/pages', 'Recent') ?>
	<?php if ($logged_user_level_id <= config_item('pages_create_permission')): ?>
	<?= navigation_list_btn('home/pages/create', 'Create') ?>
	<?= navigation_list_btn('home/pages/manage', 'Manage', $this->uri->segment(4)) ?>
	<?php endif; ?>
</ul>