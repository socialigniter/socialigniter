<?php
include 'findbyemail/webfinger.php';
$id = "tyler@social.pdxbrain.com";
$webfinger = webfinger_find_by_email($id);
echo $webfinger['webfinger']['display_name'];
?>