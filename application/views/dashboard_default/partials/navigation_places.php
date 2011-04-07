<h2 class="content_title"><img src="<?= $dashboard_assets ?>icons/places_32.png"> Places</h2>
<ul class="content_navigation">
	<?= navigation_list_btn('home/places', 'Activity') ?>
	<?php if ($logged_user_level_id <= config_item('places_create_permission')): ?>	
	<?= navigation_list_btn('home/places/create', 'Create') ?>
	<?= navigation_list_btn('home/places/manage', 'Manage', $this->uri->segment(4)) ?>
	<?php endif; ?>	
</ul>