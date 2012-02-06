<div id="main_nav">
	<div class="wrap">
	<ul>	
	<?php foreach ($navigation_menu as $nav): if (($nav->type == 'page') && ($nav->details == 'site')): ?>
		<li><a class="nav" href="<?= base_url().'pages/'.$nav->title_url ?>"><?= $nav->title ?></a></li>
		<?php elseif (($nav->type == 'page') && ($nav->details == 'module_page')): ?>
		<li><a class="nav" href="<?= base_url().$nav->title_url ?>"><?= $nav->title ?></a></li>	
		<?php else: ?>
		<li><a class="nav" href="<?= base_url().$nav->title_url ?>"><?= $nav->title ?></a></li>
	<?php endif; endforeach; ?>
	</ul>
	</div>
</div>