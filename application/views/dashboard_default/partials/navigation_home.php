<h2 class="content_title"><img src="<?= $dashboard_assets ?>icons/home_32.png"> Home</h2>
<ul class="content_navigation">
	<?= navigation_list_btn('home', 'Public') ?>
	<?= navigation_list_btn('home/friends', 'Friends') ?>
	<?= navigation_list_btn('home/replies', '@ Replies') ?>
	<?= navigation_list_btn('home/likes', 'Likes') ?>
	<?= $modules_navigation_core ?>	
</ul>