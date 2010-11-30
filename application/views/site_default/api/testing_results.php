<h2>REST Test</h2>

<div class="separator"></div>

<h3>Request</h3>
<p><?= $request_url ?><p>

<div class="separator"></div>

<h3>Response</h3>

<?php if($response_string): ?>
	<code><?= nl2br(htmlentities($response_string)) ?></code><br/>
<?php else: ?>
	<code>No response</code><br/>
<?php endif; ?>

<div class="separator"></div>
<?php if($error_string): ?>
	<h3>Errors</h3>
	<strong>Code:</strong> <?= $error_code ?><br/>
	<strong>Message:</strong> <?= $error_string ?><br/>
<div class="separator"></div>
<?php endif; ?>

<h3>Response Details</h3>
<pre>
<?php print_r($info); ?>
</pre>