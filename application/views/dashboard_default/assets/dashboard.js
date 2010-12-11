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
	$(function()
	{
		$("select, input:checkbox, input:radio, input:file").uniform();
	});

	// Hide Things
	$('.error').hide();
	$('#content_message').oneTime(4000, function()
	{
		$('#content_message').hide('slow')
	});

	// Character Counter
	$('#status_update_text').NobleCount('#character_count',
	{
		on_negative: 'color_red'
	});
	
	// Placeholders 
	doPlaceholder('#status_update_text', "What's shaking?");
	doPlaceholder('.comment_write_text', 'Write comment...');
	doPlaceholder('#tags', 'Blogging, Internet, Web Design');
		

	// Gets count of new items with class="get_count_new" uses id="name_count_new" to make call to AJAX controller
	$('.feed_count_new').oneTime(100, function() { getCountNew(this) });
	$('.feed_count_new').everyTime(60000,function() { getCountNew(this); });		

	// Status
	$("#status_update").bind("submit", function(eve)
	{
		eve.preventDefault();
		
		var status_update		= $('#status_update_text').val();
		var status_update_valid = isFieldValid('#status_update_text', "What's shaking?", 'Please write something');

		// If isn't empty		
		if (status_update_valid == true)
		{				
			$.ajax(
			{
				url: base_url + 'status/add',
				type: 'POST',
				dataType: 'html',
				data: $('#status_update').serialize(),
			  	success: function(html)
			  	{			  	
					if(html == 'error')
					{
					 	$('#content_message').html("Oops we couldn't update your status!").addClass('message_alert').show('normal');
					 	$('#content_message').oneTime(2500, function(){$('#content_message').hide('fast')});	
				 	}
				 	else
				 	{
				 		// Inject
					 	$('#feed').prepend(html).show('slow');
						$('#status_update_text').val('');						
						doPlaceholder('#status_update_text', "What's shaking?");

						// Mark New Item		                 
		                markNewItem($(html).attr('id'));				
				 	}	
			 	}
			});
		}
	});
	

	// Marks Feed Item not new
	$('.item_new').live('click', function()
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
			url			: url_new,
			type		: 'PUT',
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
	$('.item_approve').live('click', function(eve)
	{
		eve.preventDefault();
		var item_attr_id		= $(this).attr('id');
		var item_attr_array		= item_attr_id.split('_');
		var item_id				= item_attr_array[3];
		var item_url			= $(this).attr('href');
		var item_alert_approve	= '#item_alert_approve_'+item_id;
		var item_action_approve	= '#item_action_approve_'+item_id;
		
		$(this).oauthAjax(
		{
			url			: item_url,
			type		: 'PUT',
			dataType	: 'json',
		  	success		: function(result)
		  	{
				if (result.status == 'success')
				{			
					$(item_alert_approve).fadeOut('normal');
					$(item_action_approve).parent().fadeOut('normal');
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
				 	$('#content_message').html("Oops we couldn't delete your item!").addClass('message_alert').show('normal');
				 	$('#content_message').oneTime(2500, function(){$('#content_message').hide('fast')});				
				}
			}
		});
	});	
	
	
	
	/* Geolocation */
	function geo_get()
	{
		if (navigator.geolocation)
		{
			return navigator.geolocation.getCurrentPosition(geo_success);
		}
		else{
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
				localStorage.setItem('geo_accuracy',position.coords.accuracy);
				localStorage.setItem('geo_date',current_time);
			}
		}
	}
	//Initial get, use it elsewhere to update location
	geo_get();
	
	/* End Geolocation stuff */
	
	/* Start the comment functionality */
	//Cache common selectors.
	$comment_form = $('.comment_form');
	$comment_list = $('#comment_list');
	
	//When the item comment link is clicked
	$('.item_comment').live('click', function(eve)
	{
		//Hide the other comment forms that are visible.
		$comment_form.hide();
		//Get "this" link, go up and find the hidden comment_form and show it.
		$(this)
			.parent().parent().parent().find('.comment_form').show()
			//Get the textarea, focus on it, and empty the existing value
			//.find('textarea').focus();
		
		//Set the reply_to_id value by getting the parent #item_N and finding N and setting that as the id
		$this_reply_to_id = $(this).parent().parent().parent().parent();
		$this_reply_to_id.find('[name=reply_to_id]').val($this_reply_to_id.attr('id').split('_')[1]);
		
		//If we have their location...
		if(localStorage && localStorage['geo_lat'])
		{
			//...get it from localStorage and put it in the hidden comment fields
			$comment_form.find('[name=geo_lat]').val(localStorage['geo_lat']);
			$comment_form.find('[name=geo_long]').val(localStorage['geo_long']);
			$comment_form.find('[name=geo_accuracy]').val(localStorage['geo_accuracy']);
		}
		
		//Here we are going to get the comments:
		content_id = $(this).parent().parent().parent().find('.comment_form [name=content_id]').val();
		$comment_list = $(this).parent().parent().parent().find('.comment_list');
		//if($comment_list.children().length < 1){
			$.get('api/comments/content/id/'+content_id,function(json){
				for(x in json){
					$comment_list.append('\
						<li id="comment_'+json[x].comment_id+'">\
							<div class="comment">\
								<p><span class="comment_author"><a href="#link-to-userprofile">'+json[x].name+'</a></span> '+json[x].comment+'</p>\
							</div>\
							<p class="comment_meta"><span class="comment_date">'+json[x].created_at+'</span></p>\
						</li>\
					');
				}
			});
		//}
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
			content:'\
				<div class="error_alert">\
					<h2>'+title+'</h2>\
					<p>'+msg+'\
				</div>'
		});
	}
	
	//Submitting a comment	
	$(".item_comment_form").bind("submit", function(eve)
	{
		eve.preventDefault();
		
		$(this).find('[type=submit]').attr('disabled','true');
		$this_form = $(this);
		
		content_id = $(this).parent().parent().find('.comment_form [name=content_id]').val();
		var this_textarea	= $(this).find('.comment_write_text');
		var comment 		= isFieldValid(this_textarea, 'Write comment...', 'Please write something!');
		if (comment == true)
		{	
			$.ajax(
			{
				url			: base_url + 'api/comments/create',
				type		: 'POST',
				dataType	: 'json',
				data		: $(this).serializeArray(),
			  	success		: function(json)
			  	{		  	
					if(json.status == 'error')
					{
					 	generic_error();
				 	}
				 	else
				 	{
						$comment_form.hide().find('textarea').val('')
						.siblings('[type=submit]').removeAttr('disabled');
						$this_form.parent().parent().find('.comment_list').append('\
							<li id="comment_'+json.data.comment_id+'">\
								<div class="comment">\
									<p><span class="comment_author"><a href="#link-to-userprofile">'+json.data.name+'</a></span> '+json.data.comment+'</p>\
								</div>\
								<p class="comment_meta"><span class="comment_date">'+json.data.created_at+'</span></p>\
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
	
	
	/*
	
	*/
	/* End the comment functionality */
	
	// Add Category
	$('.category_select').live('click', function(eve) 
	{
		eve.preventDefault();		
		parent.$('#what').append('<option value=""></option>');
		parent.$("#what option[value='value']").attr('selected', 'selected');        
		parent.$.fancybox.close();	
	});
	
	
	// Media Manager
	$(".media_manager").fancybox({
		'autoDimensions'		: false,
		'width'					: 500,
		'height'				: 600,
		'padding'				: 20,
		'scrolling'				: 'auto',
		'transitionIn'			: 'none',
		'transitionOut'			: 'none',
		'hideOnOverlayClick'	: true,
		'overlayColor'			: '#000000',
		'overlayOpacity'		: 0.7,
		'type'					: 'iframe'
	});	
	
	
	embed_video = function(vidURL)
	{
		var embed = '<object width="500" height="375"><param name="allowfullscreen" value="true"><param name="wmode" value="opaque"><param name="allowscriptaccess" value="always"><param name="movie" value="' + vidURL + '"><embed src="' + vidURL + '" type="application/x-shockwave-flash" allowfullscreen="true" allowscriptaccess="always" height="375" width="500" wmode="opaque"></object>';
		return embed;
	};
		
	$('.insert_image').live('click', function()
	{
		var insertedImageUrl = $(this).attr('rel');		
			
		parent.$().wysiwyg('insertImage', insertedImageUrl);
		parent.$.fancybox.close();
	});	
	
	$('.insert_video').live('click', function()
	{
		var insertVideoUrl = $(this).attr('rel');
		var embedVideo = embed_video(insertVideoUrl);
		
		parent.$().wysiwyg('insertHtml', embedVideo);
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



	/* Start Blog Section */
	
	function update_category_select(where_to)
	{
		$.get(base_url+'api/categories/search/module/blog',function(json)
		{
			for(x in json)
			{
				$(where_to).append('<option value="'+json[x].category_id+'">'+json[x].category+'</option>');
			}
			$.uniform.update(where_to);
		});
	}
	
	update_category_select('[name=category_id]');
	
	$('[name=category_id]').change(function()
	{	
		if($(this).val() == 'add_category')
		{
			$('[name=category_id]').find('option:first').attr('selected','selected');
			$.uniform.update('[name=category_id]');
			$.fancybox(
			{
				content:'\
				<div class="modal_wrap" style="opacity:0;">\
					<h2>Add Category</h2>\
					<form id="new_category">\
						<label for="category_name">Name</label>\
						<input id="category_name" type="text" value="">\
						<p class="slug_url">'+base_url+current_module+'/<span></span></p>\
						<!--<label for="category_slug">Slug</label>\
						<input id="category_slug" type="text" value="">-->\
						<label for="category_parent">Parent Category</label>\
						<select id="category_parent">\
							<option>--None--</option>\
						</select>\
						<label for="category_access">Access</label>\
						<select id="category_access">\
							'+$('[name=access]').html()+'\
						</select>\
						<br>\
						<input type="submit">\
					</form>\
				</div>',
				onComplete:function(e)
				{
					update_category_select('.modal_wrap select');
					$('.modal_wrap').find('select').uniform().end().animate({opacity:'1'});
					function update_slug()
					{
						slug_val = convert_to_slug($('#category_name').val());
						//Set the new value
						$('.slug_url span').text(slug_val).live('click',function()
						{
							$(this).replaceWith('<input class="slug_url_edit" type="text" value="'+$(this).text()+'">');
							$('.slug_url_edit').select().blur(function()
							{
								$(this).replaceWith('<span class="modified">'+$(this).val()+'</span>');
							});
						});
					}
					$('#category_name').live('keyup',function()
					{
						if($('#category_name').val() !== '' && !$('.slug_url span').hasClass('modified'))
						{
							update_slug();
						}
						else
						{
							//If the category name field is null, make the slug blank
							$('#category_slug').val('');
						}
					});
					$('#new_category').live('submit',function()
					{
						$.ajax(
						{
							url		: base_url + 'api/categories/create',
							type		: 'POST',
							dataType	: 'json',
							data		: $(this).serialize(),
							success	: function(json)
							{		  	
								if(json.status == 'error')
								{
									generic_error();
								}
								else
								{
									console.log('went through');
									console.log(json);
								}	
							}
						});
						return false;
					});
					
				}
			});
			
		}
	});
	
	/* End Blog Section */

	// Handles Checkboxes
	$('#geo_enabled').live('click', function() {
		if ($('#geo_enabled') == '1') 
		{
			$('#geo_enabled').val('0')
		}
		else
		{
			$('#geo_enabled').val('1')
		}		
	});	

	$('#privacy').live('click', function() {
		if ($('#privacy') == '1') 
		{
			$('#privacy').val('0')
		}
		else
		{
			$('#privacy').val('1')
		}		
	});			

	$('#phone_search').live('click', function() {
		if ($('#phone_search') == '1') 
		{
			$('#phone_search').val('0')
		}
		else
		{
			$('#phone_search').val('1')
		}		
	});		
	
	$('#delete_pic').live('click', function() {
		if ($('#delete_pic') == '1') 
		{
			$('#delete_pic').val('0')
		}
		else
		{
			$('#delete_pic').val('1')
		}		
	});

});
