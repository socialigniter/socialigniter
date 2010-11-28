<div id="ticker">
	<h3><?= $site_tagline ?></h3>
</div>
<ul>
<?php foreach ($navigation_menu as $nav): ?>
	<?php if ($nav->type == 'page'): ?>
	<li><a class="dark_nav" href="<?= base_url().'pages/'.$nav->title_url ?>"><?= $nav->title ?></a></li>
	<?php else: ?>
	<li><a class="dark_nav" href="<?= base_url().$nav->title_url ?>"><?= $nav->title ?></a></li>
	<?php endif; ?>
<?php endforeach; ?>
	<li><a class="dark_nav" href="<?= base_url() ?>api">Api</a></li>
</ul>
<span id="copyright" class="dark_nav"><?= date('Y') ?> Social Igniter</span>