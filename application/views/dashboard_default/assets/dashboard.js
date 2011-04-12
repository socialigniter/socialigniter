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
	    	result = 'saveddddd';
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
	
	$(this).oauthAjax(
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
	//On success, if we have localStorage (IE8,Opera,FF,WebKit,iPhone,etc)
	//we'll store their location in localStorage so we can get it whenever
	current_time = new Date().getTime();
	hours_ago = (current_time/1000/60/60)-(localStorage.getItem('geo_date')/1000/60/60);
	//If it's been more than 3hrs save it, otherwise, nevermind.
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

// On Ready
$(document).ready(function()
{
	// Highlights New Item
	if ($.url.attr('anchor'))
	{
	    $('html, body').animate({scrollTop:0});
	
		var url_octothorpe = $.url.attr('anchor');
		
		markNewItem(url_octothorpe);
	}

	// Generates Uniform
	$("select, input:checkbox, input:radio, input:file").uniform();

	// Hide Things
	$('.error').hide();
	// Character Counter
	$('#status_update_text').NobleCount('#character_count',
	{
		on_negative: 'color_red'
	});		

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

		$(this).oauthAjax(
		{
			oauth 		: user_data,		
			url			: url_new,
			type		: 'PUT',
			dataType	: 'json',
		  	success		: function(result)
		  	{
		  		console.log(result);
		  	
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
				
		$(this).oauthAjax(
		{
			oauth 		: user_data,		
			url			: item_url,
			type		: 'PUT',
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
				
		$(this).oauthAjax(
		{
			oauth 		: user_data,		
			url			: item_url,
			type		: 'PUT',
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
				
		$(this).oauthAjax(
		{
			oauth 		: user_data,		
			url			: item_url,
			type		: 'PUT',
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
	
		$(this).oauthAjax(
		{
			oauth 		: user_data,		
			url			: item_url,
			type		: 'DELETE',
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
				
		$(this).oauthAjax(
		{
			oauth 		: user_data,		
			url			: item_url,
			type		: 'PUT',
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
				for (x in json.data)
				{
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
		});
		return false;
	});
	
	
	//Close the comment area if the user clicks outside of the form
	$('*').click(function(eve)
	{
		//Here we check if what the user clicked on was the form or children of it
		if(!$(eve.target).is('.comment_form,.comment_form *'))
		{
			$comment_form.hide();
		}
	});
	
	//A generic function alert the user an error has occured. Use simply with:
	//generic_error(), or you can manually specify different title's or messages
	var generic_error = function(title,msg)
	{
		if(!title){ title= 'Oops!'; }
		if(!msg) { msg = 'Something must have gone wrong, but no worries we\'re working on it!'; }
		$.fancybox(
		{
			content:'<div class="error_alert"><h2>'+title+'</h2><p>'+msg+'</div>'
		});
	}
	
	//Submitting a comment	
	$(".item_comment_form").live("submit", function(eve)
	{
		eve.preventDefault();
		
		$(this).find('[type=submit]').attr('disabled','true');
		$this_form = $(this);
		
		content_id = $(this).parent().parent().find('.comment_form [name=content_id]').val();
		var this_textarea	= $(this).find('.comment_write_text');
		var comment 		= isFieldValid(this_textarea, 'Write comment...', 'Please write something!');
		if (comment == true)
		{	
			$(this).oauthAjax(
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
						$comment_form.find('textarea').val('')
						.siblings('[type=submit]').removeAttr('disabled');
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
	$('.category_select').live('click', function(eve) 
	{
		eve.preventDefault();		
		parent.$('#what').append('<option value=""></option>');
		parent.$("#what option[value='value']").attr('selected', 'selected');        
		parent.$.fancybox.close();	
	});
		
	
	
	/* More Pannels */
	$('.drop_pannel').hide();	
	$(".more").click(function(eve)
	{	
		eve.preventDefault();

		var show_pannel = $(this).attr('href');

		$(this).hide('normal');				
		$('#'+show_pannel).show('fast');
	});	
	

	/* Page Edit */
	$('.layout_picker').live('click', function(eve)
	{
		eve.preventDefault();
		var value		= $(this).attr('id');
		var layout 		= value.replace('layout_','');
		$('#layout').val(layout);
		$('.layout_picker').removeClass('layout_selected');
		$(this).addClass('layout_selected');
	});



	/* Category Editor Plugin */
	(function($)
	{
		$.categoryEditor = function(options)
		{
			var settings = {
				url_api		: '',
				url_pre		: '',
				url_sub		: '',
				module		: '',
				type		: '',
				title		: '',
				slug_value	: '',
				details		: '',
				trigger		: '',
				after 		: function(){}
			};
			
			options = $.extend({},settings,options);
			
			// Repopulates trigger dropdown
			function update_category_select(where_to)
			{
				$.get(options.url_api, function(json)
				{
					if (json.status == 'success')
					{
						for(x in json.data)
						{
							$(where_to).append('<option value="'+json.data[x].category_id+'">'+json.data[x].category+'</option>');
						}
						$.uniform.update(where_to);
						
						$(where_to).prepend('<option value="0">---select---</option>');
						$(where_to).append('<option value="add_category">+ Add Category</option>');
					}
				});
			}					
		
			// Gets the HTML template
			$.get(base_url + 'home/category_editor',{},function(category_editor)
			{				
				var category_parents 	= '';
				var slug_url			= options.url_pre + '/';
	
				// API to get categories for parent
				$.get(options.url_api, function(json)
				{
					if (json.status == 'success')
					{
						for(x in json.data)
						{
							category_parents = category_parents+'<option value="'+json.data[x].category_id+'">'+json.data[x].category+'</option>';
						}
					}
							
					// Update returned HTML
					html = $(category_editor)
							.find('#editor_title').html(options.title).end()
							.find('#category_access').uniform().end()
							.find('#category_parent_id').append(category_parents).uniform().end()
						.html();
										
					$.fancybox(
					{
						content:html,
						onComplete:function(e)
						{
							$('#category_parent_id').live('change', function(){$.uniform.update(this);});							
							$('.modal_wrap').find('select').uniform().end().animate({opacity:'1'});
							$('#category_name').slugify({slug:'#category_slug', url:options.url_pre, name:'category_url', slugValue:options.slug_value });
														
							// Create Category
							$('#new_category').bind('submit',function(e)
							{
								e.preventDefault();
								e.stopPropagation();
								
								var category_data = $('#new_category').serializeArray();
								category_data.push({'name':'module','value':options.module},{'name':'type','value':options.type},{'name':'details','value':options.details});
								
								console.log(category_data)
							
								$(this).oauthAjax(
								{
									oauth 		: user_data,
									url			: options.url_sub,
									type		: 'POST',
									dataType	: 'json',
									data		: category_data,
									success		: function(json)
									{
										console.log(json)
																		  	
										if(json.status == 'error')
										{
											generic_error();
										}
										else
										{
											$(options.trigger).empty();
											update_category_select(options.trigger);
											$.fancybox.close();
										}	
									}
								});
								
								return false;
							});
						}
					});					
				});
			});
		};
	})( jQuery );
	

});
