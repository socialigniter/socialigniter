<link rel="icon" type="image/png" href="<?= $site_images ?>favicon.png" />
<link type="text/css" href="<?= base_url() ?>css/common.css" rel="stylesheet" media="screen" />
<style type="text/css">
body 		{ background: <?= make_css_background('background') ?>; }
a:link 		{ font-weight: normal; color: #<?= config_item('design_font_color_normal') ?>; text-decoration: none; line-height: 21px; }
a:visited	{ font-weight: normal; color: #<?= config_item('design_font_color_visited') ?>; text-decoration: none; }
a:hover		{ font-weight: normal; color:#<?= config_item('design_font_color_hover') ?>; text-decoration: underline; }
a:active	{ font-weight: normal; text-decoration: none; }

.content_container h1 a			{ font-weight: bold !important; color: #<?= config_item('design_font_color_normal') ?>; text-decoration: none; }
.content_container h2 a			{ font-weight: bold !important; color: #<?= config_item('design_font_color_normal') ?>; text-decoration: none; }
.content_container a:link 		{ font-weight: normal; font-weight: bold; color: #<?= config_item('design_link_color_normal') ?>; text-decoration: none; }
.content_container a:visited	{ font-weight: normal; font-weight: bold; color: #<?= config_item('design_link_color_visited') ?>; text-decoration: none; }
.content_container a:hover		{ font-weight: normal; font-weight: bold; color:#<?= config_item('design_link_color_hover') ?>; text-decoration: underline; }
.content_container a:active		{ font-weight: normal; font-weight: bold; text-decoration: none; }

#header 						{ width: 100%; height: 123px; margin: 0; background: <?= make_css_background('header') ?>; }
#header a, #footer a		 	{ color: #<?= config_item('design_header_link_color_normal') ?>; text-shadow: 1px 1px 1px #999999; font-size: 16px; font-weight: bold; letter-spacing: 1px; text-decoration: none; }
#header a:link, #footer a:link	{ color: #<?= config_item('design_header_link_color_normal') ?>; text-decoration: none; }
#header a:visited, #footer a:visited { color: #<?= config_item('design_header_link_color_visited') ?>; text-decoration: none; }
#header a:hover, #footer a:hover { color: #<?= config_item('design_header_link_color_hover') ?>; text-decoration: none; }
#header a:active, #footer a:active { text-decoration: underline; }
#footer 						{ width: 100%; position: relative; margin-top: -123px; height: 123px; clear:both; background: <?= make_css_background('header') ?>; font-size: 14px; }

#name 							{ width: 625px; height: 72px; display: block; position: relative; top: 40px; left: 0; float: left; }
#name a, #name a h1 			{ width: 100%; height: 100%; display: block; position: relative; top: 0px; left: 0px; font-size: 72px; line-height: 72px; color: #<?= config_item('design_header_link_color_normal') ?>; text-shadow: 1px 1px 1px #999999; }
#name a:hover, #name a:hover h1		{ color: #<?= config_item('design_header_link_color_hover') ?>; text-shadow: 1px 1px 1px #999999; }
</style>
<link type="text/css" href="<?= $site_assets ?>style.css" rel="stylesheet" media="screen" />
<script type="text/javascript" src="<?= base_url() ?>js/jquery.js"></script>
<script type="text/javascript" src="<?= base_url() ?>js/social.core.js"></script>
<script type="text/javascript">
//Global User Data:
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
var core_modules	= jQuery.parseJSON('<?= json_encode(config_item('core_modules')) ?>');
var core_assets		= '<?= $dashboard_assets.'icons/' ?>';
var site_assets		= '<?= $site_assets ?>';

$(document).ready(function()
{	
	// Hides Things
	$('.error').hide();

	if ($('#content_message').html() != '') $('#content_message').notify({status:'success',message:$('#content_message').html()});
});
</script>
