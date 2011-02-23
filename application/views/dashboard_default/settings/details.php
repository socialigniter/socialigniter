<h3>Details</h3>
<form method="post" action="<?= base_url() ?>settings/profile" enctype="multipart/form-data">
	<table border="0" cellpadding="0" cellspacing="0">
		<tr>		
			<td>Company:</td>
			<td colspan="2"><input type="text" name="company" placeholder="Company" size="40" value="<?= $company ?>"></td>
		</tr>
		<tr>		
			<td>Location:</td>
			<td colspan="2"><input type="text" name="location" size="40" placeholder="City, ST" value="<?= $location ?>"></td>
		</tr>
		<tr>
			<td>Website:</td>
			<td colspan="2"><input type="url" name="url" size="40" placeholder="http://website.com" value="<?= $url ?>"></td>
		</tr>
		<tr>		
			<td valign="top">Bio:</td>
			<td colspan="2"><textarea name="bio" cols="39" rows="6"><?= $bio ?></textarea></td>
		</tr>    
		<tr>		
			<td colspan="2"><input type="submit" value="Save" /></td>
		</tr>			
	</table>
</form>
