<h3>Create App</h3>
<?php if ($app_template): ?>
<p>To create an app you should understand the basics of JavaScript, PHP and CodeIgniter or other comparable Object Oriented MVC (Model View Controller) frameworks.</p>
<form id="create_app" name="create_app" method="post" action="<?= base_url() ?>api/site/custom">
	<p>App Name<br>
	<input type="text" name="app_name" placeholder="My Cool App" size="32">
	</p>
	
	<p>App URL (url friendly app name)<br>
	<input type="text" name="app_url" placeholder="my-cool-app" size="32">
	</p>

	<p>App Class (CodeIgniter requires classes to have underscores)<br>
	<input type="text" name="app_class" placeholder="my_cool_app" size="32">
	</p>
	
	<p><input type="submit" name="submit" value="Create"></p>
</form>
<script type="text/javascript">
$(document).ready(function()
{
	$('#create_app').bind('submit', function(e)
	{
		e.preventDefault();	
		var data = $('#create_app').serializeArray();
	
		$.oauthAjax(
		{
			oauth 		: user_data,		
			url			: base_url + 'api/install/create_app',
			type		: 'POST',
			dataType	: 'json',
			data		: data,
		  	success		: function(result)
		  	{							  	
				$('html, body').animate({scrollTop:0});
				$('#content_message').notify({scroll:true,status:result.status,message:result.message});									
		  	}		
		});						
	});	
});
</script>
<?php else: ?>
<p>If you have App Template installed, you can use it jumpstart your development of custom apps to work with the Social-Ingiter platform. Since you are seeing this message, you do not have it installed. Clone <a href="https://github.com/socialigniter/app-template">this Git repo</a> into your modules directory.</p>
<?php endif; ?>