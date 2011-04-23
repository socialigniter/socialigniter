<style type="text/css">
/* Everything else inherited from Dashboard theme CSS */
#widget_content_area 				{ width: 545px; padding:0 0 0 12px; margin: 0 0 10px 0; float: left; }
#widget_sidebar_area 				{ width: 275px; padding:0 0 0 12px; margin: 0 0 10px 35px; float: left; }
#widget_wide_area 	 				{ width: 870px; padding:0 0 0 12px; margin: 0 0 25px 0; }
</style>

<div id="widget_content_area" class="widget_area">		
	<h3>Content</h3> <input type="button" name="widget_add_content" class="widget_add" rel="content" value="Add">
	<div class="clear"></div>	
	<div class="widget_border" id="widget_content_container">
		<?php if ($content_widgets): foreach ($content_widgets as $json_widget): $widget = json_decode($json_widget->value); ?>
		<div class="widget_instance" id="widget_<?= $json_widget->settings_id ?>">
			<span class="widget_icon"><img src="<?= display_module_assets($widget->module, $dashboard_assets.'icons/', '').$widget->module ?>_24.png"></span>
			<span class="widget_name"><?= $widget->name ?></span>
			<a class="widget_edit" href="<?= $json_widget->settings_id ?>"><span class="actions action_edit"></span>Edit</a>
			<textarea name="widget_data" style="display:none"><?= $json_widget->value ?></textarea>
			<div class="clear"></div>				
		</div>
		<?php endforeach; else: ?>
		<div class="widget_instance_none" id="no_content_widgets">No Widgets</div>
		<?php endif; ?>	
	</div>			
</div>

<div id="widget_sidebar_area" class="widget_area">
	<h3>Sidebar</h3> <input type="button" name="widget_add_sidebar" class="widget_add" rel="sidebar" value="Add">
	<div class="clear"></div>
	<div class="widget_border" id="widget_sidebar_container">
		<?php if ($sidebar_widgets): foreach ($sidebar_widgets as $json_widget): $widget = json_decode($json_widget->value); ?>
		<div class="widget_instance" id="widget_<?= $json_widget->settings_id ?>">
			<span class="widget_icon"><img src="<?= display_module_assets($widget->module, $dashboard_assets.'icons/', '').$widget->module ?>_24.png"></span>
			<span class="widget_name"><?= $widget->name ?></span>
			<a class="widget_edit" href="<?= $json_widget->settings_id ?>"><span class="actions action_edit"></span>Edit</a>
			<textarea name="widget_data" style="display:none"><?= $json_widget->value ?></textarea>
			<div class="clear"></div>				
		</div>
		<?php endforeach; else: ?>
		<div class="widget_instance_none" id="no_sidebar_widgets">No Widgets</div>
		<?php endif; ?>
	</div>
</div>
<div class="clear"></div>

<div id="widget_wide_area" class="widget_area">
	<h3>Wide</h3> <input type="button" name="widget_add_wide" class="widget_add" rel="wide" value="Add">
	<div class="clear"></div>
	<div class="widget_border" id="widget_wide_container">
		<?php if ($wide_widgets): foreach ($wide_widgets as $json_widget): $widget = json_decode($json_widget->value); ?>
		<div class="widget_instance" id="widget_<?= $json_widget->settings_id ?>">
			<span class="widget_icon"><img src="<?= display_module_assets($widget->module, $dashboard_assets.'icons/', '').$widget->module ?>_24.png"></span>
			<span class="widget_name"><?= $widget->name ?></span>
			<a class="widget_edit" href="<?= $json_widget->settings_id ?>"><span class="actions action_edit"></span>Edit</a>				
			<div class="clear"></div>
		</div>
		<?php endforeach; else: ?>
		<div class="widget_instance_none" id="no_wide_widgets">No Widgets</div>			
		<?php endif; ?>
	</div>	
</div>
