<h3>Create An App</h3>
<?php if ($has_app_template): ?>
<p>In order to create a Social-Igniter app you should understand the basics of JavaScript, PHP and Object Oriented (OO) programming.
Social-Igniter is based on <a href="http://codeigniter.com" target="_blank">CodeIgniter</a> which is an (OO) Model View Controller (MVC) framework written in PHP. 
Learn more about CodeIgniter by reading the <a href="http://codeigniter.com/user_guide" target="_blank">documentation</a> and the PHP language at <a href="http://php.net/manual/en/" target="_blank">PHP.net</a> and <a href="http://phptherightway.com/" target="_blank">PHP The Right Way</a></p>
<form id="create_app" name="create_app" method="post" action="<?= base_url() ?>api/site/custom">

	<h3>App Info</h3>
	<p>App Name<br>
	<input type="text" name="app_name" placeholder="My Cool App" size="32">
	</p>
	
	<p>App URL (url friendly app name)<br>
	<input type="text" name="app_url" placeholder="my-cool-app" size="32">
	</p>

	<p>App Class (CodeIgniter requires class names to have underscores)<br>
	<input type="text" name="app_class" placeholder="my_cool_app" size="32">
	</p>
	
	<h3>App Details</h3>
	
	<p>REST API Methods (if your app will write, update, or delete from a custom REST API)<br>
		<?= form_dropdown('app_api_methods', config_item('yes_or_no'), 'FALSE') ?>
	</p>

	<p>Connections Class (if your app has OAuth, OAuth2 or a remote authentication to access data)<br>
		<?= form_dropdown('app_connections', config_item('yes_or_no'), 'FALSE') ?>
	</p>

	<p>Helper (if your app will have simple functions)<br>
		<?= form_dropdown('app_helper', config_item('yes_or_no'), 'FALSE') ?>
	</p>
	
	<p>Library (if you plan to write a object oriented library)<br>
		<?= form_dropdown('app_library', config_item('yes_or_no'), 'FALSE') ?>
	</p>	

	<p>OAuth Provider Library (if your app uses OAuth 1.0)<br>
		<?= form_dropdown('app_oauth_provider', config_item('yes_or_no'), 'FALSE') ?>
	</p>

	<p>Model (if your app will be interacting with the DB in a custom way or creating new DB tables)<br>
		<?= form_dropdown('app_model', config_item('yes_or_no'), 'FALSE') ?>
	</p>

	<p>Widgets (if your app will have widgets that are insertable in the public site)<br>
		<?= form_dropdown('app_widgets', config_item('yes_or_no'), 'FALSE') ?>
	</p>
	
	<p><input type="submit" name="submit" value="Create"></p>
</form>
<script type="text/javascript">
$(document).ready(function()
{
	$('#create_app').bind('submit', function(e)
	{
		e.preventDefault();	
		var app_data = $('#create_app').serializeArray();
	
		$.oauthAjax(
		{
			oauth 		: user_data,		
			url			: base_url + 'api/install/create_app',
			type		: 'POST',
			dataType	: 'json',
			data		: app_data,
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