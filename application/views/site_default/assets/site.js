$(document).ready(function()
{
	// Generates Uniform
	$(function(){
		$("select, input:checkbox, input:radio, input:file").uniform();
	});
	
	// Hides Things
	$('.error').hide();

	// Placeholders 
	doPlaceholder('#login_email', 'your@email.com');
	doPlaceholder('#login_password', 'password');
	doPlaceholder('#comment_name', 'Your Name');
	doPlaceholder('#comment_email', 'your@email.com');
	doPlaceholder('#comment_write_text', 'Write comment...');

	// Comments On Site
	$("#comments_logged_form").bind("submit", function(eve)
	{
		eve.preventDefault();
		
		var comment_write_url 		= $('#comment_write_url').val();
		var comment_count_current	= $('#comments_count').html();
		var reply_to_id				= $('#reply_to_id').val();

		if(comment_count_current == 'Write')	var comment_count_updated = 1;
		else									var comment_count_updated = parseInt(comment_count_current) + 1;		

		// Inject as reply or normal		
		if (reply_to_id)	var append_to_where = '#comment-replies-' + reply_to_id;
		else				var append_to_where = '#comments_list';
								
		$.ajax(
		{
			url			: comment_write_url,
			type		: 'POST',
			dataType	: 'json',
			data		: $('#comments_logged_form').serialize(),
		  	success		: function(result)
		  	{
		  		console.log(result.status);
		  	
				if(result.status == 'error')
				{
				 	$('#comment_error').append("Oops we couldn't post your comment!").show('normal');
			 	}
			 	else
			 	{			 	
					var html = '<li class="' + result.data.comment + '" id="comment-' + result.data.comment_id + '"><a href="' + result.data.profile_link + '"><span class="comment_thumbnail"><img src="' + result.data.profile_image + '" border="0" /></span></a><span class="' + result.data.sub + 'comment"><a href="' + result.data.profile_link + '">' + result.data.name + '</a> ' + result.data.comment + '<span class="' + result.data.sub + 'comment_date">' + result.data.created_at + '</span><div class="clear"></div></span><div class="clear"></div></li>'
			 					 	
				 	$(append_to_where).append(html).show('slow');
					$('#comment_write_text').val('');
					$('#reply_to_id').val('');
					$('#comments_count').html(comment_count_updated);
			 	}	
		 	}
		});
	});

	$('#comments_public_form').bind('submit', function(eve)
	{
		$('#comment_email_error').val('').hide('fast');
		$('#comment_error').hide('fast');

		if ($('#recaptcha_widget_div').length)
		{	
			Recaptcha.reload();
		}
	
		var name					= isFieldValid('#comment_name', 'Your Name', 'Please enter your name');
		var email					= isFieldValid('#comment_email', 'your@email.com', 'Please enter your email', 'email');	
		var comment 				= isFieldValid('#comment_write_text', 'Write comment...', 'Please write something!');
		var email_address 			= $('#comment_email').val();
		var email_valid				= ValidateEmailAddress(email_address);

		// Key Info
		var comment_write_url 		= $('#comment_write_url').val();
		var comment_count_current	= $('#comments_count').html();
		var reply_to_id				= $('#reply_to_id').val();

		if(comment_count_current == 'Write')	var comment_count_updated = 1;
		else									var comment_count_updated = parseInt(comment_count_current) + 1;		
		
		// Inject as reply or normal
		if (reply_to_id)	var append_to_where = '#comment-replies-' + reply_to_id;
		else				var append_to_where = '#comments_list';

		// If fields are filled out		
		if (name == true && email == true && email_valid == true && comment == true)
		{
			$.ajax(
			{
				url			: comment_write_url,
				type		: "POST",
				dataType	: "html",
				data		: $('#comments_public_form').serialize(),
				beforeSend	: function(html)
				{			
					$('#busy').show('slow');
				},	
			  	success: function(html)
			  	{		  	
					$('#busy').hide('slow');
					$('#errors').hide('slow');	
										
					if(html == 'error')
					{
				 		$('#comment_error').append("Oops we couldn't post your comment!").show('normal');
				 	}
				 	else
				 	{			 	
					 	$(append_to_where).append(html).show('slow');
						$('#comment_name').val('');
						$('#comment_email').val('');
						$('#comment_write_text').val('');
						$('#reply_to_id').val('');
						$('#comments_count').html(comment_count_updated);
						
						doPlaceholder('#comment_name', 'Your Name');
					 	doPlaceholder('#comment_email', 'your@email.com');
					 	doPlaceholder('#comment_write_text', 'Write comment...');
				 	}	
			 	}
			});		
		}
		else if (name == true && email == true && email_valid == false && comment == true)
		{
			$('#comment_email_error').html('That email address is not valid').show('normal');
			$('#comment_email_error').oneTime(2500, function(){$('#comment_email_error').hide('slow')});			
		}
		else
		{
			eve.preventDefault();
		}
					
		return false;
	});

		
	$('.comment_reply').bind('click', function()	
	{
		var reply_id = $(this).attr('id').split('-');		
		$('#reply_to_id').val(reply_id[1]);
	});
	
	$('.comment_delete').bind('click', function(eve)
	{
		eve.preventDefault();
		var comment_id 				= $(this).attr('id').split('-');
		var comment_delete			= $(this).attr('href');
		var comment_element			= '#comment-' + comment_id[1];	
		var comment_count_current	= $('#comments_count').html();
		
		if(comment_count_current == 1)
		{
			var comment_count_updated	= 'Write';
		}
		else
		{
			var comment_count_updated	= parseInt(comment_count_current)-1;		
		}
				
		$.get(comment_delete, function(html)
		{				
			if (html == 'deleted')
			{			
				$(comment_element).hide('normal');
				$('#comments_count').html(comment_count_updated);
			}
		});
	});

});