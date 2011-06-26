<h1>Upload yo picture</h1>

<form method="post" name="user_profile" id="user_profile" action="<?= base_url() ?>api/users/upload_profile_picture/id/<?= $logged_user_id ?>" enctype="multipart/form-data">
	<table border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td>Picture:</td>
		<td><input type="file" size="20" name="userfile"></td>
	</tr>
    <tr>		
		<td colspan="3"><input type="submit" value="Save" /></td>
  	</tr>  	
	</table>
</form>

<script type="text/javascript">
$(document).ready(function()
{
	$("#user_profile").bind("submit", function(e)
	{	
		e.preventDefault();
		var profile_data = $('#user_profile').serializeArray();
	
		$(this).oauthAjax(
		{
			oauth 		: user_data,
			url			: $(this).attr('ACTION'),
			type		: 'POST',
			dataType	: 'json',
			data		: profile_data,
	  		success		: function(result)
	  		{
				$('html, body').animate({scrollTop:0});
				$('#content_message').notify({scroll:true,status:result.status,message:result.message});
				
				if (result.status == 'success')
				{
					// Update Viewed User Data
					$('#logged_name').html(result.data.name);
					// DO PICTURE ONCE WORKING
				}
				
		 	}
		});		
	});	
});
</script>
