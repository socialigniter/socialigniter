<form name="page_editor" id="page_editor" action="<?= $form_url ?>" method="post" enctype="multipart/form-data">

	<div id="content_wide_content">
		<h3>Title</h3>
		<input type="text" name="title" id="title" class="input_full" value="<?= $title ?>">
		<p id="title_slug" class="slug_url"></p>
	
		<h3>Content</h3>
		<?= $wysiwyg ?>
		
		<h3>Layout</h3>
		<div id="layout_options">
		<?php foreach ($layouts as $layout): ?>
			<a id="layout_<?= $layout ?>" class="layout_picker <?php if ($layout == $details) echo 'layout_selected'; ?>" href="#"><?= $layout ?></a>
		<?php endforeach; ?>
		</div>
		<div class="clear"></div>		

	    <h3>Category</h3>
	    <p><?= form_dropdown('category_id', $categories, $category_id) ?></p>
	    
	    <h3>Tags</h3>
	    <p><input name="tags" type="text" id="tags" size="75" placeholder="Blogging, Internet, Web Design" /></p>

		<h3>Access</h3>
		<p><?= form_dropdown('access', config_item('access'), $access) ?></p>
	
		<h3>Comments</h3>
		<p><?= form_dropdown('comments_allow', config_item('comments_allow'), $comments_allow) ?></p>
		
		<input type="hidden" name="details" id="layout" value="<?= $details ?>">
		<input type="hidden" name="geo_lat" id="geo_lat" value="" />
		<input type="hidden" name="geo_long" id="geo_long" value="" />
		<input type="hidden" name="geo_accuracy" id="geo_accuracy" value="" />

	</div>
	
	<div id="content_wide_toolbar">
		<?= $content_publisher ?>
	</div>	
	
</form>
<div class="clear"></div>

<script type="text/javascript">
$(document).ready(function()
{
	// Placeholders
	doPlaceholder('#title', 'Page Title');
	doPlaceholder('#tags', 'About, Help, Information');
	$('#title').slugify({url:base_url + 'pages/', slug:'#title_slug', name:'title_url', slugValue:'<?= $title_url ?>'});
	
	// Write Article
	$("#page_editor").bind("submit", function(eve)
	{
		eve.preventDefault();
		var valid_title	= isFieldValid('#title', "Ridiculously Cute Kitten Saves Owner From Fire", 'You need a title');

		// Validation	
		if (valid_title == true)
		{	
			var page_data = $('#page_editor').serializeArray();
			page_data.push({'name':'module','value':'pages'},{'name':'type','value':'page'},{'name':'source','value':'website'});

			console.log(page_data);

			$(this).oauthAjax(
			{
				oauth 		: user_data,
				url			: $(this).attr('ACTION'),
				type		: 'POST',
				dataType	: 'json',
				data		: page_data,
		  		success		: function(result)
		  		{	
		  			console.log(result);
		  				  			  			
					if (result.status == 'success')
					{
				 		$('#content_message').notify({message: result.message + ' <a href="' + base_url + 'pages/view/' + result.data.content_id + '">' + result.data.title + '</a>'});
				 		var status = displayContentStatus(result.data.status);				 		
				 		$('#article_status').html(status);				 	
				 	}
				 	else
				 	{
					 	$('#content_message').html(result.message).addClass('message_alert').show('normal');
					 	$('#content_message').oneTime(3000, function(){$('#content_message').hide('fast')});			
				 	}	
			 	}
			});
		}
		else
		{
			eve.preventDefault();
		}		
	});

	// Add Category
	$('[name=category_id]').change(function()
	{	
		if($(this).val() == 'add_category')
		{
			$('[name=category_id]').find('option:first').attr('selected','selected');
			$.uniform.update('[name=category_id]');

			$.categoryEditor(
			{
				url_api		: base_url + 'api/categories/view/module/pages',
				url_pre		: base_url + 'pages/',
				url_sub		: base_url + 'api/categories/create',				
				module		: 'pages',
				type		: 'category',
				title		: 'Add Category',
				slug_value	: '',
				trigger		: $('.content [name=category_id]')
			});			
		}
	});		
	
});
</script>