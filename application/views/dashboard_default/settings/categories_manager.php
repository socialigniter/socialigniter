<form id="category_editor" name="category_editor" method="post">

	<h3>Category</h3>
	<input type="text" id="category_name" name="category" value='<?= $category ?>' class="input_large">
	<p id="category_slug" class="slug_url"><?= $category_url ?></p>

	<h3>Description</h3>
	<textarea name="description" id="category_description" rows="4" cols="72"><?= $description ?></textarea>
	<div class="clear"></div>

	<div id="category_image_thumb">
	<?php if ($thumb): ?>
		<img src="<?= $thumb ?>">
	<?php endif; ?>
	</div>

	<ul id="category_image_uploader" class="item_actions_list">
		<li id="uploading_pick"><a id="pickfiles" href="#"><span class="actions action_upload"></span> Upload A Picture</a></li>
		<li id="uploading_status" class="hide"><span class="actions action_sync"></span> Uploading: <span id="file_uploading_progress"></span><span id="file_uploading_name"></span></li>			
		<li id="uploading_details" class="small_details"><span class="actions_blank"></span> <?= config_item('categories_images_max_size') / 1024 ?> MB max size (<?= strtoupper(str_replace('|', ', ', config_item('categories_images_formats'))) ?>)</li>
	</ul>
	<div class="clear"></div>

	<h3>Parent Category</h3>
	<?= form_dropdown('parent_id', $categories_dropdown, $parent_id) ?>

	<h3>Access</h3>
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
		create_url	: base_url + 'api/categories/picture_upload/id/' + jQuery.url.segment(3),
		formats		: {title : 'Image Files', extensions : '<?= $upload_formats ?>'},
		start		: function(files)
		{		
			// Hide Upload
			$('#uploading_pick').hide(); 
			$('#uploading_details').hide();
			$('#uploading_status').show();
			$('#file_uploading_name').html(files[0].name);
		},
		complete : function(response)
		{		
			// Hide Status
			$('#uploading_status').delay(500).fadeOut();
			$('#uploading_pick').delay(1000).fadeIn(); 
		
			// Actions
			if (response.status == 'success')
			{			
				$('#category_image_thumb').html('<img src="' + base_url + 'uploads/categories/' + jQuery.url.segment(3) + '/small_' + response.data.content + '">');
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
		$.oauthAjax(
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