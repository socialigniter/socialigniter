<form method="post" name="user_details" id="user_details" action="<?= base_url() ?>api/users/details/id/<?= $logged_user_id ?>" enctype="multipart/form-data">
	<table border="0" cellpadding="0" cellspacing="0">
		<tr>		
			<td>Company:</td>
			<td colspan="2"><input type="text" name="company" placeholder="Company" size="40" value="<?= $company ?>"></td>
		</tr>
		<tr>		
			<td>Location:</td>
			<td colspan="2"><input type="text" name="location" size="40" placeholder="City, ST" value="<?= $location ?>"></td>
		</tr>
		<tr>
			<td>Website:</td>
			<td colspan="2"><input type="url" name="url" size="40" placeholder="http://website.com" value="<?= $url ?>"></td>
		</tr>
		<tr>		
			<td valign="top">Bio:</td>
			<td colspan="2"><textarea name="bio" cols="39" rows="6"><?= $bio ?></textarea></td>
		</tr>    
		<tr>		
			<td colspan="2"><input type="submit" value="Save" /></td>
		</tr>			
	</table>
</form>

<script type="text/javascript">
$(document).ready(function()
{
	// Write Article
	$("#user_details").bind("submit", function(e)
	{
		e.preventDefault();
		var details_data = $('#user_details').serializeArray();
		details_data.push({'name':'module','value':'users'});		
	
		$(this).oauthAjax(
		{
			oauth 		: user_data,
			url			: $(this).attr('ACTION'),
			type		: 'POST',
			dataType	: 'json',
			data		: details_data,
	  		success		: function(result)
	  		{
				$('html, body').animate({scrollTop:0});
				$('#content_message').notify({scroll:true,status:result.status,message:result.message});
		 	}
		});		
	});	
});
</script>