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


<?php if(!empty($result)): ?>
<div class="separator"></div>
	<h2>PHP Result</h2>
	<p>A useable PHP array or object for use in your code.</p>		
	<pre>
	<?php var_dump($result); ?>
	</pre>
<?php endif; ?>

<p><br />Page rendered in {elapsed_time} seconds</p>