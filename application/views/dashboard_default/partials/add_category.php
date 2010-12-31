<div class="modal_wrap">
	<h2>Add Category</h2>
	<form id="new_category">
		<label for="category_name">Name</label>
		<input id="category_name" type="text" value="" name="category">
		<p id="category_slug" class="slug_url"></p>
		
		<label for="category_description">Description</label>
		<textarea name="description" id="category_description" rows="2"></textarea>
		<label for="category_parent">Parent</label>
		<select name="parent_id" id="category_parent">
			<option value="0">--None--</option>
		</select>
		<label for="category_access">Access</label>
		<?= form_dropdown('access', config_item('access'), '', 'id="category_access"') ?>
		<input type="hidden" name="site_id" value="1">
		<input type="hidden" name="module" value="blog">
		<input type="hidden" name="type" value="article">
		<input type="hidden" name="category_url" value="">
		<br>
		<input type="submit">
	</form>
</div>