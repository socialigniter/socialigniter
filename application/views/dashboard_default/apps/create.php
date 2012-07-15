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
	
	<h3>Controllers & API</h3>
	<p>
		Add API methods to create, update, and delete from REST API <?= form_dropdown('app_api_methods', config_item('yes_or_no'), 'FALSE') ?><br>
		Add Connections Class for the following type of authentication 
		<select name="app_connections">
			<option value="FALSE">No</option>
			<option value="FALSE">OAuth 1</option>
			<option value="FALSE">OAuth 2</option>
		</select>
	</p>

	<h3>Helper & Libraries</h3>
	<p>
		Create Helper file for custom functions <?= form_dropdown('app_helper', config_item('yes_or_no'), 'FALSE') ?><br>
		Create object oriented Library template <?= form_dropdown('app_library', config_item('yes_or_no'), 'FALSE') ?><br>
		Add an OAuth Provider Library 
		<select name="app_oauth_provider">
			<option value="FALSE">No</option>
			<option value="FALSE">OAuth 1</option>
			<option value="FALSE">OAuth 2</option>
		</select>		
	</p>
	
	<h3>Database & Model</h3>
	<p>
		Add Model Class template to interact with database <?= form_dropdown('app_model', config_item('yes_or_no'), 'FALSE') ?><br>
		Create new Database table for that model <?= form_dropdown('app_model', config_item('yes_or_no'), 'FALSE') ?>
	</p>

	<h3>Widgets</h3>
	<p>
		My app will have public site Widgets <?= form_dropdown('app_widgets', config_item('yes_or_no'), 'FALSE') ?><br>
		Create a Widget template <?= form_dropdown('app_widget_template', config_item('yes_or_no'), 'FALSE') ?>
	</p>

	<input type="submit" name="submit" value="Create">
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