<ol id="list">
<?php foreach($core_modules as $module): ?>
<li class="item" id="item-users" rel="modules">
	<span class="item_title">
		<img src="<?= $dashboard_assets.'icons/'.$module ?>_24.png"> <?= display_nice_file_name($module) ?>
	</span>	
	<span class="item_right">
		<a href="<?= $module ?>">Settings</a>
	</span>	
	<div class="clear"></div>
	<span class="item_separator"></span>	
</li>
<?php endforeach; foreach($modules as $module): if (!in_array($module, $core_modules) && (!in_array($module, $ignore_modules))): ?>
<li class="item" id="item-<?= $module ?>" rel="modules">
	<span class="item_title">
		<img src="<?= base_url().'application/modules/'.$module.'/assets/'.$module ?>_24.png"> <?= display_nice_file_name($module) ?>
	</span>	
	<span class="item_right">
		<a href="<?= $module ?>">Settings</a>
	</span>	
	<div class="clear"></div>
	<span class="item_separator"></span>	
</li>
<?php endif; endforeach; ?>
</ol>
<div class="clear"></div>