<h2 class="content_title"><img src="<?= $dashboard_assets ?>icons/home_32.png"> Home</h2>
<!-- 
<ul class="content_navigation">
	<?= navigation_list_btn('home', 'All') ?>
	<?= navigation_list_btn('home/friends', 'Friends') ?>
	<?= navigation_list_btn('home/likes', 'Likes') ?>
</ul>
-->
<p id="home_groups_nav"><span class="actions action_group"></span> Groups <?= form_dropdown('group_id', $groups, $group_id, 'id="select_group_id"') ?></p>