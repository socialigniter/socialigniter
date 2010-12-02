<link rel="stylesheet" href="<?= base_url() ?>css/wysiwyg.css" type="text/css" />

<div id="wysiwyg_media">
	<a href="<?= base_url() ?>ajax/images" class="media_manager"><img src="<?= $dashboard_assets ?>icons/images_24.png" border="0" /></a>
	<a href="<?= base_url() ?>ajax/music" class="media_manager"><img src="<?= $dashboard_assets ?>icons/music_24.png" border="0" /></a>
	<a href="<?= base_url() ?>ajax/video" class="media_manager"><img src="<?= $dashboard_assets ?>icons/video_24.png" border="0" /></a>
</div>

<textarea name="<?= $wysiwyg_name ?>" id="<?= $wysiwyg_id ?>" class="<?= $wysiwyg_class ?>"><?= $wysiwyg_value ?></textarea>

<script type="text/javascript" src="<?= base_url() ?>js/jquery.wysiwyg.js"></script>
<script type="text/javascript">

$(function($)
{
	$('#<?= $wysiwyg_id ?>').wysiwyg({
		
		autoGrow: true,
	
		resizeOptions:
		{
			maxWidth	: 640,
			minWidth	: 640,
			minHeight	: 340
			
		},
		
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
