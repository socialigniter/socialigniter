<h1>Api</h1>

<p><?= $site_title ?> has a standard REST API that supports GET, POST, PUT, and DELETE methods.</p>

<h3>Sandbox</h3>

<p>To test <?= $site_title ?> API calls or any RESTful request we suggest using our testing tool<br>
<a href="<?= base_url() ?>api/sandbox" target="_blank"><?= base_url() ?>api/sandbox</a>
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


<h2>Core</h2>

<ul>
	<li><h3>Activity</h3></li>
	<li>Recent <a href="<?= base_url() ?>api/activity/recent" target="_blank"><?= base_url() ?>api/activity/recent</a></li>
	<li>Create <a href="<?= base_url() ?>api/activity/create/id/1" target="_blank"><?= base_url() ?>api/activity/create/id/1</a> (1 represents activity_id)</li>
	<li>Delete <a href="<?= base_url() ?>api/activity/destroy/id/1" target="_blank"><?= base_url() ?>api/activity/destroy/id/1</a> (1 represents activity_id)</li>
</ul>

<ul>
	<li><h3>Content</h3></li>
	<li>Recent <a href="<?= base_url() ?>api/content/recent" target="_blank"><?= base_url() ?>api/content/recent</a></li>
	<li>View <a href="<?= base_url() ?>api/content/content/id/1" target="_blank"><?= base_url() ?>api/content/view/id/1</a> (1 represents content_id)</li>
	<li>Create <a href="<?= base_url() ?>api/content/create/id/1" target="_blank"><?= base_url() ?>api/content/create/id/1</a> (1 represents content_id)</li>
	<li>Update <a href="<?= base_url() ?>api/content/update/id/1" target="_blank"><?= base_url() ?>api/content/update/id/1</a> (1 represents content_id)</li>
	<li>Delete <a href="<?= base_url() ?>api/content/destroy/id/1" target="_blank"><?= base_url() ?>api/content/destroy/id/1</a> (1 represents content_id)</li>
</ul>

<ul>
	<li><h3>Categories</h3></li>
	<li>All <a href="<?= base_url() ?>api/categories/recent" target="_blank"><?= base_url() ?>api/categories/all</a></li>
	<li>Search <a href="<?= base_url() ?>api/categories/content/id/1" target="_blank"><?= base_url() ?>api/categories/search/module/pages</a> ('search' accepts module, type, category_url, site_id as a paramter)</li>
	<li>Create <a href="<?= base_url() ?>api/categories/create" target="_blank"><?= base_url() ?>api/categories/create</a> </li>
	<li>Update <a href="<?= base_url() ?>api/categories/update/id/1" target="_blank"><?= base_url() ?>api/categories/update/id/1</a> (1 represents category_id)</li>
	<li>Delete <a href="<?= base_url() ?>api/categories/destroy/id/1" target="_blank"><?= base_url() ?>api/categories/destroy/id/1</a> (1 represents category_id)</li>
</ul>

<hr>

<h2>Modules</h2>

<ul>
<?= $modules_apis ?>
</ul>