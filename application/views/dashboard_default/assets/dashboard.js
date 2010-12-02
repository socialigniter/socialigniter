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
				url: base_url + '/status/add',
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

		// Updates feed_count_new on sidebar
		var feed_count_new		= '#' + item_type + '_count_new';
		var current_new_count	= $(feed_count_new).html();
		var updated_new_count	= current_new_count - 1;

		$.ajax(
		{
			url			: base_url + '/home/' + item_type + '/viewed/' + item_id,
			type		: 'GET',
			dataType	: 'json',
			data		: $('#comments_logged_form').serialize(),
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
		var item_click_href		= $(this).attr('href');
		var item_alert_approve	= '#item_alert_approve_'+item_id;
		var item_action_approve	= '#item_action_approve_'+item_id;

		$.get(item_click_href, function(html)
		{		
			if (html == 'approved')
			{
				$(item_alert_approve).fadeOut('normal');
				$(item_action_approve).parent().fadeOut('normal');
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
		var item_click_href		= $(this).attr('href');
		var item_element		= '#item_' + item_id;
	
		$.ajax(
		{
			url			: item_click_href,
			type		: 'DELETE',
			dataType	: 'json',
			data		: $('#comments_logged_form').serialize(),
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
	
	
	/* Start the comment functionality */
	//Cache common selectors.
	$comment_form = $('.comment_form');
	
	//When the item comment link is clicked
	$('.item_comment').live('click', function(eve)
	{
		//Hide the other comment forms that are visible.
		$comment_form.hide();
		//Get "this" link, go up and find the hidden comment_form and show it.
		$(this)
			.parent().parent().parent().find('.comment_form').show()
			//Get the textarea, focus on it, and empty the existing value
		return false;
	});
	
	
	//Close the comment area if the user clicks outside of the form
	//$(window).click(function(){ $('.comment_form').hide() });
	
	
	//Submitting a comment	
	$(".item_comment_form").bind("submit", function(eve)
	{
		eve.preventDefault();
		
		var this_textarea	= $(this).find('.comment_write_text');
		var comment 		= isFieldValid(this_textarea, 'Write comment...', 'Please write something!');
				
		if (comment == true)
		{	
			$.ajax(
			{
				url			: base_url + '/comments/logged',
				type		: 'POST',
				dataType	: 'html',
				data		: $(this).serialize(),
			  	success		: function(result)
			  	{		  	
					if(result.status == 'error')
					{
					 	alert('fail');
				 	}
				 	else
				 	{			 	
						alert('success');
				 	}	
			 	}
			});			
		}
		else
		{
			alert('boo');
		}
	
	});
	
	
	//If the user clicks anywhere inside the form, return false (so it doesn't close, see line below)
	//$comment_form.live('click', function(){ return false; });
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
