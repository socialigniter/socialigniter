<div class="modal_wrap" style="opacity:0;">
	<h2>Add Category</h2>
	<form id="new_category">
		<label for="category_name">Name</label>
		<input id="category_name" type="text" value="" name="category">
		<p class="slug_url"><!--'+base_url+current_module+'/--><span></span></p>
		<label for="category_parent">Parent Category</label>
		<select name="parent_id" id="category_parent">
			<option value="0">--None--</option>
		</select>
		<label for="category_access">Access</label>
		<select name="access" id="category_access">
			<!--'+$('[name=access]').html()+'\-->
		</select>
		<input type="hidden" name="site_id" value="1">
		<input type="hidden" name="module" value="blog">
		<input type="hidden" name="type" value="article">
		<input type="hidden" name="category_url" value="">
		<br>
		<input type="submit">
	</form>
</div>