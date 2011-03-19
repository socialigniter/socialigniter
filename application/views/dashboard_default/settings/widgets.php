<ul id="available_widgets"></ul>

<form name="settings_update" id="settings_update" method="post" action="<?= base_url() ?>api/settings/modify" enctype="multipart/form-data">
	<div id="widget_content_area" class="widget_area">		
		<h3>Content</h3> <input type="button" name="widget_add_content" class="widget_add" rel="content" value="Add">
		<div class="clear"></div>	
		<div class="widget_border">
			<?php if ($content_widgets): foreach ($content_widgets as $json_widget): $widget = json_decode($json_widget->value); ?>
			<div class="widget_instance">
				<span class="widget_name"><?= $widget->name ?></span>
				<a class="widget_edit" href="<?= $json_widget->settings_id ?>"><span class="actions action_edit"></span>Edit</a>		
				<div class="clear"></div>
			</div>
			<?php endforeach; else: ?>
			<div class="widget_instance_none">
				No Widgets
			</div>			
			<?php endif; ?>		
		</div>			
	</div>

	<div id="widget_sidebar_area" class="widget_area">
		<h3>Sidebar</h3> <input type="button" name="widget_add_sidebar" class="widget_add" rel="sidebar" value="Add">
		<div class="clear"></div>
		<div class="widget_border">
			<?php if ($sidebar_widgets): foreach ($sidebar_widgets as $json_widget): $widget = json_decode($json_widget->value); ?>
			<div class="widget_instance" id="<?= $json_widget->settings_id ?>">
				<span class="widget_icon"><img src="<?= display_module_assets($widget->module, $dashboard_assets.'icons/', '').$widget->module ?>_24.png"></span>
				<span class="widget_name"><?= $widget->name ?></span>
				<a class="widget_edit" href="<?= $json_widget->settings_id ?>"><span class="actions action_edit"></span>Edit</a>
				<textarea name="widget_data" style="display:none"><?= $json_widget->value ?></textarea>
				<div class="clear"></div>				
			</div>
			<?php endforeach; else: ?>
			<div class="widget_instance_none">No Widgets</div>
			<?php endif; ?>
		</div>
	</div>
	<div class="clear"></div>

	<div id="widget_wide_area" class="widget_area">
		<h3>Wide</h3> <input type="button" name="widget_add_wide" class="widget_add" rel="wide" value="Add">
		<div class="clear"></div>
		<div class="widget_border">

			<?php if ($wide_widgets): foreach ($wide_widgets as $json_widget): $widget = json_decode($json_widget->value); ?>
			<div class="widget_instance" id="<?= $widget->order ?>">
				<span class="widget_icon"><img src="<?= display_module_assets($widget->module, $dashboard_assets.'icons', '').$widget->module ?>_24.png"></span>
				<span class="widget_name"><?= $widget->name ?></span>
				<a class="widget_edit" href="<?= $json_widget->settings_id ?>"><span class="actions action_edit"></span>Edit</a>				
				<div class="clear"></div>
			</div>
			<?php endforeach; else: ?>
			<div class="widget_instance_none">No Widgets</div>			
			<?php endif; ?>
		</div>	
	</div>
</form>

<script type="text/javascript">
$(document).ready(function()
{
	// Add Widget
	$('.widget_add').live('click', function()
	{	
		var location = $(this).attr('rel');

		$.get(base_url+'api/settings/module/widgets', function(result)
		{		
			$.each(result.data, function()
			{							
				if (this.setting == location)
				{		
					var widget = jQuery.parseJSON(this.value);

					$("<li></li>").html(widget.name).appendTo('#available_widgets');
				}
			});
			
			alert('ADD A ' + location + ' WIDGET NOW! Pick one of these: ');
		});
	});

	// Draggable	
	$('.widget_border').sortable(
	{	
		stop: function() 
		{
			var count = 0;
		
			$(this).find('.widget_instance').each(function()
			{
				count++;
				var settings_id = $(this).attr('id');
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
			  		success	 : function(result)
			  		{
						console.log(result);
				 	}
				});
			});	
		}
	});	
	

	// Edit Partial
	var edit_html = '<p>Oops, something went wrong! Close and try again in a few seconds.</p>';
	$.get(base_url + 'home/widget_editor/standard',function(html)
	{		
		edit_html = html;
	});   
    
    // Edit Event
    $('.widget_edit').click(function(eve)
    {
    	eve.preventDefault();
		var settings_id = $(this).attr('href');
		
		$.get(base_url + 'api/settings/setting/id/' + settings_id, function(json)
		{
			var widget = jQuery.parseJSON(json.data.value);
			     
			edit_html = $('<div />').html(edit_html).find('textarea').val(widget.content).end();
			$('<div />').html(edit_html).dialog(
			{
				width	: 600,
				modal	: true,
				title	: widget.name,
				create	: function()
				{
					// Will run RIGHT as the dialog is generated in the DOM
					console.log('Dialog generated in the DOM');
				
					//Here we save "this" dialog so we can reference it in "sub scopes"
					$parent_dialog = $(this);               
				},
				buttons:
				{
					'Save':function()
					{
						var widget_data = $('#widget_setting').serializeArray();
						//widget_data.push({'name':'module','value':'users'});		
					
					    //var $setting_dialog = $(this);
					
						$(this).find('form').oauthAjax(
						{
							oauth 		: user_data,
							url			: base_url + 'api/settings/modify/id/' + settings_id,
							type		: 'POST',
							dataType	: 'json',
							data		: widget_data,
					  		success		: function(result)
					  		{
					  			if (result.status == 'success')
					  			{
									$(this).dialog('close');
								}
								else
								{
									alert('Could not save');
								}	
						 	}
						});				  
				  },			
				  'Close':function()
				  {
				  	$(this).dialog('close');
				  }
				}			
	    	});
	    	
	    });
	});
       
});
</script>


<?= $shared_ajax ?>