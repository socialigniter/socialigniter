if (jQuery.url.attr('port'))
{
	var url_port = ':' + jQuery.url.attr('port');
}
else
{
	var url_port = '';
}
var base_url 		= jQuery.url.attr('protocol') + '://' + jQuery.url.attr('host') + url_port + '/';
var current_module	= jQuery.url.segment(1);

// Makes a Placeholder for form module
function doPlaceholder(id, placeholder)
{		
	var value_length = $(id).length;
	var value_text 	 = $(id).val();

	if (value_length > 0 && value_text == '')
	{
		$(id).val(placeholder).css('color', '#999999');
		
		$(id).focus(function()
		{
			if ($(id).val() == placeholder) $(this).val('').css('color', '#000000');
		});
		
		$(id).blur(function()
		{
			if ($(id).val() == '') $(this).val(placeholder).css('color', '#999999');
		});
	}
}

//For God's sake, disable autocomplete!
$(function(){ $('input').attr('autocomplete','off'); });

// Allows for easy user notifications and if "how" the notifiy ever works it'll be site wide.
// Use like: $('#content_message).notify({message:'Something has been updated!'});
(function($)
{
	$.fn.notify = function(options)
	{
		var settings =
		{
			message : 'Content has been saved', //The message
			appendTo: '.content_wrap', //Where to add the message
			classes : 'message_alert', //Classes you want to add to the selected item,
			timeout : 5000, //How long to wait before hiding,
			speed   : 'normal' //animation speed
		};
		return this.each(function()
		{//Merge the options and settings
			options = $.extend({},settings,options);
			//Save "this"
			var $this = $(this);
			//If it's not already, hide the thing to be shown, add content, classes, then show it!
			$this.css({display:'none'}).html(options.message).addClass(options.classes).show(options.speed)
			//wait for the specified "timeout", then hide
				.delay(options.timeout).hide(options.speed,function()
				{//Cleanup by removing the added classes, then empty contents
					$this.removeClass(options.classes).empty();
				});
		});
	};
})( jQuery );

//Allows for easy dynamic "sluggable" urls to be previewed to the user based
//on an input field and the current URL

(function($)
{
	$.fn.slugify = function(options)
	{
		/*
		 Settings:
		 slug [default='']:
			Where the "slug" is previewed at. For example it could be .slug-preview
			& "http://mysite.com/" will be injected to it when the plugin is called
		 
		 url [default=your current url plus a leading slash]:
			The base url i.e. mysite.com/blog/ and then slugify will add "hello
			world" like: mysite.com/blog/hello-world
			
		name [default='slug']:
			This is the name you want to give the hidden input field. For example:
			name:'slug' then <input name="slug"... will be added to the DOM ready
			for your form's submission.
		
		classPrefix [default='slugify']
			This is prepended to the class names in the plugin, so, for example if
			change the default to slugger, you could do .slugger-input in your CSS
			and style the generated input
		 
		*/
		var settings =
		{
			"slug":"",
			"url":window.location.href+'/',
			"name":'slug',
			"classPrefix":"slugify",
			"slugTag":"span",
			"inputType":"text"
		};
		return this.each(function()
		{	//Merge the options and settings
			options = $.extend({},settings,options);
			//Save "this"
			var $this = $(this);
			
			//Converts string to a valid "sluggable" URL (private function tho!)
			function _convertToSlug(str)
			{
				//This line converts to lowercase and then makes spaces into dahes
				slug_val = str.replace(/ /g,'-').toLowerCase();
				//This line strips special characters
				slug_val = slug_val.match(/[\w\d\-]/g).toString().replace(/,/g,'');
				return slug_val;
			}
			
			//Give it the default value on load
			$(options.slug).html(options.url+'<'+options.slugTag+' class="'+options.classPrefix+'-preview"></'+options.slugTag+'><input class="'+options.classPrefix+'-input" type="'+options.inputType+'" name="'+name+'">')
				.find('.'+options.classPrefix+'-input').hide()
				.end().find('.'+options.classPrefix+'-preview').bind('click',function(){
					$this.addClass(options.classPrefix+'-modified');
					$(this).hide();
					$(options.slug+' .'+options.classPrefix+'-input').show().focus().select()
					.blur(function(){
						if($(this).val()==''){
							_revertedValue = _convertToSlug($this.val());
							$(this).val(_revertedValue);
							$(options.slug+' .'+options.classPrefix+'-preview').text(_revertedValue);
							$this.removeClass(options.classPrefix+'-modified');
						}
						else{
							$(this).val(_convertToSlug($(this).val())).hide();
							$(options.slug+' .'+options.classPrefix+'-preview').text(_convertToSlug($(this).val())).show();
						}
					})
					.bind('keyup',function(){
						$(options.slug+' .'+options.classPrefix+'-preview').text($(this).val());
					});
				})
			
			//update on each keyup
			$this.bind('keyup',function()
			{
				if(!$this.hasClass(options.classPrefix+'-modified'))
				{
					var _sluggedURL = '';
					if($this.val())
					{ //If there's a value, convert it to a slug
						_sluggedURL = _convertToSlug($this.val());
					}
					//Actually add the new slug, then, rejoice!
					$(options.slug+' .'+options.classPrefix+'-preview').text(_sluggedURL);
					$(options.slug+' .'+options.classPrefix+'-input').val(_sluggedURL);
				}
			});
		});
	};
})( jQuery );


//Converts string to a valid "sluggable" URL.
function convertToSlug(str)
{
	//This line converts to lowercase and then makes spaces into dahes
	slug_val = str.replace(/ /g,'-').toLowerCase();
	//This line strips special characters
	slug_val = slug_val.match(/[\w\d\-]/g).toString().replace(/,/g,'');
	return slug_val;
}

// Validate email address
function validateEmailAddress(email)
{
	var pattern = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);

	return pattern.test(email);
}

// Checks if field has content, handles placeholder
function isFieldValid(id, placeholder, error)
{
	var value = $(id).val();
	
	if (value == placeholder)
	{
		$(id).val(error).css('color', '#bd0b0b');		
		$(id).oneTime(1350, function(){$(id).val(placeholder)});
		$(id).oneTime(1350, function(){$(id).css('color', '#999999')});
		
		return false;
	}
	
	return true;
}

function isWysiwygValid(id, placeholder, error)
{
	var value = $(id).val();
	
	if (value == placeholder)
	{
		$(id).val(error).css('color', '#bd0b0b');		
		$(id).oneTime(1350, function(){$(id).val(placeholder)});
		$(id).oneTime(1350, function(){$(id).css('color', '#999999')});
		
		return false;
	}
	
	return true;
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
				$('#' + request).html(result.message).addClass(current_class + ' msg_notification');
			}		  	
	  	}		
	});	
}

// Marks Item In Feed New
function markNewItem(item_id)
{
	$('#' + item_id).addClass('item_created');
	$('#' + item_id).oneTime(4000, function()
	{
		$('#' + item_id).removeClass('item_created').addClass('item');
	});
}

