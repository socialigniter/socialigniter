<div align='center'>
<p>
	Are you sure you want to add <?= $webfinger ?><br><br>
	<p>
		<button id='confirm'>YES</button>
	</p>
	<script>
	$('#confirm').bind("click", function(){

		$(document).oauthAjax(
		{
			oauth 		: user_data,
			url			: base_url + 'api/relationships/follow_remote/webfinger_id/<?= $webfinger ?>',
			type		: 'POST',
			dataType	: 'json',
			data		: '',
	  		success		: function(result)
	  		{	
	  			console.log(result);	  			
	  				  			
				if (result.status == 'success')
				{

			 	}
			 	else
			 	{
					alert(result.message);
			 	}
		 	}
		})
	});
	
	
	
	
		//window.location.href = document.referrer;
	</script>
</p>
</div>


