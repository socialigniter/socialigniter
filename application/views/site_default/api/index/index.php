<h1>Api</h1>

<p><?= $site_title ?> has a standard REST API that supports GET, POST, PUT, and DELETE methods.</p>

<h3>Testing</h3>

<p>To test <?= $site_title ?> API calls or any RESTful request we suggest using our testing tool<br>
<a href="<?= base_url() ?>api/testing" target="_blank"><?= base_url() ?>api/testing</a>
</p>

<ul>
<li><h3>Formats</h3></li>
<li>xml</li>
<li>json</li>
<li>serialize</li>
<li>php (raw output)</li>
</ul>

<p>To change the format of an API call (default is JSON) append <b>/format/type</b> to end of url.<br>
Example: <a href="<?= base_url() ?>api/users/recent/format/xml" target="_blank"><?= base_url() ?>api/users/format/xml</a></p>

<ul>
<?= $modules_apis ?>
</ul>