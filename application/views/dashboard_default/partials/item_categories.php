<li class="item_manage" id="item_<?= $item_id; ?>">

	<span class="item_manage_title"><a href="<?= $title_link ?>"><?= $title ?></a></span>	
	<div class="clear"></div>
	
	<span class="item_manage_categories_meta">
		<span class="item_manage_content"><?= $contents_count ?></span>
		<span class="item_manage_publish"><?= $publish_date ?></span>
	</span>

	<ul class="item_actions" rel="manage">						
		<li><a class="item_edit_category" href="<?= $item_edit ?>"><span class="actions action_edit"></span> Edit</a></li>
		<li><a class="item_delete_category" href="<?= $item_delete ?>" data-category_id="<?= $item_id ?>"><span class="actions action_delete"></span> Delete</a></li>
	</ul>

	<div class="clear"></div>	
</li>