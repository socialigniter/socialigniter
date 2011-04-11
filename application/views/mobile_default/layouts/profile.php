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
<link rel="icon" type="image/png" href="<?= $site_images ?>favicon.png" />
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
		<div id="main_profile">
			<div class="content norm_top"></div>	
			<div class="content norm_mid">		
				<?= $content ?>
			</div>
			<div class="content norm_bot"></div>			
		</div>
		<div id="profile_sidebar">
			<div class="profile_sidebar_top"></div>	
			<div class="profile_sidebar_mid">		
				<?= $sidebar_profile ?>
			</div>
			<div class="profile_sidebar_bot"></div>
		</div>
	</div>
	<div class="clear"></div>
</div>

<div id="footer">
	<div class="wrap">		
		<?= $footer ?>
	</div>
</div>
<?= $settings['services']['google_analytics'] ?>
</body>
</html>