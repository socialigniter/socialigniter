<?php if ($site_tagline): ?>
<div id="ticker">
	<h3><?= $site_tagline ?></h3>
</div>
<?php endif; ?>
<ul>
<?php foreach ($navigation_menu as $nav): ?>
	<?php if ($nav->type == 'page'): ?>
	<li><a href="<?= base_url().'pages/'.$nav->title_url ?>"><?= $nav->title ?></a></li>
	<?php else: ?>
	<li><a href="<?= base_url().$nav->title_url ?>"><?= $nav->title ?></a></li>
	<?php endif; ?>
<?php endforeach; ?>
	<li><a href="<?= base_url() ?>api">Api</a></li>
</ul>
<span id="copyright">&copy;<?= date('Y').' '.$site_title ?></span>