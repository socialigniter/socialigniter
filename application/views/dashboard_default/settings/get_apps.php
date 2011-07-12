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

<h3>Github</h3>
<p>///// Do check for proper server support</p>

<p>Install any Social Igniter compatible app that exists as a Github repository. Just copy + paste the url where the repo is located</p>
<form method="post" action="<?= base_url() ?>api/install/custom">
	<p>Project Owner<br>
	<input type="text" name="app_url" placeholder="socialigniter" size="32">
	</p>
	
	<p>Project Name<br>
	<input type="text" name="app_url" placeholder="blog" size="32">
	</p>
	
	<p><input type="submit" name="submit" value="Download"></p>
</form>

<h3>Custom Install</h3>
<p>Just enter the url to any Social Igniter compatible app that is in a zip file on a web server and we will download the app and install it.</p>
<form method="post" action="<?= base_url() ?>api/install/custom">
	<p>App Name<br>
	<input type="text" name="app_name" placeholder="super secret app" size="32">
	</p>
	
	<p>App URL<br>
	<input type="text" name="app_url" placeholder="http://jamesbond.com/super-secret-app.zip" size="32">
	</p>
	
	<p><input type="submit" name="submit" value="Download"></p>
</form>