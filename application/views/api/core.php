<p><?= $site_title ?> has a standard REST API that supports GET, POST, PUT, and DELETE methods.</p>

<h3>Formats</h3>
<ul>
	<li>xml</li>
	<li>json</li>
	<li>serialize</li>
	<li>php (raw output)</li>
</ul>

<p>To change the format of an API call (default is JSON) append <b>/format/type</b> to end of url.<br>
Example: <a href="<?= base_url() ?>api/users/recent/format/xml" target="_blank"><?= base_url() ?>api/users/format/xml</a></p>

<hr>

<h2>Core</h2>

<h3>Activity</h3>
<ul>
	<li>Recent <a href="<?= base_url() ?>api/activity/recent" target="_blank"><?= base_url() ?>api/activity/recent</a></li>
	<li>View <a href="<?= base_url() ?>api/activity/view/user_id/1" target="_blank"><?= base_url() ?>api/activity/view/user_id/1</a> (accepts <b>site_id, user_id, verb, module,type,content_id</b>)</li>	
	<li>Create <a href="<?= base_url() ?>api/activity/create" target="_blank"><?= base_url() ?>api/activity/create</a></li>
	<li>Delete <a href="<?= base_url() ?>api/activity/destroy/id/1" target="_blank"><?= base_url() ?>api/activity/destroy/id/1</a> (1 represents <b>activity_id</b>)</li>
</ul>

<h3>Content</h3>
<ul>
	<li>Recent <a href="<?= base_url() ?>api/content/recent" target="_blank"><?= base_url() ?>api/content/recent</a></li>
	<li>View <a href="<?= base_url() ?>api/content/view/user_id/1" target="_blank"><?= base_url() ?>api/content/view/user_id/1</a> (accepts <b>site_id, parent_id, category_id, module, type, user_id</b>)</li>
	<li>Create <a href="<?= base_url() ?>api/content/create" target="_blank"><?= base_url() ?>api/content/create</a></li>
	<li>Modify <a href="<?= base_url() ?>api/content/modify/id/1" target="_blank"><?= base_url() ?>api/content/modify/id/1</a> (1 represents <b>content_id</b>)</li>
	<li>Delete <a href="<?= base_url() ?>api/content/destroy/id/1" target="_blank"><?= base_url() ?>api/content/destroy/id/1</a> (1 represents <b>content_id</b>)</li>
</ul>

<h3>Comments</h3>
<ul>
	<li>Recent <a href="<?= base_url() ?>api/comments/recent" target="_blank"><?= base_url() ?>api/comments/recent</a></li>
	<li>Content <a href="<?= base_url() ?>api/comments/content/id/1" target="_blank"><?= base_url() ?>api/comments/content/id/1</a> (1 represents <b>content_id</b>)</li>
	<li>Create <a href="<?= base_url() ?>api/comments/create" target="_blank"><?= base_url() ?>api/comments/create</a></li>
	<li>Delete <a href="<?= base_url() ?>api/comments/destroy/id/1" target="_blank"><?= base_url() ?>api/comments/destroy/id/1</a> (1 represents <b>comment_id</b>)</li>
</ul>

<h3>Categories</h3>
<ul>
	<li>All <a href="<?= base_url() ?>api/categories/all" target="_blank"><?= base_url() ?>api/categories/all</a></li>
	<li>View <a href="<?= base_url() ?>api/categories/view/module/pages" target="_blank"><?= base_url() ?>api/categories/view/module/pages</a> (accepts <b>site_id, parent_id, module, type, category_url</b> as a paramter)</li>
	<li>Create <a href="<?= base_url() ?>api/categories/create" target="_blank"><?= base_url() ?>api/categories/create</a> </li>
	<li>Update <a href="<?= base_url() ?>api/categories/update/id/1" target="_blank"><?= base_url() ?>api/categories/update/id/1</a> (1 represents <b>category_id</b>)</li>
	<li>Delete <a href="<?= base_url() ?>api/categories/destroy/id/1" target="_blank"><?= base_url() ?>api/categories/destroy/id/1</a> (1 represents <b>category_id</b>)</li>
</ul>

<h3>Locations</h3>
<ul>
	<li>Recent <a href="<?= base_url() ?>api/locations/nearby/45.23/-122.23" target="_blank"><?= base_url() ?>api/locations/nearby/45.23/-122.23</a> (45.23/-122.23 represents /lat/long)</li>
</ul>

<h3>Tags</h3>
<ul>
	<li>All <a href="<?= base_url() ?>api/tags/all" target="_blank"><?= base_url() ?>api/tags/all</a></li></ul>
	<li>Create <a href="<?= base_url() ?>api/tags/create" target="_blank"><?= base_url() ?>api/tags/create</a></li></ul>
	<li>Delete <a href="<?= base_url() ?>api/tags/destroy/tag_id/1" target="_blank"><?= base_url() ?>api/tags/destroy/tag_id/1</a> (1 represents either <b>tag_id, tag_link_id, or object_id</b>)</li>
</ul>

<h3>Users</h3>
<ul>
	<li>Recent <a href="<?= base_url() ?>api/users/recent" target="_blank"><?= base_url() ?>api/users/recent</a></li>
	<li>Single <a href="<?= base_url() ?>api/users/view/id/1" target="_blank"><?= base_url() ?>api/users/view/id/1</a></li>
	<li>Create <a href="<?= base_url() ?>api/users/create" target="_blank"><?= base_url() ?>api/users/create</a></li>
	<li>Update <a href="<?= base_url() ?>api/users/update/id/1" target="_blank"><?= base_url() ?>api/users/update/id/1</a></li>
</ul>

<h3>Settings</h3>
<ul>
	<li>Update <a href="<?= base_url() ?>api/settings/update" target="_blank"><?= base_url() ?>api/settings/update</a></li>
</ul>

<hr>

<h2>Modules</h2>

<?= $modules_apis ?>
