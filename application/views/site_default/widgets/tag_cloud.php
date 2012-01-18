<div class="widget_<?= $widget_region ?> widget_tags_tag_cloud" id="widget_<?= $widget_id ?>">
	<h2><?= $widget_title ?></h2>
	<?php foreach ($tags as $tag): ?>
		<a href="<?= base_url().'tags/'.$tag->tag_url ?>"><?= $tag->tag ?></a>, 
	<?php endforeach; ?>
</div>