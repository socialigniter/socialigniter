<!DOCTYPE html>  
<html>
<head>
<title><?= $this->social_igniter->title($sub_title, $title, $site_title) ?></title>
<meta name="title" content="<?= $this->social_igniter->title($sub_title, $title, $site_title) ?>" />
<meta name="description" content="<?= $description ?>" />
<meta name="keywords" content="<?= $keywords ?>" />
<meta name="google-site-verification" content="<?= $webmaster; ?>" />
<meta name="msvalidate.01" content="<?= $bing; ?>" />
	
<link rel="icon" type="image/png" href="<?= base_url() ?>favicon.ico" />
<?= $head ?>

</head>
<body>

<div id="name">
	<a id="header_name_img" href="<?= base_url() ?>"><img src="<?= asset_images(); ?>/site/small_social-igniter.png" border="0" /></a>
	<a id="header_name_text" href="<?= base_url() ?>"><h1><?= $this->config->item('site_title') ?></h1></a>
</div>

<div class="separator"></div>

<div id="navigation">
<ul>
	<li class="extra_space"><a href="<?= base_url()."home"; ?>">Home</a></li>
	<li><a href="<?= base_url()."profile/".$this->session->userdata('username'); ?>">Profile</a></li>	
	<li><a href="<?= base_url()."settings/"; ?>">Settings</a></li>	
	<li><a href="<?= base_url().'login/logout'; ?>">Log Out</a></li>		
</ul>
</div>

<div id="content">
	<?= $navigation ?>
	<?= $content ?>
</div>


<div id="sidebar">
	<?= $sidebar ?>
</div>

<div class="separator"></div>

	<div id="footer">
		<ul>
			<li><a href="<?= base_url()."home"; ?>">Home</a></li>
			<?= $footer ?>
		</ul>
	</div>

<?= $analytics ?>
</body>
</html>