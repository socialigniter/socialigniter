<div class="content_norm_top"></div>	
<div class="content_norm_mid">
			
		<form method="post" action="<?= base_url()."settings/profile"; ?>" enctype="multipart/form-data">
			<table border="0" cellpadding="0" cellspacing="0" class="profile_settings shadow">
				<tr>
					<td valign="top">Picture:</td>
					<td>
						<?= display_image("", "", asset_profiles().$this->session->userdata('user_id')."/small_", $image, asset_profiles()."small_nopicture.png", $name."'s profile picture") ?>
						<input type="file" size="20" name="userfile" style="vertical-align: top; margin-top: 10px;">
					</td>
				</tr>
				<?php if ($image != '') { ?>
				<tr>
					<td></td>
					<td><?= form_checkbox($delete_pic); ?> Delete picture</td>
				</tr>
				<?php } ?>
				<tr>
					<td>Name:</td>
					<td><input type="text" name="name" size="29" placeholder="Your Name" value="<?= set_value('name', $name) ?>"></td>
				</tr>
				<tr>		
					<td>Company:</td>
					<td><input type="text" name="company" size="29" placeholder="Company" value="<?= set_value('company', $company) ?>"></td>
				</tr>
				<tr>		
					<td>Location:</td>
					<td><input type="text" name="location" size="29" placeholder="City, ST" value="<?= set_value('location', $location) ?>"></td>
				</tr>
				<tr>
					<td>Website:</td>
					<td><input type="url" name="url" size="29" placeholder="http://website.com" value="<?= set_value('url', $url) ?>"></td>
				</tr>
				<tr>		
					<td valign="top">Bio:</td>
					<td><textarea name="bio" cols="22" rows="6"><?= set_value('bio', $bio) ?></textarea></td>
				</tr>    
				<tr>		
					<td colspan="2"><input type="submit" value="Save" class="button btn_save" /></td>
				</tr>			
			</table>
		</form>

</div>
<div class="content_norm_bot"></div>