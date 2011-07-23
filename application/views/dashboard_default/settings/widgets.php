<ul id="available_widgets"></ul>

<div id="layout_options">
<h3>Layouts</h3>

<?php foreach ($layouts as $layout): ?>
	<a class="layout_picker <?php if ($layout == $layout_selected) echo 'layout_selected'; ?>" href="<?= base_url().'settings/widgets/'.$layout ?>"><?= display_nice_file_name($layout) ?></a>
<?php endforeach; ?>

<input type="hidden" name="this_layout" id="this_layout" value="<?= $layout_selected ?>">

</div>
<div class="clear"></div>

<?= $layout_regions ?>

<script type="text/javascript">
$(document).ready(function()
{
	// Draggable	
	$('.widget_border').sortable(
	{	
		stop: function() 
		{
			var count 			= 0;
			var this_elements 	= $(this).find('.widget_instance');
		
			if (this_elements.length > 1)
			{	
				this_elements.each(function()
				{
					count++;
					var settings_id	= $(this).attr('id').split('_')[1];
					var widget_json = $(this).find('textarea').val();
					var widget_data = $.parseJSON(widget_json);
	
					// New Order
					widget_data.order = count;
					var new_widget_data = [{'name':'value','value':JSON.stringify(widget_data)}];
				
					$(this).oauthAjax(
					{
						oauth 	 : user_data,
						url		 : base_url + 'api/settings/modify_widget/id/' + settings_id,
						type	 : 'POST',
						dataType : 'json',
						data	 : new_widget_data,
				  		success	 : function(result) {}
					});
				});	
			}
		}
	});	

	// Error Partial
	var partial_html = '<p>Oops, something went wrong! Close and try again in a few seconds.</p>';	
	
	// Add Widget
	$('.widget_add').bind('click', function(eve)
    {
    	eve.preventDefault();
    	var widget_layout = $('#this_layout').val();
		var widget_region = $(this).attr('rel');
		var widget_count  = $('#widget_' + widget_region + '_container').find('.widget_instance').length;
		
		// Get Available Widgets 
		$.get(base_url+'api/settings/widgets_available/region/' + widget_region + '/layout/' + widget_layout, function(result)
		{	
			// Get Add Dialog
			$.get(base_url + 'home/widget_add',function(partial_html)
			{
				// Loop Through Available and Add to Dialog				
				$.each(result.data, function()
				{				
					var this_assets = displayModuleAssets(this.module, core_modules, core_assets);
					var widget_json = JSON.stringify(this);
					partial_html = $('<div />').html(partial_html).find('#widgets_available').append("<li class='widget_add_instance'><form name='widget_add'><span class='widget_icon'><img src='" + this_assets + this.module + "_24.png'></span><span class='widget_name'>" + this.name + "</span><a class='widget_add' href=''><span class='actions action_add'></span>Add</a><input name='value' type='hidden' value='" + widget_json + "'></form></li>").end();
				});

				$('<div />').html(partial_html).dialog(
				{
					width	: 325,
					modal	: true,
					title	: 'Add ' + widget_region + ' Widget',
					create	: function()
					{
						$parent_dialog = $(this);
						
						// Add Event
						$('.widget_add_instance').bind('click', function(add_eve)
						{
					    	eve.preventDefault();

							var widget_data		= [];
							var widget_json		= jQuery.parseJSON($(this).find('input').val());
							widget_json.order	= widget_count + 1;
							widget_json.layout	= '<?= $layout_selected ?>';							
							widget_data.push({'name':'module','value':'widgets'},{'name':'setting','value':widget_region},{'name':'value','value':JSON.stringify(widget_json)});

							 $(this).find('form').oauthAjax(
							{
								oauth 		: user_data,		
								url			: base_url + 'api/settings/create',
								type		: 'POST',
								dataType	: 'json',
								data		: widget_data,
							  	success		: function(result)
							  	{							  	
									if (result.status == 'success')
									{	
										var widget		 = jQuery.parseJSON(result.data.value);
										var this_assets	 = displayModuleAssets(widget.module, core_modules, core_assets);
										var added_widget = '<div class="widget_instance" id="widget_' + result.data.settings_id + '"><span class="widget_icon"><img src="' + this_assets + widget.module + '_24.png"></span><span class="widget_name">' + widget.name + '</span><a class="widget_edit" href="' + result.data.settings_id + '"><span class="actions action_edit"></span>Edit</a><textarea name="widget_data" style="display:none">' + result.data.value + '</textarea><div class="clear"></div></div>';
																		
										// Hide No Widgets									
										if (widget_count === 0)
										{
											$('#no_' + widget_region + '_widgets').hide('fast');
										}
										
										// Add New Widget										
										setTimeout(function()
										{
											$('#widget_' + widget_region + '_area').find('div.widget_border').append(added_widget).fadeIn('normal');
										}, 500);
									}
									else
									{
										$('html, body').animate({scrollTop:0});
										$('#content_message').notify({status:result.status,message:result.message});									
									}
								
									// Close Dialog
									$parent_dialog.dialog('close');
							  	}		
							});						
						});	
					},
		    	});			
			});	
		});
	});

    // Edit Event
    $('.widget_edit').bind('click', function(eve)
    {
    	eve.stopPropagation();
    	eve.preventDefault();

		var settings_id 	= $(this).attr('href');
		var widget_count	= $('#' + $(this).parent().parent().attr('id')).find('.widget_instance').length;
				
		$.get(base_url + 'api/settings/setting/id/' + settings_id, function(json)
		{
			var widget = jQuery.parseJSON(json.data.value);

			$.get(base_url + 'home/widget_editor/standard',function(html)
			{
				partial_html = html;
				partial_html = $('<div />').html(partial_html).find('textarea').val(widget.content).end();
				partial_html = $('<div />').html(partial_html).find('input').val(json.data.value).end();

				$('<div />').html(partial_html).dialog(
				{
					width	: 450,
					modal	: true,
					title	: 'Edit ' + widget.name,
					create	: function()
					{
						$parent_dialog = $(this);
						$('.widget_delete').bind('click', function(del_eve)
						{												
							del_eve.stopPropagation();
					    	del_eve.preventDefault();

							$(this).oauthAjax(
							{
								oauth 		: user_data,
								url			: base_url + 'api/settings/destroy/id/' + settings_id,
								type		: 'GET',
								dataType	: 'json',
							  	success		: function(result)
							  	{
									if (result.status == 'success')
									{	
										// No Widgets
										var widget_new_count = widget_count - 1;										
										if (widget_new_count === 0)
										{
											$('#widget_' + json.data.setting + '_container').delay(500).append('<div class="widget_instance_none" id="no_' + json.data.setting + '_widgets">No Widgets</div>');
										}
									
										// Remove Widget	
										$('#widget_'+settings_id).delay(200).fadeOut('normal', function()
										{
											$(this).delay(750).remove();
										});

										$parent_dialog.dialog('close');
										$parent_dialog.remove();
									}		  	
							  	}		
							});
						});						              
					},
					buttons:
					{
						'Save':function()
						{
							var widget_data     = [];
							var widget_json     = jQuery.parseJSON($(this).find('input').val());
							widget_json.content = $(this).find('textarea').val();
							widget_data.push({'name':'value','value':JSON.stringify(widget_json)});
							
							$(this).find('form').oauthAjax(
							{
								oauth 		: user_data,
								url			: base_url + 'api/settings/modify_widget/id/' + settings_id,
								type		: 'POST',
								dataType	: 'json',
								data		: widget_data,
						  		success		: function(result)
						  		{						  							  		
						  			if (result.status == 'success')
						  			{
										$parent_dialog.dialog('close');
									}
									else
									{
										alert('Could not save');
									}	
							 	}
							});								
						}
					}			
		    	});
			});
	    });
	});
       
});
</script>