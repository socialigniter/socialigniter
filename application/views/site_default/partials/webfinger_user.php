<? echo '<' ?>?xml version='1.0'?<? echo '>' ?>
<? $twitter_name = $this->load->module('twitter/home'); ?>
<XRD xmlns='http://docs.oasis-open.org/ns/xri/xrd-1.0'>
	<Subject><?= $uri ?></Subject>
	<Alias><?= base_url() ?>profile/<?= $username ?></Alias>
	<Link rel='http://microformats.org/profile/hcard'
    	href='<?= base_url() ?>profile/<?= $username ?>' />
    <Link rel='http://schemas.google.com/g/2010#updates-from'
  		type='application/atom+xml'
  		href='<?= base_url() ?>profile/<?= $username ?>/feed'/>
  	<? if ($twitter_name)
  		{ ?>
  	<Link rel'webfinger#twitter'
  		href='http://www.twitter.com/<?= $twitter_name->tyler_test() ?>' />
  		<?
  		}
  		?>

</XRD>
