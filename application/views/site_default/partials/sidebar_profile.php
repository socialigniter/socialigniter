<?php if ($user_id != $logged_user_id): ?>
<h3>Connect</h3>
<p><img src="<?= $dashboard_assets ?>icons/users_24.png"><a class="button_basic_on" href="<?= $follow_url ?>"><span>Follow</span></a></p>
<p><img src="<?= $dashboard_assets ?>icons/messages_24.png"><a class="button_basic_on" href="<?= $message_url ?>"><span>Message</span></a></p>
<?php endif; ?>