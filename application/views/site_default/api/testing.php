<html>
<head>
<title> Testing | API | <?= $site_title ?></title>
<style type="text/css">
body { background-color: #fff; font-family: Lucida Grande, Verdana, Sans-serif; font-size: 14px; color: #4F5155; }
a { color: #003399; background-color: transparent; font-weight: normal; }
h1 { color: #444; font-size: 21px; font-weight: bold; margin: 20px 0 20px 35px; padding: 5px 0 6px 0; }
code { font-family: Monaco, Verdana, Sans-serif; font-size: 12px; background-color: #f9f9f9; border: 1px solid #D0D0D0; color: #002166; display: block; margin: 14px 0; padding: 12px 10px; word-wrap: break-word; }
#testing_submit { position: absolute; top:0; left:0; width:100%; height: 180px; background-color:lightgrey; }
#testing_submit p { float: left; margin: 0 0 20px 35px;}
#testing_submit input[type=text] { margin: 0 10px 0 0; }
#testing_result { position: absolute; top: 190px; left: 35px;  width:94%; margin: 0 auto; padding: 0 0 20px 0 }
div.separator { border-bottom: 1px double #999999; }
</style>
</head>
<body>

<div id="testing_submit">
	<h1><?= $site_title ?> | API Testing</h1>
	<form action="<?= base_url(); ?>api/testing" method="POST">	
		<p><?= base_url() ?>api/ <input type="text" name="uri" value="<?= $this->input->post('uri') ?>" size="40"></p>
		<p><label>Params (query string): </label><input type="text" name="params" value="<?= $this->input->post('params') ?>" size="50"></p>
		<br style="clear:both" />
		<p style="width:15em">Method: <?php echo form_dropdown('method', array('get'=>'GET', 'post' => 'POST', 'put' => 'PUT', 'delete' =>'DELETE'), $this->input->post('method')); ?></p>
		<p style="width:20em">Format: <?php echo form_dropdown('format', array('json'=>'json', 'xml'=>'xml', 'serialize'=>'serialize'), $this->input->post('format')); ?></p>
		<p><?php echo form_submit('go', 'Make Request'); ?></p>
	</form>
</div>

<div id="testing_result">

	<?= $debug ?>

	<?php if(!empty($result)): ?>
	<div class="separator"></div>
		<h2>PHP Result</h2>
		<p>A useable PHP array or object for use in your code.</p>		
		<pre>
		<?php var_dump($result); ?>
		</pre>
	<?php else: ?>
		<p>Try entering an API call like "users/recent" then fiddle with format to see what the API does.</p>	
	<?php endif; ?>
	
	<p><br />Page rendered in {elapsed_time} seconds</p>
</div>

</body>
</html>