<form method="post" name="user_picture" id="user_picture" action="<?= base_url() ?>api/users/upload_profile_picture/id/<?= $logged_user_id ?>" enctype="multipart/form-data">
	<table border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td>Picture:</td>
		<td><img id="profile_thumbnail" src="<?= $thumbnail ?>" border="0"></td>
		<td><input type="file" size="20" id="pickfiles" name="pickfiles"></td>
		<td>
			<a id="uploadfiles" href="#">Upload Picture</a>
			<span id="filelist"></span>
		</td>
	</tr>
	<?php if ($image != '') { ?>
	<tr>
		<td></td>
		<td colspan="3"><a id="delete_pic" href="#">	Delete picture</a></td>
	</tr>
	<?php } ?>
	</table>
</form>
<form method="post" name="user_profile" id="user_profile" action="<?= base_url() ?>api/users/modify/id/<?= $logged_user_id ?>" enctype="multipart/form-data">
	<table border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td>Name:</td>
		<td colspan="2"><input type="text" name="name" placeholder="Your Name" size="40" value="<?= $name ?>"></td>
	</tr>
    <tr>
		<td>Username:</td>
		<td colspan="2"><input type="text" name="username" size="40" value="<?= set_value('username', $username) ?>"></td>
	</tr>
    <tr>
		<td>Email:</td>
		<td colspan="2"><input type="email" name="email" size="40" value="<?= set_value('email', $email) ?>"></td>
	</tr>
	<tr>
		<td>Language</td>
		<td colspan="2"><?= form_dropdown('language', config_item('languages'), $language); ?></td>		
	</tr>
	<tr>
		<td>Timezone</td>
		<td colspan="2"><?= timezone_menu($time_zone); ?></td>
	</tr>
	<tr>
		<td>Geo Enable:</td>
		<td colspan="2"><input type="checkbox" name="geo_enabled" value="<?= $geo_enabled ?>"> Add my location to content</td>
	</tr>		
  	<tr>
  		<td>Privacy:</td>
  		<td colspan="2"><input type="checkbox" name="privacy" value="<?= $privacy ?>"> Keep my feeds private</td>
  	</tr>
    <tr>		
		<td colspan="3"><input type="submit" value="Save" /></td>
  	</tr>  	
	</table>
</form>

<script type="text/javascript" src="<?= base_url() ?>js/plupload.js"></script>
<script type="text/javascript" src="<?= base_url() ?>js/plupload.html5.js"></script>
<script type="text/javascript" src="<?= base_url() ?>js/plupload.flash.js"></script>
<script type="text/javascript">
$(document).ready(function()
{
	$('#uploadfiles').hide();

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
		multipart_params : { 'hullloooooo there' : 'ding dong' },		
		filters : [
			{title : "Image files", extensions : "jpg,gif,png"}
		]
	});

	uploader.bind('Init', function(up, params)
	{
		console.log("Current runtime: " + params.runtime);
	});

	uploader.init();

	// Add Files
	uploader.bind('FilesAdded', function(up, files)
	{
		console.log('files added');
	
		$.each(files, function(i, file) 
		{
			$('#uploadfiles').show();
			$('#pickfiles').val(file.name + ' (' + plupload.formatSize(file.size) + ')');
		});

		up.refresh();
	});

	// Start Upload
	$('#uploadfiles').click(function(eve) {
		uploader.start();
		eve.preventDefault();
	});

	// Progress
	uploader.bind('UploadProgress', function(up, file)
	{
		console.log('upload progress ' + file.percent);
	
		$('#' + file.id + " b").html(file.percent + "%");
	});

	uploader.bind('Error', function(up, err)
	{
		console.log('oops an error');	
	
		$('#filelist').append("<div>Error: " + err.code + ", Message: " + err.message + (err.file ? ", File: " + err.file.name : "") + "</div>");

		up.refresh();
	});

	uploader.bind('FileUploaded', function(up, file, res)
	{
		console.log(res.response);
	
		$('#' + file.id + " b").html("100%");
		
		var response = JSON.parse(res.response);
		
		$('#profile_thumbnail').attr('src', base_url + 'uploads/profiles/1/small_' + response.data)
	});	


	// Update Profile Data
	$("#user_profile").bind("submit", function(e)
	{	
		e.preventDefault();
		var profile_data = $('#user_profile').serializeArray();
	
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
					// DO PICTURE ONCE WORKING
				}
				
		 	}
		});		
	});	
});
</script>
