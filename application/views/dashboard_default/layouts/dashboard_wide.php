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
<div id="container">
	<div id="header">
		<div id="name">
			<a id="name_img" href="<?= base_url() ?>"><img src="<?= $site_images ?>medium_logo.png" border="0" /></a>
			<a id="name_text" href="<?= base_url() ?>"><h1><?= $site_title ?></h1></a>
		</div>
		<div id="nav">
		<ul>
			<li class="extra_space"><a href="<?= $link_home ?>">Home</a></li>
			<li><a href="<?= $link_profile ?>">Profile</a></li>	
			<li><a href="<?= $link_settings ?>">Settings</a></li>	
			<li><a href="<?= $link_logout ?>">Log Out</a></li>		
		</ul>
		</div>
	</div>	
	<div id="content_wide">
		<div class="content wide_top"></div>
		<div class="content wide_mid">			
			<?= $navigation ?>
			<div class="clear"></div>
			<div class="wide_separator"></div>			
			<div class="content_wrap">
				<div id="content_message" class="message_normal"><?= $message ?></div>			
				<?= $content ?>
			</div>	
		</div>
		<div class="content wide_bot"></div>			
	</div>
	<div class="clear"></div>	
	<div id="footer">
		<ul>
			<?= $footer ?>
		</ul>
	</div>
</div>
<?= $google_analytics ?>
</body>
</html>