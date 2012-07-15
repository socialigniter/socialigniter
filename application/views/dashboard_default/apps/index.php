<ol>
<!-- Core Apps -->
<?php foreach($core_modules as $module): if (!in_array($module, $ignore_modules)): ?>
<li class="item_manage" id="item-<?= $module ?>" rel="modules">
	<span class="item_title">
		<img src="<?= $dashboard_assets.'icons/'.$module ?>_24.png"> <?= display_nice_file_name($module) ?>
	</span>
	<span class="item_right">	
		<a href="<?= base_url().'settings/'.$module ?>">Settings</a>
	</span>
	<div class="clear"></div>
	<span class="item_separator"></span>	
</li>
<?php endif; endforeach; ?>
<!-- Installable Apps -->
<?php foreach($modules as $module): if (!in_array($module, $core_modules) && (!in_array($module, $ignore_modules)) && (config_item($module.'_enabled') == 'TRUE')): ?>
<li class="item_manage" id="item-<?= $module ?>" rel="modules">
	<span class="item_title">
		<img src="<?= base_url().'application/modules/'.$module.'/assets/'.$module ?>_24.png"> <?= display_nice_file_name($module) ?>
	</span>	
	<span class="item_right">		
		<a href="<?= base_url().'settings/'.$module ?>">Settings</a>
	</span>
	<?php if (config_item($module.'_categories') == 'TRUE'): ?>
	<span class="item_right">
		<a href="<?= $module ?>/categories">Categories</a>
	</span>
	<?php endif; if (config_item($module.'_widgets') == 'TRUE'): ?>
	<span class="item_right">
		<a href="<?= $module ?>/widgets">Widgets</a>
	</span>
	<?php endif; ?>
	<div class="clear"></div>
	<span class="item_separator"></span>	
</li>
<?php endif; endforeach; ?>
</ol>
<div class="clear"></div>