<h3>Download Latest</h3>

<p>This will eventually pull a most recent version of Social-Igniter from the github and copy over revevant files.</p>

<h3>Update Routes</h3>

<p>This will eventually copy over the routes.php.TEMPLATE to routes.php and saves custom routes (which should be stored in DB anyway)</p>

<h3>Migrate Databse</h3>

<p>Update your database schema to the following</p>

<p><button id="migrate_current" name="migrate_current">Update Stable</button> <button id="migrate_latest" name="migrate_latest">Update Latest</button></p>
</form>
<script type="text/javascript">
$(document).ready(function()
{
	$('#migrate_current, #migrate_latest').bind('click', function(e)
	{
		e.preventDefault();
		$.oauthAjax(
		{
			oauth 		: user_data,		
			url			: base_url + 'api/install/' + $(this).attr('id'),
			type		: 'GET',
			dataType	: 'json',
		  	success		: function(result)
		  	{							  	
				$('html, body').animate({scrollTop:0});
				$('#content_message').notify({scroll:true,status:result.status,message:result.message});									
		  	}		
		});						
	});	
});
</script>