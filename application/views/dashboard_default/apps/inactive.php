<ol>
<?php foreach($modules as $module): if (!in_array($module, $ignore_modules)): if ((config_item($module.'_enabled') == 'FALSE') || (config_item($module.'_enabled') == '')): ?>
<li class="item_manage" id="item-<?= $module ?>" rel="modules">
	<span class="item_title">
		<img src="<?= base_url().'application/modules/'.$module.'/assets/'.$module ?>_24.png"> <?= display_nice_file_name($module) ?>
	</span>	
	<span class="item_right">
		<a href="<?= base_url().'api/'.$module.'/install/' ?>" rel="<?= $module ?>" class="install_app">Install</a>
	</span>
	<div class="clear"></div>
	<span class="item_separator"></span>	
</li>
<?php endif; endif; endforeach; ?>
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