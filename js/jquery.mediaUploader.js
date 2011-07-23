/* Uploading Images Plugin */
(function($)
{
	$.fn.mediaUploader = function(options)
	{	
		var settings =
		{
			'max_size'		: '',
			'create_url'	: '',
			'formats'		: '',
			'start'			: function(){},
			'complete'		: function(){}
		};
		
		return this.each(function()
		{			
			console.log('plugin getting run');

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
				filters 		: [options.formats]
			});
		
			// Initialize
			uploader.bind('Init', function(up, params){});
			uploader.init();

			// Add Files & Start Upload
			uploader.bind('FilesAdded', function(up, files)
			{		
				var file_hash	= md5(files[0].name);
				var file_data 	= [];
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

							// Start Upload	& Callback	
							uploader.refresh();
							uploader.start();
							options.start(files);
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
				$('#content_message').notify({status:'error', message:err.message}); 
				uploader.refresh();
			});
		
			// Upload Success
			uploader.bind('FileUploaded', function(up, file, res)
			{			
				$('#file_uploading_progress').html("100%");
				var response = JSON.parse(res.response);

				uploader.refresh();
				options.complete(response);							
			});
		})
	}

})(jQuery);
