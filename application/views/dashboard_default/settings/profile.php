<form method="post" name="user_profile" id="user_profile" action="<?= base_url() ?>api/users/modify/id/<?= $logged_user_id ?>" enctype="multipart/form-data">
	<table border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td>Picture</td>
		<td>
		<div id="profile_picture">
			<img id="profile_thumbnail" src="<?= $thumbnail ?>" border="0">
		</div>
		<div id="profile_picture_uploader">
		<ul id="profile_picture_container" class="item_actions_list">
		<?php if ($image): ?>
			<li><a id="pickfiles" href="#"><span class="actions action_edit"></span> Change Picture</a></li>
			<li><a id="delete_picture" href="#"><span class="actions action_delete"></span> Delete Picture</a></li>
		<?php else: ?>
			<li><a id="pickfiles" href="#"><span class="actions action_upload"></span> Upload A Picture</a></li>
			<li class="small_details"><span class="actions_blank"></span> <?= config_item('users_images_max_size') / 1024 ?> MB max size in these formats <?= strtoupper(str_replace('|', ', ', config_item('users_images_formats'))) ?></li>			
		<?php endif; ?>
		</ul>
		</div>	
		</td>
	</tr>
	<tr>
		<td>Name</td>
		<td colspan="2"><input type="text" name="name" placeholder="Your Name" size="40" value="<?= $name ?>"></td>
	</tr>
    <tr>
		<td>Username</td>
		<td><input type="text" name="username" size="40" value="<?= set_value('username', $username) ?>"></td>
	</tr>
    <tr>
		<td>Email</td>
		<td><input type="email" name="email" size="40" value="<?= set_value('email', $email) ?>"></td>
	</tr>
	<tr>
		<td>Language</td>
		<td><?= form_dropdown('language', config_item('languages'), $language); ?></td>		
	</tr>
	<tr>
		<td>Timezone</td>
		<td><?= timezone_menu($time_zone); ?></td>
	</tr>
	<tr>
		<td>Geo</td>
		<td><input type="checkbox" name="geo_enabled" value="<?= $geo_enabled ?>"> Add my location to content & updates</td>
	</tr>		
  	<tr>
  		<td>Privacy</td>
  		<td><input type="checkbox" name="privacy" value="<?= $privacy ?>"> Keep my profile private</td>
  	</tr>
	</table>
	<p><input type="submit" value="Save"></p>
</form>
<script type="text/javascript" src="<?= base_url() ?>js/plupload.js"></script>
<script type="text/javascript" src="<?= base_url() ?>js/plupload.html5.js"></script>
<script type="text/javascript" src="<?= base_url() ?>js/plupload.flash.js"></script>
<script type="text/javascript">
$(document).ready(function()
{
	var uploader_parent		= '#profile_picture_uploader';
	var uploader_list		= '#profile_picture_container';
	var uploader_create 	= '<ul id="profile_picture_container" class="item_actions_list"><li><a id="pickfiles" href="#"><span class="actions action_upload"></span> Upload A Picture</a></li><li class="small_details"><span class="actions_blank"></span> <?= config_item('users_images_max_size') / 1024 ?> MB max size in these formats <?= strtoupper(str_replace('|', ', ', config_item('users_images_formats'))) ?></li></ul>';
	var uploader_change 	= '<ul id="profile_picture_container" class="item_actions_list"><li><a id="pickfiles" href="#"><span class="actions action_edit"></span> Change Picture</a></li><li><a id="delete_picture" href="#"><span class="actions action_delete"></span> Delete Picture</a></li></ul>';
	var uploader_working	= '<ul id="profile_picture_container" class="item_actions_list"><li><span class="actions action_sync"></span> Uploading: <span id="file_uploading_progress"></span><span id="file_uploading_name"></span></li></ul>';

	// Uploader Params
	var uploader = new plupload.Uploader(
	{
		runtimes : 'html5,flash',
		browse_button : 'pickfiles',
		container : 'container',
		max_file_size : '<?= config_item('users_images_max_size') / 1024 ?>mb',
		max_file_count: 1,
		url : base_url + 'api/users/upload_profile_picture/id/' + user_data.user_id,
		flash_swf_url : base_url + 'js/plupload.flash.swf',
		multipart : true,
		multipart_params : {'file_hash':'','upload_id':'','consumer_key':user_data.consumer_key},		
		filters : [
			{title : "Image files", extensions : "jpg,gif,png"}
		]
	});

	uploader.bind('Init', function(up, params){});
	uploader.init();

	// Add Files & Start Upload
	uploader.bind('FilesAdded', function(up, files)
	{		
		var file_hash		= md5(files[0].name);
		var picture_data 	= $('#user_profile').serializeArray();
		picture_data.push({'name':'file_hash','value':file_hash});
		
		// Create Expectation (OAuth1 signed request)	
		$(this).oauthAjax(
		{
			oauth 		: user_data,
			url			: base_url + 'api/upload/create_expectation',
			type		: 'POST',
			dataType	: 'json',
			data		: picture_data,
	  		success		: function(result)
	  		{	  			  			  		
				if (result.status == 'success')
				{
					uploader.settings.multipart_params.file_hash = files[0].name;
					uploader.settings.multipart_params.file_hash = file_hash;
					uploader.settings.multipart_params.upload_id = result.data;					

					$(uploader_list).replaceWith(uploader_working);
			 		$('#file_uploading_name').append(files[0].name + '(' + plupload.formatSize(files[0].size) + ')');
								
					// Start Upload		
					uploader.refresh();
					uploader.start();
				}
				else
				{
					$('#content_message').notify({status:result.status,message:result.message});	
				}
		 	}
		});		
	});

	// Upload Progress
	uploader.bind('UploadProgress', function(up, file)
	{
		$('#file_uploading_progress').html(file.percent + "%");
	});
	
	// Upload Error
	uploader.bind('Error', function(up, err)
	{
		$('#content_message').notify({status:'error',message:'Error: ' + err.code + ', Message: ' + err.message + (err.file ? ', File: ' + err.file.name : '')}); 
		uploader.refresh();
	});

	// Upload Success
	uploader.bind('FileUploaded', function(up, file, res)
	{
		$('#file_uploading_progress').html("100%");
		var response = JSON.parse(res.response);
		
		$(uploader_list).delay(750).fadeOut(function()
		{
			$(uploader_list).remove();
			$(uploader_parent).append(uploader_change);
			$(uploader_list).delay(1250).fadeIn();
	
			uploader.init();
		});
	
		console.log(response);
		
		// Change Image
		if (response.status == 'success')
		{
		
			$('#profile_thumbnail').attr('src', base_url + 'uploads/profiles/1/small_' + response.data)
		}
		else
		{
			$('#content_message').notify({status:response.status,message:response.message});	
		}
	});
	
	// Delete Picture
	$('#delete_picture').live('click', function(eve)
	{	
		eve.preventDefault();
		$(this).oauthAjax(
		{
			oauth 		: user_data,
			url			: base_url + 'api/users/delete_profile_picture/id/' + user_data.user_id,
			type		: 'GET',
			dataType	: 'json',
	  		success		: function(result)
	  		{			
				if (result.status == 'success')
				{
					$(uploader_list).fadeOut(function()
					{
						$(uploader_list).remove();
						$(uploader_parent).append(uploader_create);
						$(uploader_list).fadeIn('slow');
						
						uploader.init();
						
						$('#profile_thumbnail').attr('src', base_url + 'uploads/profiles/medium_nopicture.png');
					});
				}
				else
				{
					$('#content_message').notify({status:result.status,message:result.message});			
				}
		 	}
		});
	});		


	// Update Profile Data
	$("#user_profile").bind('submit', function(e)
	{	
		e.preventDefault();
		var profile_data = $('#user_profile').serializeArray();
	
		console.log(profile_data);
	
		$(this).oauthAjax(
		{
			oauth 		: user_data,
			url			: $(this).attr('ACTION'),
			type		: 'POST',
			dataType	: 'json',
			data		: profile_data,
	  		success		: function(result)
	  		{
				$('html, body').animate({scrollTop:0});
				$('#content_message').notify({status:result.status,message:result.message});
				
				if (result.status == 'success')
				{
					// Update Viewed User Data
					$('#logged_name').html(result.data.name);
				}
		 	}
		});		
	});	
});

function deletePicture()
{
	// Delete Picture
	$('#delete_picture').live('click', function(eve)
	{
		console.log('delete click');
	
		eve.preventDefault();
		$(this).oauthAjax(
		{
			oauth 		: user_data,
			url			: base_url + 'api/users/delete_profile_picture/id/' + user_data.user_id,
			type		: 'GET',
			dataType	: 'json',
	  		success		: function(result)
	  		{				
				$('#content_message').notify({status:result.status,message:result.message});			
			
				if (result.status == 'success')
				{
					$(uploader_list).fadeOut(function()
					{
						$(uploader_list).remove();
						$(uploader_parent).append(uploader_create);
						$(uploader_list).fadeIn('slow');
						
						uploader.init();
						
						$('#profile_thumbnail').attr('src', base_url + 'uploads/profiles/medium_nopicture.png');
					});
				}	
		 	}
		});
	});
}

</script>
