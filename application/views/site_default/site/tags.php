<h2>Tags</h2>

<?php foreach ($tags as $tag): ?>
	<p><a href="<?= base_url().'tags/'.$tag->tag_url ?>"><?= $tag->tag ?></a></p>
	<hr>
<?php endforeach; ?>