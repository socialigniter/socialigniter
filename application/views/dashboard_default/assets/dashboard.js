// Makes Word From content.status
function displayContentStatus(status, approval)
{
	var result = '';
	
	if (approval != '')
	{
	    if (status == 'P' && approval == 'Y')
	    {
	    	result = 'published'; 
	    }
	    else if (status == 'P' && approval == 'N') 	
	    {
	    	result = 'awaiting approval';
		}
		else if (status == 'S' && approval == 'Y')
		{
			result = 'saved';	        
		}
		else
		{
			result = 'saved';
		}
	}
	else
	{
	    if (status == 'P')
	    {
	    	result = 'published'; 
	    }
	    else if (status == 'S') 	
	    {
	    	result = 'saved';
		}
		else
		{
			result = 'unpublished';	        
		}	
	}

	return result;
}

// Gets Count of Feed Items
function getCountNew(element)
{
	var request 		= $(element).attr('id');
	var current_class	= $(element).attr('class');
	var type			= $(element).attr('rel');
	
	$.oauthAjax(
	{
		oauth 		: user_data,		
		url			: base_url + 'api/' + type + '/new',
		type		: 'GET',
		dataType	: 'json',
	  	success		: function(result)
	  	{	  	  	
			if(result.status == 'success')
			{	// Adds msg_notifation class to feed_count_new
				$('#' + request).html(result.data).addClass(current_class + ' msg_notification');
			}		  	
	  	}		
	});	
}

// Marks Item In Feed New
function markNewItem(item_id)
{
	$('#' + item_id).addClass('item_created');
	$('#' + item_id).oneTime(18000, function()
	{
		$('#' + item_id).removeClass('item_created').addClass('item');
	});
}


/* Geo Location */
function geo_get()
{
	if (navigator.geolocation)
	{
		if (!(navigator.userAgent.match(/Mac/) && navigator.userAgent.match(/Chrome/)))
		{
			return navigator.geolocation.getCurrentPosition(geo_success);
		}
	}
	else
	{
		return false;
	}
}

function geo_success(position)
{
	// On success, if we have localStorage (IE8,Opera,FF,WebKit,iPhone,etc)
	// we'll store their location in localStorage so we can get it whenever
	current_time = new Date().getTime();
	hours_ago = (current_time/1000/60/60)-(localStorage.getItem('geo_date')/1000/60/60);

	// If it's been more than 3hrs save it, otherwise, nevermind.
	if(hours_ago >= 3)
	{
		if(localStorage)
		{
			localStorage.setItem('geo_lat',position.coords.latitude);
			localStorage.setItem('geo_long',position.coords.longitude);
			localStorage.setItem('geo_date',current_time);
		}
	}
}


/* Sort function for comment arrays by comment_id (desc) */
function sortByCommentId(a, b){
  var aCommentId = a.comment_id;
  var bCommentId = b.comment_id; 
  return ((aCommentId > bCommentId) ? -1 : ((aCommentId < bCommentId) ? 1 : 0));
}


/*	mediaUploader - jQuery Plugin 
	- Based on Pluploader http://plupload.com
	- Options are implied
*/
(function($)
{
	$.fn.mediaUploader = function(options)
	{	
		var settings =
		{
			'max_size'		: '',
			'create_url'	: '',
			'formats'		: '',
			'multipart'		: {},
			'start'			: function(){},
			'complete'		: function(){}
		};
					
		options = $.extend({}, settings, options);
		trigger = $(this).attr('id');
		
		var multipart_data 			= options.multipart;
		multipart_data.file_hash	= ''; 
		multipart_data.upload_id	= '';
		multipart_data.consumer_key	= user_data.consumer_key;

		// Uploader Params
		var uploader = new plupload.Uploader(
		{
			runtimeStyle	: 'html5,flash',
			browse_button	: trigger,
			container		: 'container',
			max_file_size	: options.max_size,
			max_file_count	: 1,
			url 			: options.create_url,
			flash_swf_url	: base_url + 'js/plupload.flash.swf',
			multipart 		: true,
			multipart_params: multipart_data,		
			filters 		: [options.formats]
		});
	
		// Initialize
		uploader.bind('Init', function(up, params){});

		// Call Init
		uploader.init();

		// Add Files & Start Upload
		uploader.bind('FilesAdded', function(up, files)
		{	
			var file_hash	= md5(files[0].name);
			var file_data 	= [];
			file_data.push({'name':'file_hash','value':file_hash});
			
			// Create Expectation (OAuth1 signed request)	
			$.oauthAjax(
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

						// Trigger Start Callback
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
				
			// Refresh & Trigger Complete Callback
			uploader.refresh();
			options.complete(response);							
		});
	}
})(jQuery);


/*	categoryManager - jQuery Plugin 
	- Uses Social-Igniter category API
	- Allows both creating and editing of categories
*/
(function($)
{
	$.fn.categoryManager = function(options)
	{
		var settings = {
			action		: '',
			module		: '',
			type		: '',
			title		: '',
			category_id	: ''
		};

		options = $.extend({}, settings, options);

		$(this).change(function()
		{	
			if ($(this).val() == 'add_category')
			{
				$existing_categories = $(this);
				$existing_categories.find('option:first').attr('selected','selected');

				// Action & URLs
				if (options.action == 'edit')
				{
					var partial_url	= 'home/category_manager/' + options.category_id;
					var action_url	= 'api/categories/modify/id/' + options.category_id;
				}
				else
				{
					var partial_url = 'home/category_manager';
					var action_url	= 'api/categories/create';
				}
			
				// Gets HTML template
				$.get(base_url + partial_url, {}, function(html_partial)
				{
					$('<div />').html(html_partial).dialog(
					{
						width	: 525,
						modal	: true,
						close	: function(){$(this).remove()},				
						title	: options.title,
						create	: function()
						{
							$category_editor = $(this);	
		
							$('#category_name').slugify(
							{
								slug	  : '#category_slug', 
								url		  : base_url + options.module + '/', 
								name	  : 'category_url', 
								slugValue : $(html_partial).find('#category_slug').html()
							});										
						},
						open	: function() {},
						buttons	:
						{
				        	'Cancel':function()
				        	{
				          		$category_editor.dialog('close');
				          		$category_editor.remove();
				        	},
				        	'Save':function()
				        	{
								var category_data = $('#category_manager').serializeArray();
								category_data.push({'name':'module','value':options.module},{'name':'type','value':options.type});
		
								$.oauthAjax(
								{
									oauth 		: user_data,
									url			: base_url + action_url,
									type		: 'POST',
									dataType	: 'json',
									data		: category_data,
									success		: function(result)
									{	
										// Succeeds - Remove Old, Append New Categories
										if (result.status == 'success')
										{
											var existing_options = $existing_categories.find('option');
											var existing_target  = $('#category_id');
							
											$existing_categories.find('option').remove().end().append('<option value="0">---select---</option><option value="' + result.data.category_id + '" selected="selected">' + result.data.category + '</option>');
							
											$.each(existing_options, function(index, value)
											{						
												if ($(value).attr('value') != '0')
												{
													$existing_categories.append(value);
												}
											});											
										}

			          					$category_editor.dialog('close');
			          					$category_editor.remove();
									}
								});
				        	}
				        }				    
				    });  
				});
			}
		});
	};
}) (jQuery);



/* Takes a DOM element (li, div, a, etc...) and converts it to a multi selectable region */
(function($)
{
	$.fn.selectify = function(options) 
	{
		var defaults = 
		{
			element	: '',
			trigger	: '',
			waiting	: '',
			clicked	: '',
			limit	: 1
		};

		options = $.extend(true, defaults, options);

		$(this).find('.' + options.trigger).live('click', function()
		{
			// Picked Values
			var widget_current_pick = $(options.element).val();
			var value 				= $(this).attr('rel');

			if (widget_current_pick == '')
			{
				widget_current_pick = new Array();
			}
			else
			{
				widget_current_pick = JSON.parse(widget_current_pick);
			}

			var is_added = jQuery.inArray(value, widget_current_pick);

			// Is Added To Target Form Field
			if (is_added == -1)
			{			
				// Has Picker Limit Been Reached
				if (widget_current_pick.length < options.limit)
				{
					// Add to Object & Field				
					$(this).removeClass(options.waiting).addClass(options.clicked);	
					widget_current_pick.push(value);
					$(options.element).val(JSON.stringify(widget_current_pick));
				}
			}
			else
			{
				// Remove from Object & Field
				$(this).removeClass(options.clicked).addClass(options.waiting);
				widget_current_pick.remove(is_added);				
				$(options.element).val(JSON.stringify(widget_current_pick));	
			}
		});
	};
})(jQuery);


/* HTML Partial Vars */
var partials = {
	"item_data":"<li class='item_data' id='item_data_'><span class='actions action_'></span><span class='item_data'></span><ul class='item_actions'><li><a href='' class='mobile_edit_data'><span class='actions action_edit'></span> Edit</a></li><li><a href='' class='mobile_delete_data' rel=''><span class='actions action_delete'></span> Delete</a></li></ul><input type='hidden' id='data_' value=''></li>"
}




// On Ready Actions
$(document).ready(function()
{
	// Gets count of new items with class="get_count_new" uses id="name_count_new" to make call to AJAX controller
	$('.feed_count_new').oneTime(100, function() { getCountNew(this) });
	$('.feed_count_new').everyTime(60000,function() { getCountNew(this); });		
	
	/* Item Events (activity, content, comments, etc...)
	 * These are used to interact with feed items of any type, but currently for (activity, content, comments)
	 * You can extend your module to hit a custom API event (new, approve, publish, save, delete) 
	 */
	 
	// Marks Feed Item not new
	$('.item_new, .item_manage_new').live('click', function()
	{
		var item				= $(this).attr('id');
		var item_id 			= item.replace('item_','');		
		var item_type			= $(this).attr('rel');
		var item_alert_new		= '#item_alert_new_'+item_id;
		var url_new				=  base_url + 'api/' + item_type + '/viewed/id/' + item_id;

		// Updates feed_count_new on sidebar
		var feed_count_new		= '#' + item_type + '_count_new';
		var current_new_count	= $(feed_count_new).html();
		var updated_new_count	= current_new_count - 1;

		$.oauthAjax(
		{
			oauth 		: user_data,		
			url			: url_new,
			type		: 'GET',
			dataType	: 'json',
		  	success		: function(result)
		  	{		  	
				if (result.status == 'success')
				{			
					$('#item_alert_new_' + item_id).fadeOut('normal');
					$('#item_' + item_id).removeClass('item_new').addClass('item');
				
					if (updated_new_count == 0)
					{
						$(feed_count_new).fadeOut('normal');
					}
					else
					{
						$(feed_count_new).html(updated_new_count);
					}
				}		  	
		  	}		
		});
	});
	
	// Approve Item
	$('.item_approve, .item_alert_approve').live('click', function(eve)
	{
		eve.preventDefault();
		var item_attr_id		= $(this).attr('id');
		var item_attr_array		= item_attr_id.split('_');
		var item_id				= item_attr_array[3];
		var item_type			= $(this).attr('rel');		
		var item_url			= base_url + 'api/' + item_type + '/approve/id/' + item_id;
				
		$.oauthAjax(
		{
			oauth 		: user_data,		
			url			: item_url,
			type		: 'GET',
			dataType	: 'json',
		  	success		: function(result)
		  	{
				if (result.status == 'success')
				{			
					$('#item_alert_approve_'+item_id).fadeOut('normal');
					$('#item_action_approve_'+item_id).parent().fadeOut('normal');
				}		  	
		  	}		
		});
	});	
	
	// Published Item (content) will be Saved
	$('.item_published').live('click', function(eve)
	{
		eve.preventDefault();
		var item_attr_id		= $(this).attr('id');
		var item_attr_array		= item_attr_id.split('_');
		var item_id				= item_attr_array[3];
		var item_url			= base_url + 'api/content/save/id/' + item_id;
				
		$.oauthAjax(
		{
			oauth 		: user_data,		
			url			: item_url,
			type		: 'GET',
			dataType	: 'json',
		  	success		: function(result)
		  	{		  	
				if (result.status == 'success')
				{	
					$('#item_alerts_'+item_id).append('<span class="item_alert_saved" rel="content" id="item_alert_saved_'+item_id+'">Saved</span>').fadeIn('normal');
					$('#item_action_published_'+item_id).replaceWith('<a class="item_saved" href="saved" rel="content" id="item_action_saved_'+item_id+'"><span class="actions action_saved"></span> Saved</a>').animate({opacity:'1'});					
				}		  	
		  	}		
		});		
		
	});

	// Saved Item (content) will be Published
	$('.item_saved, .item_alert_saved').live('click', function(eve)
	{
		eve.preventDefault();
		var item_attr_id		= $(this).attr('id');
		var item_attr_array		= item_attr_id.split('_');
		var item_id				= item_attr_array[3];
		var item_url			= base_url + 'api/content/publish/id/' + item_id;
				
		$.oauthAjax(
		{
			oauth 		: user_data,		
			url			: item_url,
			type		: 'GET',
			dataType	: 'json',
		  	success		: function(result)
		  	{		  	
				if (result.status == 'success')
				{	
					$('#item_alert_saved_'+item_id).fadeOut('normal');
					$('#item_action_saved_'+item_id).replaceWith('<a class="item_published" href="published" rel="content" id="item_action_published_'+item_id+'"><span class="actions action_published"></span> Published</a>');
				}		  	
		  	}		
		});			
	});

	// Delete Item
	$('.item_delete').live('click', function(eve)
	{
		eve.preventDefault();
		var item_attr_id		= $(this).attr('id');
		var item_attr_array		= item_attr_id.split('_');
		var item_id				= item_attr_array[3];
		var item_url			= $(this).attr('href');
		var item_element		= '#item_' + item_id;
	
		$.oauthAjax(
		{
			oauth 		: user_data,		
			url			: item_url,
			type		: 'GET',
			dataType	: 'json',
		  	success		: function(result)
		  	{
				if (result.status == 'success')
				{			
					$(item_element).hide('normal');
				}			
				else
				{
					$('#content_message').notify({message:'Oops we couldn\'t delete your item!'});
				}
			}
		});
	});	
	
	// Activate User
	$('.item_activate, .item_alert_activate').live('click', function(eve)
	{
		eve.preventDefault();
		var item_attr_id		= $(this).attr('id');
		var item_attr_array		= item_attr_id.split('_');
		var item_id				= item_attr_array[3];
		var item_url			= base_url + 'api/users/activate/id/' + item_id;
				
		$.oauthAjax(
		{
			oauth 		: user_data,		
			url			: item_url,
			type		: 'GET',
			dataType	: 'json',
		  	success		: function(result)
		  	{	  	
				if (result.status == 'success')
				{	
					$('#item_alert_activate_'+item_id).fadeOut('normal');
					$('#item_action_activate_'+item_id).replaceWith('<a class="item_deactivate" href="deactivate" rel="users" id="item_action_deactivate_'+item_id+'"><span class="actions action_deactivate"></span> Deactivate</a>');
				}
		  	}		
		});			
	});
	
	
	/* Start the comment functionality */
	//Cache common selectors.
	$comment_list = $('.comment_list');
	$comment_form = $('.comment_form');
	
	//When the item comment link is clicked
	$('.item_comment').live('click', function(eve)
	{
		$(this).parent().parent().parent().find('.comment_list').show();
		//Hide the other comment forms that are visible.
		$comment_form.hide();
		//Get "this" link, go up and find the hidden comment_form and show it.
		$(this)
			.parent().parent().parent().find('.comment_form').show()
			//Get the textarea, focus on it, and empty the existing value
			.find('textarea').focus();
		
		//Set the reply_to_id value by getting the parent #item_N and finding N and setting that as the id
		$this_reply_to_id = $(this).parent().parent().parent().parent();
		$this_reply_to_id.find('[name=reply_to_id]').val($this_reply_to_id.attr('id').split('_')[1]);
		
		//If we have their location...
		if(localStorage && localStorage['geo_lat'])
		{
			//...get it from localStorage and put it in the hidden comment fields
			$comment_form.find('[name=geo_lat]').val(localStorage['geo_lat']);
			$comment_form.find('[name=geo_long]').val(localStorage['geo_long']);
		}
		
		// Here we are going to get the comments:
		content_id = $(this).parent().parent().parent().find('.comment_form [name=content_id]').val();
		$comment_list = $(this).parent().parent().parent().find('.comment_list');
		$.get('api/comments/content/id/'+content_id, function(json)
		{
			if (json.status !== 'error')
			{
				$comment_list.find('li:not(#comment_write)').remove();					
				json.data.sort(sortByCommentId);
				for (x in json.data)
				{
					if (x != "remove") {
						$comment_list.prepend('\
							<li id="comment_'+json.data[x].comment_id+'">\
								<div class="comment">\
									<a href="' + base_url + 'profiles/' + json.data[x].username + '"><img class="comment_thumb" src="'+getUserImageSrc(json.data[x],'small')+'"></a>\
									<p><span class="comment_author"><a href="' + base_url + 'profiles/' + json.data[x].username + '">'+json.data[x].name+'</a></span> '+json.data[x].comment+'</p>\
									<p class="comment_meta"><span class="comment_date">'+$.relativetime({time:json.data[x].created_at})+'</span></p>\
									<div class="clear"></div>\
								</div>\
							</li>\
						');
					}
				}
			}
		});
		return false;
	});
	
	
	// Close the comment area if the user clicks outside of the form
	$('*').click(function(eve)
	{
		// Here we check if what the user clicked on was the form or children of it
		if(!$(eve.target).is('.comment_form,.comment_form *'))
		{
			$comment_form.hide();
		}
	});
	
	// A generic function alert the user an error has occured. Use simply with:
	// generic_error(), or you can manually specify different title's or messages
	var generic_error = function(title,msg)
	{
		if(!title){ title= 'Oops!'; }
		if(!msg) { msg = 'Something must have gone wrong, but no worries we\'re working on it!'; }
		$.fancybox(
		{
			content:'<div class="error_alert"><h2>'+title+'</h2><p>'+msg+'</div>'
		});
	}
	
	// Submitting a comment	
	$(".item_comment_form").live("submit", function(e)
	{
		e.preventDefault();
		
		$(this).find('[type=submit]').attr('disabled','true');
		$this_form = $(this);
		
		content_id = $(this).parent().parent().find('.comment_form [name=content_id]').val();
		var this_textarea	= $(this).find('.comment_write_text');
		var comment 		= isFieldValid(this_textarea, 'Write comment...', 'Please write something!');
		if (comment == true)
		{	
			$.oauthAjax(
			{
				oauth 		: user_data,			
				url			: base_url + 'api/comments/create',
				type		: 'POST',
				dataType	: 'json',
				data		: $(this).serializeArray(),
			  	success		: function(json)
			  	{		  	
					if(json.status == 'error')
					{
					 	generic_error('Error',json.message);
				 	}
				 	else
				 	{
						$comment_form.find('textarea').val('').siblings('[type=submit]').removeAttr('disabled');
						$this_form.closest('.comment_list').prepend('\
							<li id="comment_'+json.data.comment_id+'">\
								<div class="comment">\
									<a href="' + base_url + 'profiles/' + json.data.username + '"><img class="comment_thumb" src="'+getUserImageSrc(json.data,'small')+'"></a>\
									<p><span class="comment_author"><a href="' + base_url + 'profiles/' + json.data.username + '">'+json.data.name+'</a></span> '+json.data.comment+'</p>\
									<p class="comment_meta"><span class="comment_date">just now</span></p>\
									<div class="clear"></div>\
								</div>\
							</li>\
						');
				 	}
			 	}
			});
		}
		else
		{
			generic_error();
		}
	});	
	
	
	/* Add Category */
	$('.category_select').live('click', function(e) 
	{
		e.preventDefault();		
		parent.$('#what').append('<option value=""></option>');
		parent.$("#what option[value='value']").attr('selected', 'selected');        
		parent.$.fancybox.close();	
	});
	
	
	/* More Pannels */
	$('.drop_pannel').hide();	
	$(".more").click(function(e)
	{	
		e.preventDefault();

		var show_pannel = $(this).attr('href');

		$(this).hide('normal');				
		$('#'+show_pannel).show('fast');
	});
	
});
