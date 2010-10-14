<p>Drag to arrange navigation menu order</p>
<?php foreach ($pages as $page): ?>

<div class="page_manage_container">
	<div class="page_manage_icon">

		<div class="page_manage_buttons">
			<a href="<?= base_url().'pages/edit/'.$page->content_id ?>"><img src="<?= $dashboard_assets ?>icons/edit_24.png" border="0" alt="Delete" /></a>
			<?php if ($page->type == 'page'): ?> 
			<a href="<?= base_url().'pages/delete/'.$page->content_id ?>"><img src="<?= $dashboard_assets ?>icons/delete_24.png" border="0" alt="Delete" /></a>
			<?php endif; ?>
		</div>
	</div>

	<span class="page_manage_title"><?= $page->title ?></span>
</div>

<?php endforeach; ?>

<div class="clear"></div>