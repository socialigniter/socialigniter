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
	<?php if (config_item($module.'_enabled') == 'TRUE'): ?>
	<span class="item_right">		
		<a href="<?= $module ?>">Settings</a>
	</span>
	<?php if (config_item($module.'_categories') == 'TRUE'): ?>
	<span class="item_right">
		<a href="<?= $module ?>/categories">Categories</a>
	</span>
	<?php endif; if (config_item($module.'_widgets') == 'TRUE'): ?>
	<span class="item_right">
		<a href="<?= $module ?>/widgets">Widgets</a>
	</span>
	<?php endif; else: ?>
	<span class="item_right">
		<a href="<?= base_url().'api/'.$module.'/install/' ?>" rel="<?= $module ?>" class="install_app">Install</a>
	</span>
	<?php endif; ?>
	<div class="clear"></div>
	<span class="item_separator"></span>	
</li>
<?php endif; endforeach; ?>
</ol>
<div class="clear"></div>
<script type="text/javascript">
$(document).ready(function()
{
	// Write Article
	$('.install_app').bind('click', function(eve)
	{
		eve.preventDefault();
		var install_app = $(this).attr('rel');
	
		$.oauthAjax(
		{
			oauth 		: user_data,
			url			: $(this).attr('href'),
			type		: 'GET',
			dataType	: 'json',
	  		success		: function(result)
	  		{
	  			console.log(result);
	  		
				$('html, body').animate({scrollTop:0});
				
				if (result.status == 'success')
				{
					$('#content_message').notify({status:result.status,message:result.message + ' You will now be direct to setup.',complete:'redirect',redirect: base_url + 'settings/' + install_app});			 		
		 		}
		 	}
		});		
	});	
});
</script>