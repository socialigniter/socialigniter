<form id="category_editor" name="category_editor" method="post">

	<p>Category</p>
	<p><input type="text" id="category_name" name="category" value='<?= $category ?>' class="input_large"></p>
	<p id="category_slug" class="slug_url"><?= $category_url ?></p>

	<p>Description</p>
	<p><textarea name="description" id="category_description" rows="4" cols="72"><?= $description ?></textarea></p>

	<div id="category_image_thumb">
	<?php if ($thumb): ?>
		<img src="<?= $thumb ?>">
	<?php endif; ?>
	</div>
	
	<ul id="category_image_uploader" class="item_actions_list">
		<li id="uploading_pick"><a id="pickfiles" href="#"><span class="actions action_edit"></span> Upload A Picture</a></li>
		<li id="uploading_status" class="hide"><span class="actions action_sync"></span> Uploading: <span id="file_uploading_progress"></span><span id="file_uploading_name"></span></li>			
		<li id="uploading_details"><span class="actions_blank"></span> <?= config_item('users_images_max_size') / 1024 ?> MB max size (<?= strtoupper(str_replace('|', ', ', config_item('users_images_formats'))) ?>)</li>
	</ul>

	<p>Parent Category</p>
	<p><?= form_dropdown('parent_id', $categories_dropdown, $parent_id) ?></p>

	<p>Access</p>
	<p><?= form_dropdown('access', config_item('access'), $access, 'id="category_access"') ?></p>

	<p><input type="submit" value="Save"></p>

	<input type="hidden" name="details" value='<?= $details ?>'>
	<input type="hidden" name="site_id" value='<?= $site_id ?>'>

</form>
<script type="text/javascript" src="<?= base_url() ?>js/plupload.js"></script>
<script type="text/javascript" src="<?= base_url() ?>js/plupload.html5.js"></script>
<script type="text/javascript" src="<?= base_url() ?>js/plupload.flash.js"></script>
<script type="text/javascript">
$(document).ready(function()
{
	// Make Gategory Slug
	$('#category_name').slugify(
	{
		slug	  : '#category_slug',
		url		  : base_url + jQuery.url.segment(1) + '/',
		name	  : 'category_url',
		slugValue : $('#category_slug').html()
	});
	
	// Upload Image
	$.mediaUploader(
	{
		max_size	: '2mb',
		create_url	: base_url + 'api/categories/picture_upload',
		formats		: {title : 'Image Files', extensions : '<?= $upload_formats ?>'},
		start		: function(files)
		{
			// Hide Upload
			$('#uploading_pick').hide(); 
			$('#uploading_delete').hide();
			$('#uploading_details').hide();
			$('#uploading_status').show();
			$('#file_uploading_name').html(files[0].name);
		},
		complete : function(response)
		{
			// Hide Status
			$('#uploading_status').delay(500).fadeOut();
			$('#uploading_pick').delay(1250).fadeIn(); 
			$('#uploading_delete').delay(1250).fadeIn();
		
			// Actions
			if (response.status == 'success')
			{			
				$('#category_image_thumb').append('<img src="' + base_url + 'uploads/categories/small_' + response.data.content + '">');
			}
			else
			{
				$('html, body').animate({scrollTop:0});
				$('#content_message').notify({status:response.status,message:response.message});	
			}		
		}
	});		
	
	$('#category_editor').bind('submit', function(e)
	{
		var category_data = $('#category_editor').serializeArray();
		
		e.preventDefault();
		$(this).oauthAjax(
		{
			oauth 		: user_data,
			url			: base_url + 'api/categories/modify/id/' + jQuery.url.segment(3),
			type		: 'POST',
			dataType	: 'json',
			data		: category_data,
			success		: function(result)
			{
				$('html, body').animate({scrollTop:0});			
				$('#content_message').notify({status:result.status,message:result.message});
			}
		});	
	
	});

});
</script>