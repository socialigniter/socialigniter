<? echo "<?xml version='1.0'?>" ?>

<XRD xmlns='http://docs.oasis-open.org/ns/xri/xrd-1.0'>
	<Subject><?= $uri ?></Subject>
	<Alias><?= base_url() ?>profile/<?= $username ?></Alias>
	<Link rel="http://microformats.org/profile/hcard" type="text/html" href="<?= base_url() ?>profile/<?= $username ?>" />
	<Link rel='http://schemas.google.com/g/2010#updates-from'
		type='application/atom+xml'
		href='<?= base_url() ?>profile/<?= $username ?>/feed'/>
	<Link rel='webfinger#friend'
		href='<?= base_url() ?>profile/<?= $username ?>/add_friend/{uri}' />
	<? if (isset($screen_name)): ?>
	<Link rel='webfinger#twitter'
		href='http://www.twitter.com/<?= $screen_name ?>' />
	<? endif; ?>


</XRD>
