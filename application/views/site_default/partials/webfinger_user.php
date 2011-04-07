<? echo '<' ?>?xml version='1.0'?<? echo '>' ?>

<XRD xmlns='http://docs.oasis-open.org/ns/xri/xrd-1.0'>
	<Subject><?= $uri ?></Subject>
	<Alias><?= base_url() ?>profile/<?= $username ?></Alias>
	<Link rel='http://microformats.org/profile/hcard'
    	href='<?= base_url() ?>profile/<?= $username ?>' />
</XRD>