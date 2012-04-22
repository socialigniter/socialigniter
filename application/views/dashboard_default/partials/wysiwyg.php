<link rel="stylesheet" href="<?= base_url() ?>css/wysiwyg.css" type="text/css" />
<?php if ($wysiwyg_media): ?>
<div id="wysiwyg_media">
	<?= $this->social_igniter->scan_media_manager(); ?>
</div>
<?php endif; ?>

<p><textarea name="<?= $wysiwyg_name ?>" id="<?= $wysiwyg_id ?>" class="<?= $wysiwyg_class ?>"><?= $wysiwyg_value ?></textarea></p>
<script type="text/javascript" src="<?= base_url() ?>js/jquery.wysiwyg.js"></script>
<?php if ($wysiwyg_js): ?>
<script type="text/javascript">
$(document).ready(function()
{
	$('#<?= $wysiwyg_id ?>').wysiwyg(
	{	
		autoGrow: true,
		resizeOptions: { maxWidth : <?= $wysiwyg_width ?>, minWidth : <?= $wysiwyg_width ?>, minHeight : <?= $wysiwyg_height ?> },
		controls:
		{
			separator				: { visible : false },
			strikeThrough			: { visible : true },
			underline 				: { visible : true },
			justifyLeft				: { visible : true },
			justifyCenter			: { visible : true },
			justifyRight			: { visible : true },
			justifyFull				: { visible : false },
			indent					: { visible : true },
			outdent					: { visible : true },
			subscript				: { visible : false },
			superscript				: { visible : false },
			undo					: { visible : true },
			redo					: { visible : true },
			insertImage				: { visible : false },
			insertOrderedList		: { visible : true },
			insertUnorderedList		: { visible : true },
			insertHorizontalRule	: { visible : true },
			cut						: { visible	: true },
			copy					: { visible : true },
			paste					: { visible : true },
			html					: { visible : true }	
		}
	});
});
</script>
<?php endif; ?>