/* Uploading Images Plugin */
(function($)
{
	$.fn.mediaUploader = function(options)
	{	
		var settings =
		{
			'form'			: '',
			'thumb'			: '',
			'notify'		: '',
			'parent'		: '',
			'list'			: '',
			'create' 		: '<ul id="picture_container" class="item_actions_list"><li><a id="pickfiles" href="#"><span class="actions action_upload"></span> Upload A Picture</a></li><li class="small_details"><span class="actions_blank"></span></li></ul>',
			'change' 		: '<ul id="picture_container" class="item_actions_list"><li><a id="pickfiles" href="#"><span class="actions action_edit"></span> Change Picture</a></li><li><a id="delete_picture" href="#"><span class="actions action_delete"></span> Delete Picture</a></li></ul>',
			'working'		: '<ul id="picture_container" class="item_actions_list"><li><span class="actions action_sync"></span> Uploading: <span id="file_uploading_progress"></span><span id="file_uploading_name"></span></li></ul>',
			'max_size'		: '',
			'create_url'	: '',
			'file_formats'	: ''
		};
		return this.each(function()
		{			
			options = $.extend({}, settings, options);
			var $this = $(this);

			// Uploader Params
			var uploader = new plupload.Uploader(
			{
				runtimeStyle	: 'html5,flash',
				browse_button	: 'pickfiles',
				container		: 'container',
				max_file_size	: options.max_size,
				max_file_count	: 1,
				url 			: options.create_url,
				flash_swf_url	: base_url + 'js/plupload.flash.swf',
				multipart 		: true,
				multipart_params: {'file_hash':'', 'upload_id':'', 'consumer_key':user_data.consumer_key},		
				filters 		: [options.file_formats]
			});
		
			// Initialize
			uploader.bind('Init', function(up, params){});
			uploader.init();
		
			// Add Files & Start Upload
			uploader.bind('FilesAdded', function(up, files)
			{		
				var file_hash	= md5(files[0].name);
				var file_data 	= $(options.form).serializeArray();
				file_data.push({'name':'file_hash','value':file_hash});
				
				// Create Expectation (OAuth1 signed request)	
				$(this).oauthAjax(
				{
					oauth 		: user_data,
					url			: base_url + 'api/upload/create_expectation',
					type		: 'POST',
					dataType	: 'json',
					data		: file_data,
			  		success		: function(result)
			  		{	  			  			  		
						if (result.status == 'success')
						{
							// Update Multipart Form Variables
							uploader.settings.multipart_params.file_hash = files[0].name;
							uploader.settings.multipart_params.file_hash = file_hash;
							uploader.settings.multipart_params.upload_id = result.data;					
		
							// Show Uploading Status - upload size - plupload.formatSize(files[0].size)
							$(options.list).replaceWith(options.working);
					 		$('#file_uploading_name').append(files[0].name);
										
							// Start Upload		
							uploader.refresh();
							uploader.start();
						}
						else
						{
							$(options.notify).notify({status:result.status,message:result.message});	
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
				// err.code err.file
				$(options.notify).notify({status:'error', message:err.message}); 
				uploader.refresh();
			});
		
			// Upload Success
			uploader.bind('FileUploaded', function(up, file, res)
			{
				$('#file_uploading_progress').html("100%");
				var response = JSON.parse(res.response);
				
				$(options.list).delay(750).fadeOut(function()
				{
					$(options.list).remove();
					$(options.parent).append(options.change);
					$(options.list).delay(1250).fadeIn();
					uploader.init();
				});

				// Change Image
				if (response.status == 'success')
				{
					$(options.thumb).attr('src', base_url + 'uploads/profiles/1/small_' + response.data)
				}
				else
				{
					$(options.notify).notify({status:response.status,message:response.message});	
				}
			});
		})
	}

})(jQuery);
