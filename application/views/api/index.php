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
<script type="text/javascript" src="<?= base_url() ?>js/jquery-1.5.1.js"></script>
<script type="text/javascript" src="<?= base_url() ?>js/jquery.url.js"></script>
<script type="text/javascript" src="<?= base_url() ?>js/social.core.js"></script>
<script type="text/javascript" src="<?= base_url() ?>js/oauth.js"></script>
<script type="text/javascript" src="<?= base_url() ?>js/sha1.js"></script>
<script type="text/javascript">
// oauthAjax plugin allows ajax request to be signed with oauth token
(function($)
{
	$.fn.oauthAjax = function(settings)
	{
		var oauth_consumer_key 		= '<?= $oauth_consumer_key ?>';
		var oauth_consumer_secret 	= '<?= $oauth_consumer_secret ?>';
		var oauth_token 			= '<?= $oauth_token ?>';
		var oauth_token_secret 		= '<?= $oauth_token_secret ?>';		

		var accessor = { 
			consumerSecret	: oauth_consumer_secret,
			tokenSecret		: oauth_token_secret,
		};	
		
		var parameters = [
			["oauth_consumer_key", oauth_consumer_key],
			["oauth_token", oauth_token]
		];
		
		// only works if settings.data is a map (i.e., { "foo": "bar", "blah" : "yuck" })
		for (var name in settings.data)
			parameters.push([name, settings.data[name]]);
				
		var message = {
			method: settings.type || "GET",
			action: settings.url,
			parameters: parameters
		}
		
		OAuth.setTimestampAndNonce(message);
		OAuth.SignatureMethod.sign(message, accessor);
		
		var oldBeforeSend = settings.beforeSend;
		settings.beforeSend = function(xhr) {
			xhr.setRequestHeader("Authorization", OAuth.getAuthorizationHeader("", message.parameters))
			if (oldBeforeSend) oldBeforeSend(xhr);
		};
	
		jQuery.ajax(settings);

		var element = this;
	};
})(jQuery);

var base_url 		= '<?= base_url() ?>';
var current_module	= jQuery.url.segment(1);

$(document).ready(function()
{
	$('#sandbox_form').bind('submit', function(eve)
	{
		eve.preventDefault();
				
		$(this).oauthAjax(
		{
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