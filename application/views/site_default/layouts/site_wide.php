<!DOCTYPE html>  
<html>
<head>
<title><?= $this->social_igniter->title($sub_title, $page_title, $site_title) ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="title" content="<?= $this->social_igniter->title($sub_title, $page_title, $site_title) ?>" />
<meta name="description" content="<?= $site_description ?>" />
<meta name="keywords" content="<?= $site_keywords ?>" />
<meta name="google-site-verification" content="<?= $settings['site']['google_webmaster']; ?>" />
<meta name="msvalidate.01" content="<?= $settings['site']['bing_webmaster']; ?>" />

<link rel="icon" type="image/png" href="<?= base_url() ?>favicon.ico" />
<?= $head ?>
<?= $modules_head ?>

</head>
<body>
<div id="header">
	<div class="wrap">
		<div id="name">
			<a href="<?= base_url() ?>"><h1><?= $site_title; ?></h1></a>
		</div>
		<?= $logged ?>
		<div class="clear"></div>
		<ul id="main_nav">
			<li><a class="nav" href="#">Download</a></li>
			<li><a class="nav" href="#">About</a></li>
			<li><a class="nav" href="#">Developers</a></li>
			<li><a class="nav" href="#">Contact</a></li>
			<?= $navigation ?>
		</ul>
	</div>
</div>

<div id="content">
	<div class="wrap">
			<?= $content ?>
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