<h1>Upload yo picture</h1>

<form method="post" name="user_picture" id="user_picture" action="<?= base_url() ?>api/users/upload_profile_picture/id/<?= $logged_user_id ?>" enctype="multipart/form-data">
	<table border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td>Picture:</td>
		<td><input type="file" size="20" name="userfile"></td>
	</tr>
    <tr>		
		<td colspan="3"><input type="submit" value="Save" /></td>
  	</tr>  	
	</table>
</form>

<script type="text/javascript">
$(document).ready(function()
{
	console.log(md5('dig dong sing song'));

	$("#user_profile").bind("submit", function(eve)
	{	
		eve.preventDefault();
		var picture_data = $('#user_picture').serializeArray();
		picture_data.push({'name':'file_hash','value':''});
		
		// Create Expectation Token (OAuth 1.0 signed request)	
		$(this).oauthAjax(
		{
			oauth 		: user_data,
			url			: base_url + 'upload/create_expectation',
			type		: 'POST',
			dataType	: 'json',
			data		: picture_data,
	  		success		: function(result)
	  		{
				if (result.status == 'success')
				{
					// Upload file data (unsigned request)
					initializeUploader($('#upload_url').val(), $('#upload_token').val());
				
					function initializeUploader(url, token)
					{
						window.uploader = new plupload.Uploader(
						{
							runtimes : 'flash',
							multi_selection: false,
							browse_button : 'pickfiles',
							max_file_size : '20mb',
							url : base_url + 'api/users/upload_profile_picture',
							flash_swf_url : '<?= base_url() ?>js/plupload.flash.swf',
							multipart : true,
							multipart_params : { token : token },
							filters : [{title : "Video files", extensions : "jpg,jpeg,gif,png"}]
						});
						
						$('#uploadfiles').click(function()
						{
							uploader.start();
				
							$('#upload_file_form').fadeOut(function()
							{
								$('#upload_file_progress').fadeIn();
							});
				
							return false;
						});		
						
						// Initialize Upload
						uploader.bind('Init', function(up, params)
						{
							console.log('Init current runtime: ' + params.runtime);
						});
						
						// File Added
						uploader.bind('FilesAdded', function(up, files)
						{		
							for (var i in files)
							{
								$('#pickfiles').hide();
								$('#picked_file').val(files[i].name).fadeIn();
								$('#uploadfiles').fadeIn();
								console.log('filename: ' + files[i].name + ' and filesize: ' + plupload.formatSize(files[i].size));
							}
						});
						
						uploader.bind('UploadFile', function(up, file)
						{
							$('#upload_form').html($('#upload_form').html() + '<input type="hidden" name="file-' + file.id + '" value="' + file.name + '" />');
							$('#upload_filename').html(file.name).fadeIn();
				
							// Make Approx Time
							var size		= plupload.formatSize(file.size);
							var size_split	= size.split(' ');
							var approx_time = '';
							
							if (size_split[1] == 'KB')
							{
								approx_time = '1 min';
							}
							else if (size_split[1] == 'MB')
							{
								approx_time = .5 * size_split[0];
								approx_time = Math.round(approx_time) + ' mins';
							}
													
							$('#upload_aprox_time').delay(250).fadeIn();
						});
				
						uploader.bind('UploadProgress', function(up, file)
						{	
							console.log('percent: ' + file.percent);
							$('#upload_filelist').html('<span>' + file.percent + "%</span>");
						});		
						
						// Now do get Video ID
						uploader.bind('FileUploaded', function(up, file, res)
						{
							console.log('FileUploaded');			
							console.log(res);			
							youtube_response = $.parseJSON(res.response);
							//console.log(youtube_response);
							$('#upload_filename').delay(250).fadeOut(function()
							{
								$('#upload_filelist').delay(250).fadeOut();
								$('#upload_aprox_time').delay(250).fadeOut();
								$('#upload_file_processing').delay(250).fadeIn();
				
								setTimeout(function() { addChantVideo(youtube_response.id) }, 8000);
							});
						});
						
						// And here
						uploader.bind('Error', function(up, args)
						{
							console.log('Error');
							console.log(args);
							$('#upload_filename').delay(400).fadeOut(function()
							{
								$('#upload_filelist').delay(250).fadeOut();
								$('#upload_file_processing').delay(750).fadeIn();
							});
						});
				
						uploader.init();
					}	
					
					if (upload.status == 'success')
					{
						$('#content_message').notify({status:result.status,message:result.message});
					}

				}
				
		 	}
		});
		
		
		
				
	});	
});
</script>
