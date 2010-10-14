<ol id="list">
<?php foreach($core_modules as $module): ?>
<li class="item" id="item-users" rel="modules">
	<span class="item_title">
		<img src="<?= $dashboard_assets.'icons/'.$module ?>_24.png"> <?= ucwords($module) ?>
	</span>	
	<span class="item_right">
		<a href="<?= $module ?>">Settings</a>
	</span>	
	<div class="clear"></div>
	<span class="item_separator"></span>	
</li>
<?php endforeach; ?>
<?php foreach($modules as $module): $module_icon = base_url().'application/modules/'.$module.'/assets/'.$module ?>
<li class="item" id="item-<?= $module ?>" rel="modules">
	<span class="item_title">
		<img src="<?= $module_icon ?>_24.png"> <?= ucwords($module) ?>
	</span>	
	<span class="item_right">
		<a href="<?= $module ?>">Settings</a>
	</span>	
	<div class="clear"></div>
	<span class="item_separator"></span>	
</li>
<?php endforeach; ?>
</ol>
<div class="clear"></div>