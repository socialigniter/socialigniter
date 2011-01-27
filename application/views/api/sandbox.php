<?= $debug ?>

<?php if(!empty($result)): ?>
<div class="separator"></div>
	<h2>PHP Result</h2>
	<p>A useable PHP array or object for use in your code.</p>		
	<pre>
	<?php var_dump($result); ?>
	</pre>
<?php endif; ?>

<p><br />Page rendered in {elapsed_time} seconds</p>