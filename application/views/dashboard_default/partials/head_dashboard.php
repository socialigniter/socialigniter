<link rel="alternate" type="application/rss+xml" title="<?= $site_title ?> RSS 2.0 Feed" href="<?= base_url() ?>feed" />
<link rel="alternate" type="application/rss+xml" title="<?= $site_title ?> RSS 2.0 Comments" href="<?= base_url() ?>feed/comments" />

<link type="text/css" href="<?= base_url() ?>css/common.css" rel="stylesheet" media="screen" />
<link type="text/css" href="<?= $dashboard_assets ?>style.css" rel="stylesheet" media="screen" />
<link type="text/css" href="<?= base_url() ?>css/uniform.default.css" rel="stylesheet" media="screen" charset="utf-8" />
<link type="text/css" href="<?= base_url() ?>css/jplayer.css" rel="stylesheet" media="screen" charset="utf-8" />
<link type="text/css" href="<?= base_url() ?>css/fancybox.css" rel="stylesheet" media="screen" charset="utf-8" />
<link type="text/css" href="<?= base_url() ?>css/uploadify.css" rel="stylesheet" media="screen" charset="utf-8" />

<script type="text/javascript" src="<?= base_url() ?>js/jquery-1.4.4.min.js"></script>
<script type="text/javascript" src="<?= base_url() ?>js/jquery.NobleCount.min.js"></script>  
<script type="text/javascript" src="<?= base_url() ?>js/jquery.uniform.min.js"></script>
<script type="text/javascript" src="<?= base_url() ?>js/jquery.timers.js"></script>
<script type="text/javascript" src="<?= base_url() ?>js/jquery.fancybox.js"></script>
<script type="text/javascript" src="<?= base_url() ?>js/jquery.jplayer.min.js"></script>
<script type="text/javascript" src="<?= base_url() ?>js/jquery.url.js"></script>
<script type="text/javascript" src="<?= base_url() ?>js/oauth.js"></script>
<script type="text/javascript" src="<?= base_url() ?>js/sha1.js"></script>
<script type="text/javascript" src="<?= base_url() ?>js/jquery.oauthajax.js"></script>
<script type="text/javascript" src="<?= base_url() ?>js/swfobject.js""></script>
<script type="text/javascript" src="<?= base_url() ?>js/jquery.uploadify.v2.1.4.min.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
		$('#file_upload').uploadify({
		  'uploader'  : '/js/uploadify.swf',
		  'script'    : '/application/modules/media/models/uploadify.php',
		  'cancelImg' : '/images/cancel.png',
		  'folder'    : '/media/images/1',
		  'auto'      : true,
		  'multi'     : true,
		  'wmode'     : 'transparent',
		  'removeCompleted':false
		});
	 });
</script>
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
	"geo_accuracy":"",
	"privacy":"<?= $logged_privacy ?>",	 
	"consumer_key": "<?= $oauth_consumer_key ?>",
	"consumer_secret": "<?= $oauth_consumer_secret ?>",
	"token": "<?= $oauth_token ?>",
	"token_secret": "<?= $oauth_token_secret ?>"
}
</script>
<script type="text/javascript" src="<?= base_url() ?>js/social.core.js"></script>
<script type="text/javascript" src="<?= $dashboard_assets ?>dashboard.js"></script>