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
	<a href="<?= base_url() ?>"><h1><?= $site_title ?></h1></a>
</div>

<div class="separator"></div>

<?= $logged ?>

<div class="separator"></div>

<ul id="navigation">
	<li><a class="nav" href="#">Download</a></li>
	<li><a class="nav" href="#">About</a></li>
	<li><a class="nav" href="#">Developers</a></li>
	<li><a class="nav" href="#">Contact</a></li>
	<?= $navigation ?>
</ul>

<div class="separator"></div>

<div id="main">
	<?= $content ?>
</div>

<div class="separator"></div>

<div id="sidebar">
	<?= $sidebar ?>
</div>

<div class="separator"></div>

<div id="footer">
	<?= $footer ?>
</div>

<?= $analytics ?>
</body>
</html>