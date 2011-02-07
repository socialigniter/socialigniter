	<li><a class="sidebar_icon" href="<?= base_url() ?>users"><img src="<?= $dashboard_assets ?>icons/users_24.png"><span>Users</span></a></li>
	<?php if ($logged_user_level_id == 1): ?>
	<li><a class="sidebar_icon" href="<?= base_url() ?>settings/api"><img src="<?= $dashboard_assets ?>icons/api_24.png"><span>API</span></a></li>
	<li><a class="sidebar_icon" href="<?= base_url() ?>settings/modules"><img src="<?= $dashboard_assets ?>icons/modules_24.png"><span>Modules</span></a></li>
	<li><a class="sidebar_icon" href="<?= base_url() ?>settings/site"><img src="<?= $dashboard_assets ?>icons/site_24.png"><span>Site</span></a></li>
	<?php endif; ?>