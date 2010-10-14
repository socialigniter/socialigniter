<h3>Create</h3>

<p>By creating a site with this account you will able to administer more than one URL from this same dashboard. You will need FTP access to the new domain name.</p>

<form method="post" action="<?= base_url()."site/create";?>">

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
	<td>Google Webmaster:</td>
	<td><input type="text" name="" size="40" placeholder="f8bKMR-ZBS4oz44kKjUrqqH5gSoSJKDK-_sfCBg" value="<?= set_value('') ?>"></td>
</tr>

<tr>
	<td>Bing:</td>
	<td><input type="text" name="" size="40" placeholder="4F229B5D013A9B6B0B209B07C99D7E94" value="<?= set_value('') ?>"></td>
</tr>

<tr>		
	<td>Google Analytics:</td>
	<td colspan="2"><textarea name="description" cols="26" rows="3"><?= set_value('analytics') ?></textarea></td>
</tr>

<tr>		
	<td colspan="2"><input type="submit" value="Create Site" /></td>
</tr>
</table>

</form>