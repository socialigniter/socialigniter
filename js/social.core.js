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
			"inputType":"text",
			"slugValue":""
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
			
			//This line add line add the default url into the element specified by the user
			//then creates a span by default and puts the default value of the slug on load, if there is one
			//it also creates the input element and gives it the same value as the span
			$(options.slug).html(options.url+'<'+options.slugTag+' class="'+options.classPrefix+'-preview">'+options.slugValue+'</'+options.slugTag+'><input class="'+options.classPrefix+'-input" type="'+options.inputType+'" value="'+options.slugValue+'" name="'+options.name+'">')
				//hide the input element that was just created
				.find('.'+options.classPrefix+'-input').hide()
				//bind a click event to make the span editable that's holding the edited slug
				.end().find('.'+options.classPrefix+'-preview').bind('click',function(){
					//Add a class to the original selected element that says the element has been modified
					$this.addClass(options.classPrefix+'-modified');
					//Hide the span
					$(this).hide();
					//Show the input, focus on it, and highlight the text
					$(options.slug+' .'+options.classPrefix+'-input').show().focus().select()
					//When the user clicks outside of it...
					.blur(function(){
						//If the value is blank...
						if($(this).val()==''){
							//Convert the value to the input box again
							_revertedValue = _convertToSlug($this.val());
							$(this).val(_revertedValue);
							//Also update the span
							$(options.slug+' .'+options.classPrefix+'-preview').text(_revertedValue);
							//Make it as if it was never editied.
							//This will make any mods to the input show up here in the span again
							$this.removeClass(options.classPrefix+'-modified');
						}
						//If there is a value...
						else{
							//Take the value of the input and convert it to a URL safe value
							$(this).val(_convertToSlug($(this).val())).hide();
							//Do the same to the span
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


/*
	Returns null on a form submit if unchecked rather than browser default of nothing.
*/

(function($){
	$.fn.nullify = function(options) {
		var defaults = {
			on:'yes', //The value you want when it's "on"
			off:'no', //The value you want when it's "off"
			afterToggle:function(){}
		};
		
		return this.each(function() {
			options = $.extend(true, defaults, options);
			
			$this = $(this);
			
			//Set the default values on call
			if($this.val()==options.on){
				//If it's set to "on", make the checkbox checked
				$this.attr('checked','checked')
			}
			else{
				//Otherwise remove any preset checkboxes
				$this.removeAttr('checked');
			}
			
			//Here we generate the input. The input takes the checkboxes name and value
			//It's an exact duplicate so you dont need to build your form, API, or backend any differently
			$this.after('<input style="display:none;" type="text" class="nullified-input" value="'+$this.val()+'" name="'+$this.attr('name')+'">')
			//Then we change the value of checkbox to be prepended with "nullify-", so it doesn't conflict
			.val('nullify-'+$this.val())
			//Same as the the value change, but name
			.attr('name','nullify-'+$this.attr('name'))
			//Now, we'll bind a click event to each checkbox
			.bind('click',function(){
				//This strips out the nullify on the checkbox so we can find the matching
				//input in case some other JS modifiyng the DOM (like Uniform)
				_matchingInput = $(this).attr('name').split('nullify-')[1];
				//If, on click, this item is checked...
				if($(this).attr('checked')){
					//Check it and change the value of the hidden input
					$(this).attr('checked','checked');
					$('[name='+_matchingInput+']').val(options.on);
				}
				else{
					//To reverse of above, remove check and change value
					$(this).removeAttr('checked');
					$('[name='+_matchingInput+']').val(options.off);
				}
				//This is a anon function to be called if the user wants after the
				//checkbox is toggled.
				options.afterToggle();
			});
		});
		
	};
})(jQuery);


/*
	Takes care of making upload forms work via AJAX, just add water
*/

(function($){
	$.fn.uploadify = function(options) {
		var defaults = {
			type:'text',
			onUpload:function(){},
			afterUpload:function(){}
		};
		return this.each(function(i) {
			options = $.extend(true, defaults, options);
			
			$this = $(this);
			
			
			//Make sure the form is set to send binary, and if not fix that
			if($this.attr('enctype')!=='multipart/form-data'){
				$this.attr('enctype','multipart/form-data');
			}
			//Make sure the form is set to POST the data, if not, fix that
			if($this.attr('method')!=='post'){
				$this.attr('method','post');
			}
			
			
			$this.attr('target','upload_target_'+i).append('<iframe style="display:none;" src="" id="upload_target_'+i+'" class="uploadify-iframe"></iframe>');
			
			
			$this.bind('submit',function(e){
				options.onUpload();
				$('#upload_target_'+i).load(function(){
					_returnValue = $(this).contents().find('body').html();
					
					if(options.type == 'json'){
						_returnValue = JSON.parse(_returnValue);
					}
					
					options.afterUpload.call(this,_returnValue);
					$(this).replaceWith($(this).clone());
				});
				
			});
			
		});
	};
})(jQuery);

/*
	This plugin allows you to trim a line and add ellipsis after a string passes
	the max amount of characters you specify
*/
(function($){
	$.fn.ellipsify = function(options) {
		var defaults = {
			max:140
		};
		return this.each(function() {
			options = $.extend(true, defaults, options);
			
			$this = $(this);
			
			if(typeof options.max == 'number'){
				$this.attr('title',$this.html()).html($this.html().slice(0,options.max)+'&hellip;');
			}
			else{
				//To do
				//$this.attr('title',$this.html()).css({width:options.max,overflow:'hidden'}).wrap('<div></div>').parent().find('div').append('XXX');
			}
			
		});
	};
})(jQuery);

/*
	Takes a DOM element and then converts it to an editable text/textarea field
*/
(function($){
	$.fn.editify = function(options) {
		var defaults = {
			type:'input',
			autoWidth:'auto',
			autoHeight:'auto',
			content:'auto'
		};
		return this.each(function() {
			options = $.extend(true, defaults, options);
			
			$this = $(this);
			
			$this.bind('click',function(){
				if(options.type == 'input'){
					$this.replaceWith('<input type="text" value="'+$this.html()+'">');
				}
				else if(options.type == 'textarea'){
					$this.after('<textarea style="width:'+$this.width()+'px" class="editify editify-textarea">'+$this.html()+'</textarea>').siblings('.editify').select();
				}
				
			});
			
		});
	};
})(jQuery);

$(function(){
	//New way  to handle checkboxes!
	$('.nullify').nullify({
		//This allows us to do something AFTER we toggle, which in this case
		//updates uniform, however, this could be anything.
		afterToggle:function(){
			$.uniform.update();
		}
	});
	
	
	//Uploader
	
	$('#media_gallery form').uploadify({
		type:'json',
		onUpload:function(){
			$.fancybox({href:base_url + 'images/shared/loader.gif',type:'image',showCloseButton:false});
		},
		afterUpload:function(json){
			$.fancybox.close();
			$('#media_gallery .drag_wrap > ul').append('\
				<li class="media_item" style="cursor:pointer">\
					<a href="#fancybox"><img width="125" height="125" src="'+base_url+' media/images/'+json.data.category_id+'/small_'+json.data.content+'"></a>\
					<ul class="media_actions">\
						<li style="cursor: pointer; "><a href=""><span class="actions action_see"></span> View</a></li>\
						<li style="cursor: pointer; "><a href=""><span class="actions action_edit"></span> Edit</a></li>\
					</ul>\
				</li>');
		}
	});
	
});


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

