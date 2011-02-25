<script type="text/javascript">
$(document).ready(function()
{
	// Write Article
	$("#settings_update").bind("submit", function(e)
	{
		e.preventDefault();
		var settings_data = $('#settings_update').serializeArray();
		settings_data.push({'name':'module','value':'<?= $this_module ?>'});		
	
		$(this).oauthAjax(
		{
			oauth 		: user_data,
			url			: '<?= base_url() ?>api/settings/modify',
			type		: 'POST',
			dataType	: 'json',
			data		: settings_data,
	  		success		: function(result)
	  		{		  					  			  			
				if (result.status == 'success')
				{
				 	$('#content_message').html(result.message).addClass('message_alert').show('slow');				 	
				 	$('#content_message').oneTime(4000, function(){$('#content_message').hide('normal')});
			 	}
			 	else
			 	{
				 	$('#content_message').html(result.message).addClass('message_alert').show('slow');
				 	$('#content_message').oneTime(4000, function(){$('#content_message').hide('normal')});			
			 	}	
		 	}
		});		
	});	
});
</script>