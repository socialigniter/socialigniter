<form method="post" action="<?= base_url()."pages/edit/".$page->content_id ?>">

	<h3>Title</h3>
	<input type="text" name="title" size="60" value="<?= $page->title ?>">
	<div id="title_url_text" class="edit_in_place"><?= base_url().'pages/'.$page->title_url ?></div>

	<input type="hidden" name="title_url" size="60" value="<?= $page->title_url ?>">

	
	<h3>Layout</h3>
	<div id="layout_options">
	<?php foreach ($layouts as $layout): ?>
				
		<a id="layout_<?= $layout ?>" class="layout_picker <?php if ($layout == $page->details) echo 'layout_selected'; ?>" href="#"><?= $layout ?></a>
	
	<?php endforeach; ?>
	</div>
	<div class="clear"></div>

	<?= $wysiwyg ?>

    <h3>Tags</h3>             
    <input name="tags" type="text" id="tags" size="75" placeholder="Blogging, Internet, Web Design" />  

	<h3>Access</h3>
	<?= form_dropdown('access', config_item('access'), $page->access) ?>	

	
	 <h3>Comments</h3>
	<?= form_dropdown('comments_allow', config_item('comments_allow'), $page->comments_allow) ?>	

	<input type="hidden" name="details" size="12" id="layout" value="<?= $page->details ?>">
	
    <p><input name="publish" type="submit" id="publish" value="Publish" /> <input type="submit" id="save" name="save" value="Save" /></p>

</form>