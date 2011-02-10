<li class="item" id="item_<?= $item_id; ?>" rel="timeline">
	<div class="item_thumbnail">
		<a href="<?= $item_profile ?>"><img src="<?= $item_avatar ?>" /></a>
	</div>
	<div class="item_content">
		<span class="item_content_body">
			<b><a href="<?= $item_profile ?>"><?= $item_contributor ?></a></b> <?= $item_content ?>		
		</span>
		
		<?php if ($item_type): ?><span class="item_type<?= $item_type ?>"></span><?php endif; ?>
		
		<div class="clear"></div>
		<span class="item_meta"><?= $item_date ?></span>
		
		<div class="clear"></div>		
	</div>
	<div class="clear"></div>	
	<span class="item_separator"></span>
</li>