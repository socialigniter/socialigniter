<h3>Settings</h3>

<form method="post" action="<?= base_url()."pages/create";?>">

<table border="0" cellpadding="0" cellspacing="0">
<tr>
	<td>Title:</td>
	<td><input type="text" name="name" size="40" value="<?= set_value('site_title') ?>"></td>
</tr>
<tr>
	<td>Tagline:</td>
	<td><input type="text" name="name" size="40" value="<?= set_value('site_tagline') ?>"></td>
</tr>
<tr>
	<td>Domain:</td>
	<td><input type="text" name="detail_2" size="40" placeholder="http://website.com" value="<?= set_value('detail_2') ?>"></td>
</tr>
<tr>
	<td>Keywords:</td>
	<td><input type="text" name="detail_2" size="40" placeholder="Dogs, cats, birds, girrafes" value="<?= set_value('keywords') ?>"></td>
</tr>
<tr>		
	<td>Description:</td>
	<td colspan="2"><textarea name="description" cols="26" rows="6"><?= set_value('description') ?></textarea></td>
</tr>

<tr>		
	<td colspan="2"><input type="submit" value="Save" /></td>
	</tr>
	</table>

</form>