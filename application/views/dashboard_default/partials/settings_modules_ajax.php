<script type="text/javascript">
$(document).ready(function()
{
	// Save Settings
	$('#settings_update').bind('submit', function(eve)
	{
		eve.preventDefault();
		var settings_data = $('#settings_update').serializeArray();
		settings_data.push({'name':'module','value':'<?= $this_module ?>'});	
	
		$(this).oauthAjax(
		{
			oauth 		: user_data,
			url			: base_url + 'api/settings/modify',
			type		: 'POST',
			dataType	: 'json',
			data		: settings_data,
	  		success		: function(result)
	  		{
				$('html, body').animate({scrollTop:0});
				$('#content_message').notify({scroll:true,status:result.status,message:result.message});			 		
		 	}
		});		
	});	
});
</script>