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

<div id="container">
	<div id="header">
		<div id="name">
			<a id="name_img" href="<?= base_url() ?>"><img src="<?= asset_images(); ?>/site/small_social-igniter.png" border="0" /></a>
			<a id="name_text" href="<?= base_url() ?>"><h1><?= $site_title ?></h1></a>
		</div>
		<div id="nav">
		<ul>
			<li class="extra_space"><a href="<?= base_url()."home"; ?>">Home</a></li>
			<li><a href="<?= base_url()."profile/".$this->session->userdata('username'); ?>">Profile</a></li>	
			<li><a href="<?= base_url()."settings/profile"; ?>">Settings</a></li>	
			<li><a href="<?= base_url().'login/logout'; ?>">Log Out</a></li>		
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
			<?= $modules_footer ?>
		</ul>
	</div>
</div>

<?= $settings['site']['google_analytics'] ?>
</body>
</html>