<!DOCTYPE html>  
<html>
<head>
<title><?= site_title($sub_title, $page_title, $site_title) ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="title" content="<?= site_title($sub_title, $page_title, $site_title) ?>" />
<meta name="description" content="<?= $site_description ?>" />
<meta name="keywords" content="<?= $site_keywords ?>" />
<meta name="google-site-verification" content="<?= $settings['site']['google_webmaster']; ?>" />
<meta name="msvalidate.01" content="<?= $settings['site']['bing_webmaster']; ?>" />
<link rel="icon" type="image/png" href="<?= $site_assets ?>favicon.ico" />
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
		<?= $navigation ?>
	</div>
</div>

<div id="content">
	<div class="wrap">
		<div id="main">
			<?= $content ?>
		</div>
		<div id="sidebar">
			<?= $sidebar_profile ?>
		</div>
	</div>
	<div class="clear"></div>
</div>

<div id="footer">
	<div class="wrap">		
		<?= $footer ?>
	</div>
</div>
<?= $settings['site']['google_analytics'] ?>
</body>
</html>