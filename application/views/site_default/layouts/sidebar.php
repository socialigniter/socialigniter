<!DOCTYPE html>
<html>
<head>
<title><?= site_title($sub_title, $page_title, $site_title) ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="title" content="<?= site_title($sub_title, $page_title, $site_title) ?>" />
<meta name="description" content="<?= $site_description ?>" />
<meta name="keywords" content="<?= $site_keywords ?>" />
<meta name="google-site-verification" content="<?= $settings['services']['google_webmaster']; ?>" />
<meta name="msvalidate.01" content="<?= $settings['services']['bing_webmaster']; ?>" />

<!-- OpenGraph (Facebook) http://ogp.me -->
<meta property="og:title" content="<?= site_title($sub_title, $page_title, $site_title) ?>"/>
<meta property="og:type" content="website" />
<meta property="og:image" content="<?= $site_image ?>"/>
<meta property="og:url" content="<?= current_url() ?>"/>
<meta property="og:site_name" content="<?= config_item('site_title') ?>"/>
<meta property="og:description" content="<?= $site_description ?>">

<!-- RSS & Atom Feeds -->
<link rel="alternate" type="application/rss+xml" title="<?= $site_title ?> RSS 2.0 Feed" href="<?= base_url() ?>feed" />
<link rel="alternate" type="application/rss+xml" title="<?= $site_title ?> RSS 2.0 Comments" href="<?= base_url() ?>feed/comments" />

<?= $head ?>
</head>
<body>
<div id="header">
	<div class="wrap">
		<div id="name">
			<a href="<?= base_url() ?>"><h1><?= $site_title; ?></h1></a>
		</div>
		<?= $logged ?>
		<div class="clear"></div>
	</div>
</div>
<?= $navigation ?>


<div id="content">
	<div class="wrap">
		<div id="main">
			<div id="content_message" class="message_normal"><?= $message ?></div>
			<?= $content ?>
		</div>
		<div id="sidebar">
			<?= $sidebar ?>
		</div>
		<div class="clear"></div>
		<div id="main_wide">
			<?= $wide ?>
		</div>	
	</div>
</div>

<div id="footer">
	<div class="wrap">		
		<?= $footer ?>
	</div>
	<div class="clear"></div>
</div>
<?= $google_analytics ?>
</body>
</html>