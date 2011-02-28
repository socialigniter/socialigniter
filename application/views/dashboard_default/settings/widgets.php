<form name="settings_update" id="settings_update" method="post" action="<?= base_url() ?>api/settings/modify" enctype="multipart/form-data">

<!--
	<h3>Create</h3>
	<input type="button" name="add_widget" value="Add Widget">
	
	<h3>Layout</h3>
	<?= form_dropdown('layout', $layouts, ''); ?>
-->
	<div id="widget_content_area" class="widget_area">		
		<h3>Content</h3> <input type="button" name="add_widget_content" class="add_widget" rel="content" value="Add">
		<div class="clear"></div>	
		<div class="widget_border"></div>			
	</div>

	<div id="widget_sidebar_area" class="widget_area">		
		<h3>Sidebar</h3> <input type="button" name="add_widget_sidebar" class="add_widget" rel="sidebar" value="Add">
		<div class="clear"></div>
		<div class="widget_border"></div>
	</div>
	<div class="clear"></div>

	<div id="widget_wide_area" class="widget_area">
		<h3>Wide</h3> <input type="button" name="add_widget_wide" class="add_widget" rel="wide" value="Add">
		<div class="clear"></div>
		<div class="widget_border"></div>	
	</div>


</form>

<script type="text/javascript">
$(document).ready(function()
{
	/* Widget Picker Plugin */
	(function($)
	{
		$.widgetPicker = function(options)
		{
			var settings = {
				url_html	: '',
				url_submit	: '',
				type		: '',
				title		: '',
				after 		: function(){}
			};
			
			options = $.extend({},settings,options);					
		
			// Gets the HTML template
			$.get(base_url + 'home/widget_picker',{},function(category_editor)
			{							
				// Update returned HTML
				html = $(category_editor)
						.find('#editor_title').html(options.title).end()
					.html();
									
				$.fancybox(
				{
					content: html,
					onComplete: function(e)
					{
						$('#category_parent_id').live('change', function(){$.uniform.update(this);});							
						$('.modal_wrap').find('select').uniform().end().animate({opacity:'1'});
													
						// Create Category
						$('#add_widget').bind('submit',function(e)
						{
							e.preventDefault();
							e.stopPropagation();
							
							var category_data = $('#new_category').serializeArray();
							category_data.push({'name':'type','value':options.type});
						
							$(this).oauthAjax(
							{
								oauth 		: user_data,
								url			: options.url_sub,
								type		: 'POST',
								dataType	: 'json',
								data		: category_data,
								success		: function(json)
								{																		  	
									if(json.status == 'success')
									{
										alert('yay success');
									}
									else
									{
										alert('awwww failure'); //$.fancybox.close();
									}	
								}
							});
							
							return false;
						});
					}
				});					
			});
		};
	})( jQuery );




	// Add Widget
	$('.add_widget').live('click', function()
	{	
		$.widgetPicker(
		{
			url_html	: base_url + 'home/settings/occurrence_membership',		
			url_submit	: base_url + 'api/classes/occurrence_create',
			type		: $(this).attr('rel'),
			title		: 'Add ' + $(this).attr('rel') + 'Widget'
		});		
	});


});
</script>

<?= $shared_ajax ?>