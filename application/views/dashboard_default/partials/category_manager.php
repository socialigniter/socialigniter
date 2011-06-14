<form id="new_category" action="post">

	<p>Name<br>
	<input type="text" id="category_name" name="category" value="" class="input_large"></p>

	<p id="category_slug" class="slug_url"></p>

	<p>Description<br>
	<textarea name="description" id="category_description" rows="4" cols="72"></textarea></p>

	<p>Thumbnail<br>
	<input type="file" name="thumbnail" id="category_thumbnail" value="">
	</p>

	<p>Parent Category<br>
	<select name="parent_id" id="category_parent_id">
		<option value="0">--None--</option>
	</select></p>

	<p>Access<br>
	<?= form_dropdown('access', config_item('access'), '', 'id="category_access"') ?></p>

	<input type="hidden" name="site_id" value="<?= config_item('site_id') ?>">

</form>