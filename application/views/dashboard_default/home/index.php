<div id="home_apps">
	<h2>Apps</h2>
	<ul>
		<?php if(is_array($apps)): foreach($apps as $app): if (isset($app->launcher) AND ($app->launcher == 'yes')): ?>
			<li><a href="<?= base_url().$app->links->default ?>" class="app_button button_data"><img src="<?= base_url().'application/modules/'.$app->app.'/assets/'.$app->app.'_32.png' ?>"><?= $app->name ?></a></li>
		<?php endif; endforeach; else: ?>
			<li>Perhaps you should <a href="https://social-igniter.com/pages/apps">download some apps!</a></li>
		<?php endif; ?>
	</ul>
	<div class="clear"></div>

	<?php if ($logged_user_level_id == 1): ?>	
	<h2>Admin</h2>
	<ul>
		<li><a href="<?= base_url() ?>users" class="app_button button_data"><img src="<?= $dashboard_assets ?>icons/users_32.png"><span>Users</span></a></li>
		<li><a href="<?= base_url() ?>apps" class="app_button button_data"><img src="<?= $dashboard_assets ?>icons/installer_32.png"><span>Apps</span></a></li>
		<li><a href="<?= base_url() ?>settings/site" class="app_button button_data"><img src="<?= $dashboard_assets ?>icons/site_32.png"><span>Site</span></a></li>
		<li><a href="<?= base_url() ?>settings/api" class="app_button button_data"><img src="<?= $dashboard_assets ?>icons/api_32.png"><span>API</span></a></li>
	</ul>
	<?php endif; ?>	
</div>

<div id="home_activity">
	<h2>Activity</h2>
	<div id="home_activity_feed">
		<ul>
			<?php foreach($activity as $item): ?>
			<li><img class="activity_image" src="<?= $this->social_igniter->profile_image($item->user_id, $item->image, $item->gravatar, 'medium', 'dashboard_theme') ?>"> <?= $this->activity_igniter->render_item($item) ?></li>
			<?php endforeach; ?>
		</ul>
	</div>
</div>
<div class="clear"></div>

<style type="text/css">
.button_data				{ border: 1px solid #ececec; }
.button_data:hover			{ border: 1px solid #c3c3c3; border-radius: 5px; -webkit-border-radius: 5px; -moz-border-radius: 5px; background-color: #f6f5f5; background-image: -webkit-gradient(linear, left top, left bottom, from(#f6f5f5),to(#d7d6d6)); background-image: -webkit-linear-gradient(top, #f6f5f5, #d7d6d6); background-image: -moz-linear-gradient(top, #f6f5f5, #d7d6d6); background-image: -o-linear-gradient(top, #f6f5f5, #d7d6d6); background-image: -ms-linear-gradient(top, #f6f5f5, #d7d6d6); background-image: linear-gradient(top, #f6f5f5, #d7d6d6); filter: progid:DXImageTransform.Microsoft.gradient(GradientType=0,StartColorStr='#f6f5f5', EndColorStr='#d7d6d6'); }
.button_data:active			{ border: 1px solid #b3b3b3; color: #999999; background-color: #dadada; background-image: -webkit-gradient(linear, left top, left bottom, from(#dadada),to(#e9e9e9)); background-image: -webkit-linear-gradient(top, #dadada, #e9e9e9); background-image: -moz-linear-gradient(top, #dadada, #e9e9e9); background-image: -o-linear-gradient(top, #dadada, #e9e9e9); background-image: -ms-linear-gradient(top, #dadada, #e9e9e9); background-image: linear-gradient(top, #dadada, #e9e9e9); filter: progid:DXImageTransform.Microsoft.gradient(GradientType=0,StartColorStr='#dadada', EndColorStr='#e9e9e9'); }

#home_apps 					{ width: 510px; float: left; margin-right: 0px; }
#home_apps ul li			{ float: left; margin: 0px 25px 25px 0px; }
#home_apps a.app_button		{ width: 75px; height: 85px; display: block; text-align: center; color: #999999; font-size: 12px; }
#home_apps a.app_button:hover { text-decoration: none; color: #2078ce; }
#home_apps a.app_button img	{  display: block; margin: 15px auto 10px auto; }

#home_activity				{ width: 380px; float: left; }
#home_activity_feed			{ min-height: 300px; max-height: 800px; overflow-x: hidden; overflow-y: scroll; border-radius: 5px;  }
#home_activity_feed ul		{ margin: 10px 10px 10px 15px; }
#home_activity_feed ul li 	{ margin: 5px 0px 0px 0px; line-height: 21px; padding-bottom: 15px; background: url(/application/views/dashboard_default/assets/images/item_separator.png) left bottom repeat-x; }
#home_activity_feed img.activity_image { width: 24px; height: 24px; position: relative; top: 6px; left: 0px; margin-right: 6px; }

</style>