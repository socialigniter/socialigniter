<form method="post" action="<?= base_url()."pages/create";?>">

	<h3>Title</h3>
	<input type="text" name="title" size="60" value="<?= set_value('title') ?>">
	
	<h3>Layout</h3>
	<div id="layout_options">
	<?php foreach ($layouts as $layout): ?>
				
		<a id="layout_<?= $layout ?>" class="layout_picker <?php if ($layout == $details) echo 'layout_selected'; ?>" href="#"><?= $layout ?></a>
	
	<?php endforeach; ?>
	</div>
	<div class="clear"></div>	

	<?= $wysiwyg ?>
	
    <h3>Tags</h3>             
    <input name="tags" type="text" id="tags" size="75" placeholder="Blogging, Internet, Web Design" /> 	
	
	<h3>Access</h3>
	<select name="access" id="access">
	 	<option value="E">Everyone</option>
	    <option value="M">Only Me</option>
	    <option value="S">Specify</option>
	</select>
	
	 <h3>Comments</h3>             
	 <select name="comments_allow" id="comments_allow">
	 	<option value="Y">Allow</option>
	    <option value="N">Don't Allow</option>
	    <option value="A">Require Approval</option>
	</select>

	<?php if ($social_post): ?>
	<h3>Publish</h3>
	<?= $social_post ?>
	<div class="clear"></div>
	<?php endif; ?>

	<input type="hidden" name="details" size="12" id="layout" value="<?= $details ?>">	

    <p><input name="publish" type="submit" id="publish" value="Publish" /> <input type="submit" id="save" name="save" value="Save" /></p>

</form>