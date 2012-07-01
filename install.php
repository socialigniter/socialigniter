<?php
if ($_POST["hostname"]) 
{
	// Config
	$config_file	= "./application/config/config.php.TEMPLATE";
	$config_current	= file_get_contents($config_file, FILE_USE_INCLUDE_PATH);
	$config_current	= str_replace("http://domain.com/", $_POST["base_url"], $config_current);
	file_put_contents("./application/config/config.php", $config_current);
	
	// Database
	$hostname 		= $_POST["hostname"];
	$username		= $_POST["username"];
	$password		= $_POST["password"];
	$database_name 	= $_POST["database_name"];
	
	$database_file 	= file_get_contents("./application/config/database.php.TEMPLATE", FILE_USE_INCLUDE_PATH);
	$database_file 	= str_replace("['dev']['hostname'] = ''", "['dev']['hostname'] = '".$hostname."'", $database_file);
	$database_file 	= str_replace("['dev']['username'] = ''", "['dev']['username'] = '".$username."'", $database_file);
	$database_file 	= str_replace("['dev']['password'] = ''", "['dev']['password'] = '".$password."'", $database_file);
	$database_file 	= str_replace("['dev']['database'] = ''", "['dev']['database'] = '".$database_name."'", $database_file);
	
	file_put_contents("./application/config/database.php", $database_file);
	
	// Make Files
	copy("./application/config/routes.php.TEMPLATE","./application/config/routes.php");
	copy("./application/config/custom.php.TEMPLATE","./application/config/custom.php");
	copy("./application/config/social_igniter.php.TEMPLATE","./application/config/social_igniter.php");
	copy("./application/helpers/custom_helper.php.TEMPLATE","./application/helpers/custom_helper.php");
	
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
body { font-family: Helvetica, Arial, sans-serif; }

h1 { font-size: 36px; margin: 25px 0; }
h2 { font-size: 30px; margin: 25px 0; }
p { font-size: 14px; margin: 5px 0; line-height: 21px; }

.hide { display: none; }

div.separator {
	width: 100%;
	height: 1px;
	background: #999999;
	border-bottom: 1px solid #eeeeee;
	margin: 25px 0;
}

#container {
	width: 650px;
	margin: 45px auto;
	background: #d9d9d9;
	border-radius: 15px;
	border: 1px solid #999999;
	padding: 0 25px 25px 25px;
}

</style>

<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript">
//<![CDATA[
$(document).ready(function()
{

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
				console.log(result);
				
				$('#step_1').fadeOut();
				$('#step_2').fadeIn();
				
				$.ajax(
				{
					url		: $('#base_url').val() + 'setup',
					type	: 'GET',
					dataType: 'json',
					success	: function(result)
					{
						console.log(result);
						
						$('#step_2').fadeOut();
						$('#step_3').fadeIn();				
					}
				});			
			}
		});
	});


	$('#install_step_3').bind('submit', function(e)
	{
		e.preventDefault();
		$.ajax(
		{
			url		: $('#base_url').val() + 'setup/admin',
			type	: 'POST',
			dataType: 'json',
			data	: $('#install_step_3'),
			success	: function(result)
			{
				console.log(result);
			}
		});
	});	
	
});
//]]>
</script>
</head>
<body>
<div id="container">

	<h1>Install Social-Igniter</h1>
	<p>Howdi, you have taken the first step to setting up your own little corner on the internet, but first we need you to tell us a few things before your site can work properly.</p>

	<div class="separator"></div>

	<div id="step_1">
	<form name="install_step_1" id="install_step_1" method="POST" action="install.php">

		<h2>Site URL</h2>
		<p>URL of your website <input type="text" name="base_url" id="base_url" placeholder="http://example.com/"></p>

		<h2>Database Settings</h2>
		<p>The hostname of your database server. <input type="text" placeholder="localhost" name="hostname"></p>
		<p>The username used to connect to the database. <input type="text" placeholder="root" name="username"></p>
		<p>The password used to connect to the database. <input type="password" placeholder="" name="password"></p> 
		<p>The name of the database you want to connect to. <input type="text" placeholder="social-igniter" name="database_name"></p>

		<p><input type="submit" value="Continue"></p>
		
	</form>
	</div>

	<div id="step_2" class="hide">
		
		<h2>Creating Database</h2>
		
	</div>


	<div id="step_3" class="hide">
	<form name="install_step_3" method="POST" action="install">

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
		
	</form>
	</div>

</div>
</body>
</html>
<?php } ?>