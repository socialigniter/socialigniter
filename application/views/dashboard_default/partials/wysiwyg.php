<link rel="stylesheet" href="<?= base_url() ?>css/redactor.css">
<?php if ($wysiwyg_media): ?>
<div id="wysiwyg_media"><?= $this->social_igniter->scan_media_manager(); ?></div>
<?php endif; ?>
<script src="<?= base_url() ?>js/redactor.min.js"></script>
<?php if ($wysiwyg_js): ?>
<script type="text/javascript">
$(document).ready(function()
{
	$('#<?= $wysiwyg_id ?>').redactor(
	{
		buttons: [ 
			'formatting', '|', 
			'bold', 'italic', 'deleted', '|', 
			'unorderedlist', 'orderedlist', 'outdent', 'indent', '|',
			'image', 'video', 'table', 'link', '|', 
			'fontcolor', 'backcolor', '|', 
			'alignleft', 'aligncenter', 'alignright', '|',
			'html', '|', 
			'fullscreen'
		],
		autoresize: true,
		removeStyles: true
	});
});
</script>
<p><textarea id="<?= $wysiwyg_id ?>" name="<?= $wysiwyg_name ?>" class="<?= $wysiwyg_class ?>" style="height: 300px;"><?= $wysiwyg_value ?></textarea></p>
<?php endif; ?>