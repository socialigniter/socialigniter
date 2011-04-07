<? echo '<' ?>?xml version='1.0' encoding='UTF-8'?<? echo '>' ?>

<!-- NOTE: this host-meta end-point is a pre-alpha work in progress.   Don't rely on it. -->
<!-- Please follow the list at http://groups.google.com/group/webfinger -->
<XRD xmlns='http://docs.oasis-open.org/ns/xri/xrd-1.0' 
     xmlns:hm='http://host-meta.net/xrd/1.0'>
  <hm:Host xmlns='http://host-meta.net/xrd/1.0'><? preg_match('/\/\/(.*?)\//',base_url(), $matches) ?><?= $matches[1] ?></hm:Host>
  <Link rel='lrdd' 
        template='<?= base_url() ?>webfinger/{uri}'>
    <Title>Resource Descriptor</Title>
  </Link>
</XRD>
