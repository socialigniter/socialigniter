<h3>Social Igniter Apps</h3>

<p>The following apps have been made and/or are approved by the Social Igniter project, thus they should be mostly bug free, stable, secure.</p>

<pre>http://social-igniter.com/api/apps/get</pre>

<p>The results of that API call should contain data like</p>

<pre>
[{
	"name":"Blog",
	"version":"0.7",
	"last_updated":"2011-03-21",
	"demo":"http://social-igniter.com/blog"
	"source":"http://github.com/socialigniter/blog"
	"download":"https://github.com/socialigniter/blog/zipball/master"
},
{
	"name":"Events",
	"version":"0.4",
	"last_updated":"2011-01-21",
	"demo":"http://social-igniter.com/events"
	"source":"http://github.com/socialigniter/events"
	"download":"https://github.com/socialigniter/events/zipball/master"
}
]
</pre>

<p>&nbsp;</p>

<h3>Github</h3>
<p>///// Do check for proper server support</p>

<p>Install any Social Igniter compatible app that exists as a Github repository. Just copy + paste the url where the repo is located</p>
<form method="post" action="<?= base_url() ?>api/site/custom">
	<p>Project Owner<br>
	<input type="text" name="app_url" placeholder="socialigniter" size="32">
	</p>
	
	<p>Project Name<br>
	<input type="text" name="app_url" placeholder="blog" size="32">
	</p>
	
	<p><input type="submit" name="submit" value="Download"></p>
</form>

<p>&nbsp;</p>


<h3>Custom Install</h3>
<p>Just enter the url to any Social Igniter compatible app that is in a zip file on a web server and we will download the app and install it.</p>
<form method="post" action="<?= base_url() ?>api/site/custom">
	<p>App Name<br>
	<input type="text" name="app_name" placeholder="super secret app" size="32">
	</p>
	
	<p>App URL<br>
	<input type="text" name="app_url" placeholder="http://jamesbond.com/super-secret-app.zip" size="32">
	</p>
	
	<p><input type="submit" name="submit" value="Download"></p>
</form>

<p>&nbsp;</p>

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