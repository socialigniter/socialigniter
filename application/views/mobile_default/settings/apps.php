<ol>
<?php foreach($core_modules as $module): if (!in_array($module, $ignore_modules)): ?>
<li class="item_manage" id="item-<?= $module ?>" rel="modules">
	<span class="item_title">
		<img src="<?= $dashboard_assets.'icons/'.$module ?>_24.png"> <?= display_nice_file_name($module) ?>
	</span>	
	<span class="item_right">
		<a href="<?= $module ?>">Settings</a>
	</span>	
	<div class="clear"></div>
	<span class="item_separator"></span>	
</li>
<?php endif; endforeach; foreach($modules as $module): if (!in_array($module, $core_modules) && (!in_array($module, $ignore_modules))): ?>
<li class="item_manage" id="item-<?= $module ?>" rel="modules">
	<span class="item_title">
		<img src="<?= base_url().'application/modules/'.$module.'/assets/'.$module ?>_24.png"> <?= display_nice_file_name($module) ?>
	</span>	
	<span class="item_right">
	<?php if (config_item($module.'_enabled') == 'TRUE'): ?>
	
		<a href="<?= $module ?>">Settings</a>
		
	<?php else: ?>
	
		<a href="<?= base_url().'settings/'.$module.'/install' ?>">Install</a>
	
	<?php endif; ?>
	</span>	
	<div class="clear"></div>
	<span class="item_separator"></span>	
</li>
<?php endif; endforeach; ?>
</ol>
<div class="clear"></div>