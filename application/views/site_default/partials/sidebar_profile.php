<?php if ($user_id != $logged_user_id): ?>
	<h3>Connect</h3>
	<p><img src="<?= $dashboard_assets ?>icons/users_24.png"><a class="button_basic_on" href="<?= $follow_url ?>"><span>Follow</span></a></p>
	<p><img src="<?= $dashboard_assets ?>icons/messages_24.png"><a class="button_basic_on" href="<?= $message_url ?>"><span>Message</span></a></p>
<?php else: ?>
	<h3><?= random_element(config_item('cool_salutations')) ?>,</h3>
	<p>This is your <?= random_element(config_item('cool_phrases')) ?> profile</p>
<?php endif; ?>
	<div class="profile_sidebar_separator"></div>

<?php if ($connections): ?>

	<h3>Connections</h3>
	
	<ul>
	<?php foreach ($connections as $connection): ?>
		<li><a class="profile_sidebar_icon" href="<?= $connection->url.$connection->connection_username ?>" target="_blank"> <img src="<?= base_url().'application/modules/'.$connection->module.'/assets/'.$connection->module ?>_24.png"> <?= $connection->connection_username ?></a></li>
	<?php endforeach; ?>
	</ul>
	
	<div class="profile_sidebar_separator"></div>

<?php endif; ?>	
	
	<h3>People</h3>
	<p>Thumbnails of friends</p>
