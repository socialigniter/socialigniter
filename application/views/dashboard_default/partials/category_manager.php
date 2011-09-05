<form id="category_editor" name="category_editor" method="post">

	<p>Category</p>
	<p><input type="text" id="category_name" name="category" value='<?= $category ?>' class="input_large"></p>
	<p id="category_slug" class="slug_url"><?= $category_url ?></p>

	<p>Description</p>
	<p><textarea name="description" id="category_description" rows="4" cols="72"><?= $description ?></textarea></p>

	<p>Thumbnail</p>
	<?php if ($thumb): ?>
	<p><img src="<?= $thumb ?>"></p>
	<?php endif; ?>
	<p><input type="file" name="thumbnail" id="category_thumbnail" value=""></p>

	<p>Parent Category</p>
	<p><?= form_dropdown('parent_id', $categories_dropdown, $parent_id) ?></p>

	<p>Access</p>
	<p><?= form_dropdown('access', config_item('access'), $access, 'id="category_access"') ?></p>

	<input type="hidden" name="details" value='<?= $details ?>'>
	<input type="hidden" name="site_id" value='<?= $site_id ?>'>

</form>