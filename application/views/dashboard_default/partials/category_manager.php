<form id="category_manager" action="post">

	<label for="category_name">Name</label>
	<input id="category_name" type="text" value="<?= $category ?>" placeholder="Category Name" name="category">
	<p id="category_slug" class="slug_url"><?= $category_url ?></p>

	<label for="category_description">Description</label>
	<textarea name="description" id="category_description" placeholder="A description about your category" rows="4"><?= $description ?></textarea>

	<label for="category_parent">Parent Category</label>
	<select name="parent_id" id="category_parent_id">
		<option value="0">--None--</option>
	</select>

	<label for="category_access">Access</label>
	<?= form_dropdown('access', config_item('access'), $access) ?>

	<input type="hidden" name="site_id" value="<?= config_item('site_id') ?>">
	<div class="clear"></div>

</form>