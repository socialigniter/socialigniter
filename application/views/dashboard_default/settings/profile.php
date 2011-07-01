<form method="post" name="user_profile" id="user_profile" action="<?= base_url() ?>api/users/modify/id/<?= $logged_user_id ?>" enctype="multipart/form-data">
	<table border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td>Picture</td>
		<td>
		<div id="profile_picture">
			<img id="profile_thumbnail" src="<?= $thumbnail ?>" border="0">
		</div>
		<div id="profile_picture_uploader">
		<?php if ($image): ?>
		<ul id="profile_picture_change" class="item_actions_list">
			<li><a id="pickfiles" href="#"><span class="actions action_edit"></span> Change Picture</a></li>
			<li><a id="delete_picture" href="#"><span class="actions action_delete"></span> Delete Picture</a></li>
		</ul>
		<?php else: ?>
		<ul id="profile_picture_create" class="item_actions_list">
			<li><a id="pickfiles" href="#"><span class="actions action_upload"></span> Upload A Picture</a></li>
			<li class="small_details"><span class="actions_blank"></span> <?= config_item('users_images_max_size') / 1024 ?> MB max size in these formats <?= strtoupper(str_replace('|', ', ', config_item('users_images_formats'))) ?></li>			
		</ul>
		<?php endif; ?>
		<ul id="profile_picture_uploading" class="item_actions_list"></ul>
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
	// Do Expectation Token
	$('#userfile').change(function(eve)
	{	
		eve.preventDefault();
		var file_hash		= md5($(this).val());
		var picture_data 	= $('#user_picture').serializeArray();
		picture_data.push({'name':'file_hash','value':file_hash});
	
		console.log(picture_data);
		// Create Expectation Token (OAuth 1.0 signed request)	
		$(this).oauthAjax(
		{
			oauth 		: user_data,
			url			: base_url + 'api/upload/create_expectation',
			type		: 'POST',
			dataType	: 'json',
			data		: picture_data,
	  		success		: function(result)
	  		{
	  			console.log(result);	  		
				if (result.status == 'success')
				{
				}
		 	}
		});		
	});

	var uploader = new plupload.Uploader({
		runtimes : 'html5,flash',
		browse_button : 'pickfiles',
		container : 'container',
		max_file_size : '10mb',
		max_file_count: 1,
		url : base_url + 'api/users/upload_profile_picture/id/' + user_data.user_id,
		flash_swf_url : base_url + 'js/plupload.flash.swf',
		multipart : true,
		multipart_params : {'file_hash':'','upload_id':'','consumer_key':user_data.consumer_key},		
		filters : [
			{title : "Image files", extensions : "jpg,gif,png"}
		]
	});

	uploader.bind('Init', function(up, params)
	{
		console.log("Current runtime: " + params.runtime);
	});

	uploader.init();

	// Add Files & Start Upload
	uploader.bind('FilesAdded', function(up, files)
	{		
		var file_hash		= md5(files[0].name);
		var picture_data 	= $('#user_profile').serializeArray();
		picture_data.push({'name':'file_hash','value':file_hash});
		
		console.log(picture_data);
		
		// Create Expectation Token (OAuth 1.0 signed request)	
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

					$('#profile_picture_change').hide();
					$('#profile_picture_create').hide();
					$('#profile_picture_uploading').html('<li><span class="actions action_sync"></span> Uploading: <span id="file_uploading_progress"></span>' + files[0].name + ' (' + plupload.formatSize(files[0].size) + ')</li>');
			
					up.refresh();
					
					// Start Upload		
					uploader.start();
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
		$('#content_message').notify({scroll:true,status:'error',message:'Error: ' + err.code + ', Message: ' + err.message + (err.file ? ', File: ' + err.file.name : '')}); 
		up.refresh();
	});

	// Upload Success
	uploader.bind('FileUploaded', function(up, file, res)
	{
		$('#file_uploading_progress').html("100%");
		var response = JSON.parse(res.response);
		
		$('#profile_picture_uploading').delay(750).fadeOut();
		$('#profile_picture_change').delay(1250).fadeIn();		

		$('#content_message').notify({scroll:true,status:response.status,message:response.message});
		
		if (response.status == 'success')
		{
			$('#profile_thumbnail').attr('src', base_url + 'uploads/profiles/1/small_' + response.data)
		}
	});	

	// Delete Picture
	$('#delete_picture').bind('click', function(eve)
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
				$('#content_message').notify({scroll:true,status:result.status,message:result.message});			
			
				if (result.status == 'success')
				{
					$('#profile_picture_change').fadeOut();
					$('#profile_picture_create').fadeIn();
					$('#profile_thumbnail').attr('src', base_url + 'uploads/profiles/medium_nopicture.png');
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
				$('#content_message').notify({scroll:true,status:result.status,message:result.message});
				
				if (result.status == 'success')
				{
					// Update Viewed User Data
					$('#logged_name').html(result.data.name);
				}
		 	}
		});		
	});	
});
</script>
