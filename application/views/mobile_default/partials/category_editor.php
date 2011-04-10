<div class="modal_wrap">
	<h2 id="editor_title"></h2>
	<form id="new_category" action="post">
		<label for="category_name">Name</label>
		<input id="category_name" type="text" value="" name="category">
		<p id="category_slug" class="slug_url"></p>	
		<label for="category_description">Description</label>
		<textarea name="description" id="category_description" rows="2"></textarea>
		<label for="category_parent">Parent Category</label>
		<select name="parent_id" id="category_parent_id">
			<option value="0">--None--</option>
		</select>
		<label for="category_access">Access</label>
		<?= form_dropdown('access', config_item('access'), '', 'id="category_access"') ?>
		<input type="hidden" name="site_id" value="<?= config_item('site_id') ?>">
		<br>
		<input type="submit">
	</form>
</div>