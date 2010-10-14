	<li><a class="sidebar_icon" href="<?= base_url() ?>users"><img src="<?= $dashboard_assets ?>icons/users_24.png"><span>Users</span></a></li>
	<?php if ($level == 1): ?>
	<li><a class="sidebar_icon" href="<?= base_url() ?>settings/modules"><img src="<?= $dashboard_assets ?>icons/settings_24.png"><span>Settings</span></a></li>
	<li><a class="sidebar_icon" href="<?= base_url() ?>site"><img src="<?= $dashboard_assets ?>icons/site_24.png"><span>Site</span></a></li>
	<?php endif; ?>