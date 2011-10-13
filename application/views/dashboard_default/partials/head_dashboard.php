<link rel="alternate" type="application/rss+xml" title="<?= $site_title ?> RSS 2.0 Feed" href="<?= base_url() ?>feed" />
<link rel="alternate" type="application/rss+xml" title="<?= $site_title ?> RSS 2.0 Comments" href="<?= base_url() ?>feed/comments" />

<link type="text/css" href="<?= base_url() ?>css/common.css" rel="stylesheet" media="screen" />
<link type="text/css" href="<?= $dashboard_assets ?>style.css" rel="stylesheet" media="screen" />

<script type="text/javascript" src="<?= base_url() ?>js/jquery.js"></script>
<script type="text/javascript" src="<?= base_url() ?>js/jquery-ui.js"></script>
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

var base_url 		= jQuery.url.attr('protocol') + '://' +jQuery.url.attr('host') + '/';
var current_module	= jQuery.url.segment(1);
var core_modules	= jQuery.parseJSON('<?= json_encode(config_item('core_modules')) ?>');
var core_assets		= '<?= $dashboard_assets.'icons/' ?>';

$(document).ready(function()
{
	if ($('#content_message').html() != '') $('#content_message').notify({status:'success',message:$('#content_message').html()});
});
</script>
<script type="text/javascript" src="<?= $dashboard_assets ?>dashboard.js"></script>
