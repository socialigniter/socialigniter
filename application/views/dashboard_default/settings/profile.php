<h3>Profile</h3>
		
<form method="post" action="<?= base_url()."settings/profile"; ?>" enctype="multipart/form-data">
	<table border="0" cellpadding="0" cellspacing="0">
		<tr>
			<td>Picture:</td>
			<td><?= display_image("", "", asset_profiles().$this->session->userdata('user_id')."/small_", $image, asset_profiles()."small_nopicture.png", $name."'s profile picture") ?></td>
			<td><input type="file" size="20" name="userfile"></td>
		</tr>
		<?php if ($image != '') { ?>
		<tr>
			<td></td>
			<td colspan="2"><?= form_checkbox($delete_pic); ?> Delete picture</td>
		</tr>
		<?php } ?>
		<tr>
			<td>Name:</td>
			<td colspan="2"><input type="text" name="name" placeholder="Your Name" size="40" value="<?= set_value('name', $name) ?>"></td>
		</tr>
		<tr>		
			<td>Company:</td>
			<td colspan="2"><input type="text" name="company" placeholder="Company" size="40" value="<?= set_value('company', $company) ?>"></td>
		</tr>
		<tr>		
			<td>Location:</td>
			<td colspan="2"><input type="text" name="location" size="40" placeholder="City, ST" value="<?= set_value('location', $location) ?>"></td>
		</tr>
		<tr>
			<td>Website:</td>
			<td colspan="2"><input type="url" name="url" size="40" placeholder="http://website.com" value="<?= set_value('url', $url) ?>"></td>
		</tr>
		<tr>		
			<td valign="top">Bio:</td>
			<td colspan="2"><textarea name="bio" cols="26" rows="6"><?= set_value('bio', $bio) ?></textarea></td>
		</tr>    
		<tr>		
			<td colspan="2"><input type="submit" value="Save" /></td>
		</tr>			
	</table>
</form>
