<?php
if ($_POST["hostname"])
{
	// Config
	$config_file	= "./application/config/config.php.TEMPLATE";
	$encryption_key	= sha1(microtime(true).mt_rand(10000,90000));
	$config_current	= file_get_contents($config_file, FILE_USE_INCLUDE_PATH);
	$config_current	= str_replace("{INSTALL_BASE_URL}", $_POST["base_url"], $config_current);
	$config_current	= str_replace("{INSTALL_ENCRYPTION_KEY}", $encryption_key, $config_current);
	file_put_contents("./application/config/config.php", $config_current);

	// Database
	$hostname 		= $_POST["hostname"];
	$username		= $_POST["username"];
	$password		= $_POST["password"];
	$database_name 	= $_POST["database"];
	$database_file 	= file_get_contents("./application/config/database.php.TEMPLATE", FILE_USE_INCLUDE_PATH);
	$database_file 	= str_replace("{INSTALL_DB_HOSTNAME}", $hostname, $database_file);
	$database_file 	= str_replace("{INSTALL_DB_USERNAME}", $username, $database_file);
	$database_file 	= str_replace("{INSTALL_DB_PASSWORD}", $password, $database_file);
	$database_file 	= str_replace("{INSTALL_DB_DATABASE}", $database_name, $database_file);
	file_put_contents("./application/config/database.php", $database_file);
	
	// Make Files
	copy("./application/config/routes.php.TEMPLATE","./application/config/routes.php");
	copy("./application/config/social_igniter.php.TEMPLATE","./application/config/social_igniter.php");
	
	echo json_encode(array('status' => 'success', 'message' => 'Created files & folders', 'data' => $_POST));
}
else
{
?>
<!DOCTYPE html>  
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Install Social-Igniter</title>

<style type="text/css">
html, body, div, span, applet, object, iframe, h1, h2, h3, h4, h5, h6, p, blockquote, pre, a, abbr, acronym, address, big, cite, code, del, dfn, em, img, ins, kbd, q, s, samp, small, strike, strong, sub, sup, tt, var, b, u, i, center, dl, dt, dd, ol, ul, li,
fieldset, form, label, legend, table, caption, tbody, tfoot, thead, tr, th, td, article, aside, canvas, details, embed,  figure, figcaption, footer, header, hgroup, menu, nav, output, ruby, section, summary, time, mark, audio, video { margin: 0; padding: 0; border: 0; font-size: 100%; font: inherit; vertical-align: baseline; } article, aside, details, figcaption, figure, footer, header, hgroup, menu, nav, section { display: block; } body { line-height: 1; }
ol, ul { list-style: none; } blockquote, q { quotes: none; } blockquote:before, blockquote:after, q:before, q:after { content: ''; content: none; } table { border-collapse: collapse; border-spacing: 0; }

/* Start App CSS */
body 		{ font-family: Helvetica, Arial, sans-serif; }
h1, h2, h3	{ font-family: "Trebuchet MS", Arial, Helvetica, sans-serif; font-size: 14px; color: #333; }
h1			{ color: #444; font-size: 36px; font-weight: bold; text-shadow:1px 1px 1px #FFF; }
h2			{ color: #444; font-size: 30px; font-weight: bold; text-shadow:1px 1px 1px #FFF; }
h3			{ color: #444; font-size: 24px; font-weight: bold; text-shadow:1px 1px 1px #FFF; }
p 			{ font-size: 14px; margin: 5px 0; line-height: 21px; }
.hide 		{ display: none; }
.clear		{ clear: both; }
#container	{ width: 700px; min-height: 350px; margin: 45px auto; padding: 0 25px 25px 25px; text-align: left; }
#logo		{ height: 125px; float: right; margin-left: 15px; margin-bottom: 20px; }
#welcome h1	{ float: left; }
#welcome p	{ float: left; width: 500px; margin-top: 20px; }

div.norm_top 				{ width: 700px; height: 12px; background: url('/application/views/dashboard_default/assets/images/content_norm_top.png') 0 0 no-repeat; margin: 0; }
div.norm_mid 				{ width: 700px; background: url('/application/views/dashboard_default/assets/images/content_norm_mid.png') 0 0 repeat-y; margin: 0 0; padding: 10px 0; }
div.norm_bot 				{ width: 700px; height: 12px; background: url('/application/views/dashboard_default/assets/images/content_norm_bot.png') 0 0 no-repeat; margin: 0 0 15px 0; }
div.norm_separator			{ width: 671px; height: 18px; margin: 6px auto 12px auto; background: url('/application/views/dashboard_default/assets/images/content_norm_separator.png') 0 0 no-repeat; }
div.content_wrap 			{ margin: 15px 25px 15px 25px; }
div.content_wrap h2			{ margin: 25px 0 25px 0; }
div.content_wrap h2 span	{ color: #999999; font-style: italic; }
div.content_wrap h3 		{ margin: 15px 0 15px 0; }
div.content_wrap h3 span	{ color: #999999; font-style: italic; }
div.content_wrap a 			{ color: #2078ce; text-decoration: none; }
div.content_wrap a:visited	{ color: #2078ce; text-decoration: underline; }
div.content_wrap a:hover	{ color: #2078ce; text-decoration: underline; }
div.content_wrap input[type=button]	{ margin: 0px 0px 25px 0px; }

</style>
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/social.core.js"></script>
<script type="text/javascript">
//<![CDATA[
$(document).ready(function()
{
	// Show Fresh Setup or Existing
	<?php if (file_exists('./application/config/config.php')): ?>
	$('#step_5').fadeIn();
	base_url = $.url.attr('protocol') + '://' + $.url.attr('host') + '/';
	$('#go_to_website').attr('href', base_url).html(base_url);
	$('#go_to_dashboard').attr('href', base_url + 'home').html(base_url + 'home');
	<?php else: ?>
	$('#step_1').fadeIn();
	base_url = $.url.attr('protocol') + '://' + $.url.attr('host') + '/';
	$('#base_url').val(base_url);	
	<?php endif; ?>

	$('#install_step_1').bind('submit', function(e)
	{	
		e.preventDefault();
		var step_1_data = $('#install_step_1').serialize();
		
		console.log(step_1_data);
		
		$.ajax(
		{
			url		: 'install.php',
			type	: 'POST',
			dataType: 'json',
			data	: step_1_data,
			success	: function(result)
			{
				console.log('Step 1');
				console.log(result);
				
				$('#step_1').fadeOut();
				$('#step_2').fadeIn();
				
				$.ajax(
				{
					url		: $('#base_url').val() + 'setup',
					type	: 'POST',
					dataType: 'json',
					data	: step_1_data,
					success	: function(result)
					{
						console.log('Step 2');
						console.log(result);

						$('#step_2').fadeOut();
						$('#step_3').fadeIn();				
					}
				});	
						
			}
		});
	});


	$("#install_step_3").bind('submit', function(e)
	{	
		e.preventDefault();	
		$.validator(
		{
			elements :		
				[{
					'selector' 	: '#signup_name', 
					'rule'		: 'require', 
					'field'		: 'Enter your name',
					'action'	: 'label'					
				},{
					'selector' 	: '#signup_email', 
					'rule'		: 'email', 
					'field'		: 'Please enter a valid email',
					'action'	: 'label'							
				},{
					'selector' 	: '#signup_password', 
					'rule'		: 'require', 
					'field'		: 'Please enter a password',
					'action'	: 'label'					
				},{
					'selector' 	: '#signup_password_confirm', 
					'rule'		: 'confirm', 
					'field'		: 'Please confirm your password',
					'action'	: 'label'					
				}],
			message : '',
			success	: function()
			{					
				var signup_data = $('#install_step_3').serialize();
				console.log(signup_data);
				
				$.ajax(
				{
					url			: $('#base_url').val() + 'setup/admin',
					type		: 'POST',
					dataType	: 'json',
					data		: signup_data,
			  		success		: function(result)
			  		{
			  			console.log('Step 3');
			  			console.log(result);
			  		
						$('#step_3').fadeOut();
						$('#step_4').fadeIn();
													
					
				 	}
				});
			}
		});
	});


					
	$('#install_step_4').bind('submit', function(e)
	{
		e.preventDefault();
		var step_4_data = $('#install_step_4').serialize();
	
		console.log(step_4_data);
	
		$.ajax(
		{
			url		: $('#base_url').val() + 'setup/site',
			type	: 'POST',
			dataType: 'json',
			data	: step_4_data,
			success	: function(result)
			{
				console.log('Step 4');
				console.log(result);
	
				base_url = $('#base_url').val();
	
				$('#go_to_website').attr('href', base_url).html(base_url);
				$('#go_to_dashboard').attr('href', base_url + 'home').html(base_url + 'home');

	
				$('#step_4').fadeOut();
				$('#step_5').fadeIn();					
				
			}
		});
	});	


});
//]]>
</script>
</head>
<body>
<div id="container">

	<div class="content norm_top"></div>	
	<div class="content norm_mid">	

		<div id="welcome" class="content_wrap">
			<img id="logo" src="images/si_logo.png">
			<h1>Install Social-Igniter</h1>
			<p>Welcome and thanks for downloading Social-Igniter, you have taken the first step to setting up your own little corner on the internet. Great job Ace, but first we need you to tell us a few things before your site will work properly.</p>
			<div class="clear"></div>
		</div>
	
		<div class="norm_separator"></div>
	
		<div class="content_wrap">
		
			<div id="step_1" class="hide">
			<form name="install_step_1" id="install_step_1" method="POST">
				<h2>Site URL</h2>
				<p>Of your website <input type="text" name="base_url" id="base_url" placeholder="http://example.com/"></p>
				<h2>Database Settings</h2>
				<p>The hostname of your database server. <input type="text" id="db_hostname" placeholder="localhost" name="hostname"></p>
				<p>The username used to connect to the database. <input type="text" id="db_username" placeholder="root" name="username"></p>
				<p>The password used to connect to the database. <input type="password" id="db_password" placeholder="" name="password"></p> 
				<p>The name of the database you want to connect to. <input type="text" id="db_database" placeholder="social-igniter" name="database"></p>
				<p><input type="submit" value="Continue"></p>
			</form>
			</div>
		
			<div id="step_2" class="hide">
				<h2 id="step_2_title">Creating Database...</h2>		
			</div>
		
			<div id="step_3" class="hide">
			<form name="install_step_3" id="install_step_3" method="POST">
				<h2>Your Information</h2>
				<p>Enter information for who will be owning or controlling this website</p>
				<table border="0" cellpadding="0" cellspacing="0">
					<tr>
					  <td>Name</td>
					  <td>
					  	<input type="text" name="name" id="signup_name" placeholder="Joe Smith" value="">
					  	<span id="signup_name_error"></span>
					  </td>
					</tr>
					<tr>
					  <td>Email</td>
					  <td>
					  	<input type="text" name="email" id="signup_email" placeholder="your@email.com" value="">
				  		<span id="signup_email_error"></span>
					  </td>
					</tr>
					<tr>  
					  <td>Password</td>
					  <td>
					  	<input type="password" name="password" id="signup_password" placeholder="********" value="">
					  	<span id="signup_password_error"></span>
					  </td>
					</tr>
					<tr>  
					  <td>Confirm Password</td>
					  <td>
					  	<input type="password" name="password_confirm" id="signup_password_confirm" placeholder="********" value="">
					  	<span id="signup_password_confirm_error"></span> 
					  </td>
					</tr>
					<tr> 
					  <td colspan="2"><input type="submit" name="submit" value="Signup"></td>
					</tr>
				</table>	
				<input type="hidden" name="session" value="1">
				<input type="hidden" name="remember" value="1">
			</form>
			</div>
			
			<div id="step_4" class="hide">
			<form name="install_step_4" id="install_step_4" method="POST">
				<h2>Website Information</h2>
				<p>So people know what website they are visiting :)</p>
				<p>Site Name<br> <input type="text" name="title" id="site_title" placeholder="My Awesome Website" value=""></p>
				<p>Tagline<br> <input type="text" name="tagline" id="site_tagline" placeholder="Where I Post All My Awesome Things" value=""></p>
				<p>Keywords<br> <input type="text" name="keywords" id="site_keywords" placeholder="awesome, things, pictures, videos, poems, watermelons, cats, ninjas" value=""></p> 
				<p>Description<br> <textarea name="description" id="site_description" placeholder="This is my awesome website where I post awesome stuff. Some of my favorite things are ninjas, watermelons, and cats" cols="40" rows="7"></textarea>
				<p><input type="submit" value="Continue"></p>
			</form>
			</div>	
		
			<div id="step_5" class="hide">
				<h2>Awesome!</h2>
				<p>Your site is now setup. Go em get em tiger!</p>
				<p>Go to your website <a id="go_to_website" href=""></a>
				<p>Go to your dashboard <a id="go_to_dashboard" href=""></a>
			</div>
	
		</div>
		
	</div>
	<div class="content norm_bot"></div>
</div>
</body>
</html>
<?php } ?>