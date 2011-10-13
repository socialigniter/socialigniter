<html>
<head>
<title> API | <?= $site_title ?></title>
<style type="text/css">
body { background-color: #fff; font-family: Lucida Grande, Verdana, Sans-serif; font-size: 14px; color: #4F5155; }
a { color: #003399; background-color: transparent; font-weight: normal; }
h1 { color: #444; font-size: 21px; font-weight: bold; margin: 20px 0 20px 35px; padding: 5px 0 6px 0; }
code { font-family: Monaco, Verdana, Sans-serif; font-size: 12px; background-color: #f9f9f9; border: 1px solid #D0D0D0; color: #002166; display: block; margin: 14px 0; padding: 12px 10px; word-wrap: break-word; }
#sandbox_submit { position: absolute; top:0; left:0; width:100%; height: 180px; background-color:lightgrey; }
#sandbox_submit p { float: left; margin: 0 0 20px 35px;}
#sandbox_submit input[type=text] { margin: 0 10px 0 0; }
#sandbox_result { position: absolute; top: 190px; left: 35px;  width:94%; margin: 0 auto; padding: 0 0 20px 0 }
div.separator { border-bottom: 1px double #999999; }
</style>
<link rel="icon" type="image/png" href="<?= $site_images  ?>favicon.png" />
<script type="text/javascript" src="<?= base_url() ?>js/jquery.js"></script>
<script type="text/javascript" src="<?= base_url() ?>js/jquery.url.js"></script>
<script type="text/javascript" src="<?= base_url() ?>js/social.auth.js"></script>
<script type="text/javascript" src="<?= base_url() ?>js/social.core.js"></script>
<script type="text/javascript">
var user_data = {
	"user_id":"<?= $logged_user_id ?>",
	"username":"<?= $logged_username ?>",
	"user_level_id":"<?= $logged_user_level_id ?>",
	"name":"<?= $logged_name ?>",
	"image":"<?= $logged_image ?>",
	"location":"<?= $logged_location ?>",
	"geo_enabled":"<?= $logged_geo_enabled ?>",
	"geo_lat":"",
	"geo_long":"",
	"privacy":"<?= $logged_privacy ?>",	 
	"consumer_key": "<?= $oauth_consumer_key ?>",
	"consumer_secret": "<?= $oauth_consumer_secret ?>",
	"token": "<?= $oauth_token ?>",
	"token_secret": "<?= $oauth_token_secret ?>"
}

var base_url 		= '<?= base_url() ?>';
var current_module	= jQuery.url.segment(1);

$(document).ready(function()
{
	$('#sandbox_form').bind('submit', function(eve)
	{
		eve.preventDefault();
				
		$.oauthAjax(
		{
			oauth 		: user_data,		
			url			: base_url + 'api/sandbox',
			type		: 'POST',
			dataType	: 'html',
			data		: $('#sandbox_form').serializeArray(),
		  	success		: function(result)
		  	{		  				  	
				if(result.status == 'error')
				{
					$('#sandbox_result').html(result);
			 	}
			 	else
			 	{			 					 	
					$('#sandbox_result').html(result);
			 	}	
		 	}
		});
	});
});
</script>
</head>
<body>
<div id="sandbox_submit">
	<h1><?= $site_title ?> | API Sandbox</h1>
	<form id="sandbox_form" action="<?= base_url(); ?>api/sandbox" method="POST">	
		<p><?= base_url() ?>api/ <input type="text" name="uri" id="req_uri" value="<?= $this->input->post('uri') ?>" size="40"></p>
		<p><label>Params (query string): </label><input type="text" name="params" id="req_params" value="<?= $this->input->post('params') ?>" size="50"></p>
		<br style="clear:both" />
		<p style="width:15em">Method: 
		<select name="method" id="req_method">
			<option value="get">GET</option>
			<option value="post">POST</option>
			<option value="put">PUT</option>
			<option value="delete">DELETE</option>
		</select>
		</p>
		<p style="width:20em">Format: 
		<select name="format" id="req_format">
			<option value="json">json</option>
			<option value="xml">xml</option>
			<option value="serialize">serialize</option>
		</select>
		<p><input type="submit" name="go" value="Make Request"  /></p>
	</form>
</div>

<div id="sandbox_result">
	<?= $content ?>	
</div>
</body>
</html>