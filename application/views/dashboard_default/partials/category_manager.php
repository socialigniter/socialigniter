<form id="category_editor" name="category_editor" method="post">

	<p>Category</p>
	<p><input type="text" id="category_name" name="category" value='<?= $category ?>' class="input_large"></p>
	<p id="category_slug" class="slug_url"><?= $category_url ?></p>

	<p>Description</p>
	<p><textarea name="description" id="category_description" rows="4" cols="72"><?= $description ?></textarea></p>

	<?php if ($thumb): ?>
	<p><img src="<?= $thumb ?>"></p>
	<?php endif; ?>
	<ul id="category_image_upload" class="item_actions_list">
		<li id="uploading_pick"><a id="pickfiles" href="#"><span class="actions action_edit"></span> Upload A Picture</a></li>
		<li id="uploading_status" class="hide"><span class="actions action_sync"></span> Uploading: <span id="file_uploading_progress"></span><span id="file_uploading_name"></span></li>			
		<li id="uploading_delete" class="hide"><a id="delete_picture" href="#"><span class="actions action_delete"></span> Delete Picture</a></li>
	</ul>

	<p>Parent Category</p>
	<p><?= form_dropdown('parent_id', $categories_dropdown, $parent_id) ?></p>

	<p>Access</p>
	<p><?= form_dropdown('access', config_item('access'), $access, 'id="category_access"') ?></p>

	<input type="hidden" name="details" value='<?= $details ?>'>
	<input type="hidden" name="site_id" value='<?= $site_id ?>'>

</form>
<script type="text/javascript">
$(document).ready(function()
{
		
});
</script>