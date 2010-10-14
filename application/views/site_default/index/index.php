<h1><?= $page->title ?></h1>

<?= $page->content ?>

<?php if ((config_item('comments_enabled') == 'TRUE') && ($page->comments_allow != 'N')): ?>
<div id="comments">
	<h3><span id="comments_count"><?= $comments_title ?></span> Comments</h3>
	
	<ol id="comments_list">
		<?php if($comments_list) echo $comments_list ?>
	</ol>
	<?= $comments_write ?>
</div>
<?php endif; ?>