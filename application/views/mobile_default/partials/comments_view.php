<div id="comments">
	<h3><span id="comments_count"><?= $comments_title ?></span> Comments</h3>
	
	<ol id="comments_list">
		<?php if($comments_list) echo $comments_list ?>
	</ol>

<form action="<?= $comments_post ?>" method="post" name="comments_write_form" id="<?= $comments_write_form ?>">
	<?php if($this->social_auth->logged_in()): ?>
	<a href="<?= $link_profile ?>"><span class="comment_thumbnail"><img src="<?= $logged_image ?>" border="0" /></span></a>
	<span class="comment_write">
		<a href="<?= $link_profile ?>"><?= $logged_name; ?></a> says:
	<?php else: ?>
	<span class="comment_thumbnail"><img src="<?= $logged_image ?>" border="0" /></span>
	<span class="comment_write">
		<span id="comment_name_email">
		<input type="text" name="comment_name" id="comment_name" value="<?= $comment_name; ?>">
		<input type="text" name="comment_email" id="comment_email" value="<?= $comment_email; ?>">
		<div id="comment_email_error" class="error"></div>
		</span>
	<?php endif; ?>
		<textarea name="comment" id="comment_write_text" rows="3" cols="38"><?= $comment_write_text; ?></textarea>
		<?= $recaptcha ?>
		<div id="comment_error" class="error"><?= $comment_error ?></div>
		<input type="submit" id="comment_submit" value="Comment">
	</span>
	<div class="clear"></div>
	<input type="hidden" name="reply_to_id" id="reply_to_id" value="<?= $reply_to_id ?>">
	<input type="hidden" name="content_id" id="content_id" value="<?= $content_id ?>">
	<input type="hidden" name="geo_lat" id="geo_lat" value="<?= $geo_lat ?>">
	<input type="hidden" name="geo_long" id="geo_long" value="<?= $geo_long ?>">
</form>
<div class="clear"></div>

<script type="text/javascript">

$(document).ready(function()
{
	// Placeholders 
	doPlaceholder('#comment_name', 'Your Name');
	doPlaceholder('#comment_email', 'your@email.com');
	doPlaceholder('#comment_write_text', 'Write comment...');

	// Comments On Site
	$('#comments_logged_form').bind('submit', function(eve)
	{
		eve.preventDefault();
				
		var comment_count_current	= $('#comments_count').html();
		var reply_to_id				= $('#reply_to_id').val();
		var comment 				= isFieldValid('#comment_write_text', 'Write comment...', 'Please write something!');		

		if(comment_count_current == 'Write')	var comment_count_updated = 1;
		else									var comment_count_updated = parseInt(comment_count_current) + 1;		

		// Reply or Normal		
		if (reply_to_id)	var append_to_where = '#comment-replies-' + reply_to_id;
		else				var append_to_where = '#comments_list';
		
		// Is Valid		
		if (comment == true)
		{								
			$(this).oauthAjax(
			{			
				oauth 		: user_data,
				url			: base_url + 'api/comments/create',
				type		: 'POST',
				dataType	: 'json',
				data		: $('#comments_logged_form').serializeArray(),
			  	success		: function(result)
			  	{				  		  				  	
					if(result.status == 'success')
					{		 	
						var html = '<li class="' + result.data.sub + 'comment" id="comment-' + result.data.comment_id + '"><a href="' + result.data.profile_link + '"><span class="comment_thumbnail"><img src="' + user_data.image + '" border="0" /></span></a><span class="' + result.data.sub + 'comment"><a href="' + result.data.profile_link + '">' + result.data.name + '</a> ' + result.data.comment + '<span class="comment_date ' + result.data.sub + '">' + result.data.created_at + '</span><ul class="comment_actions"><li><a href="' + base_url + 'api/comments/destroy/id/' + result.data.comment_id + '" id="delete-' + result.data.comment_id + '" class="comment_delete"><span class="item_actions action_delete"></span> Delete</a></li></ul><div class="clear"></div></span><div class="clear"></div></li>';
				 					 	
					 	$(append_to_where).append(html).show('slow');
						$('#comment_write_text').val('');
						$('#reply_to_id').val('');
						$('#comments_count').html(comment_count_updated);
						doPlaceholder('#comment_write_text', 'Write comment...');
				 	}
				 	else
				 	{					 		
					 	$('#comment_error').append(result.message).show('normal');
					}
			 	}
			});
		}
		else
		{
			eve.preventDefault();
		}	
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
		var email_valid				= validateEmailAddress(email_address);
		var comment_count_current	= $('#comments_count').html();
		var reply_to_id				= $('#reply_to_id').val();

		if(comment_count_current == 'Write')	var comment_count_updated = 1;
		else									var comment_count_updated = parseInt(comment_count_current) + 1;		
		
		// Reply or Normal
		if (reply_to_id)	var append_to_where = '#comment-replies-' + reply_to_id;
		else				var append_to_where = '#comments_list';

		// Is Valid		
		if (name == true && email == true && email_valid == true && comment == true)
		{
			console.log('inside validation');
		
			$.ajax(
			{
				url			: base_url + 'api/comments/create_public',
				type		: "POST",
				dataType	: "json",
				data		: $('#comments_public_form').serialize(),	
			  	success		: function(result)
			  	{	
			  		console.log(result);
			  				  		  				  	
					if(result.status == 'error')
					{
						$('#comment_error').html('');	
					 	$('#comment_error').append(result.message).show('normal');
				 	}
				 	else
				 	{
						var html = '<li class="' + result.data.sub + 'comment" id="comment-' + result.data.comment_id + '"><a href="' + result.data.profile_link + '"><span class="comment_thumbnail"><img src="' + user_data.image + '" border="0" /></span></a><span class="' + result.data.sub + 'comment"><a href="' + result.data.profile_link + '">' + result.data.name + '</a> ' + result.data.comment + '<span class="comment_date ' + result.data.sub + '">' + result.data.created_at + '</span><ul class="comment_actions"><li><a href="' + base_url + 'api/comments/destroy/id/' + result.data.comment_id + '" id="delete-' + result.data.comment_id + '" class="comment_delete"><span class="item_actions action_delete"></span> Delete</a></li></ul><div class="clear"></div></span><div class="clear"></div></li>';				 	
				 				 	
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

		
	$('.comment_reply').live('click', function()	
	{
		var reply_id = $(this).attr('id').split('-');		
		$('#reply_to_id').val(reply_id[1]);
	});
	
	$('.comment_delete').live('click', function(eve)
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

		$(this).oauthAjax(
		{
			oauth 		: user_data,
			url			: comment_delete,
			type		: 'GET',
			dataType	: 'json',
		  	success		: function(result)
		  	{		  	
				if(result.status == 'error')
				{
				 	$('#comment_error').append(result.message).show('normal');
			 	}
			 	else
			 	{			 	
					$(comment_element).hide('normal');
					$('#comments_count').html(comment_count_updated);
			 	}	
		 	}
		});
	});
});
</script>

</div>
