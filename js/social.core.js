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

function isCoreModule(module, core_modules)
{
	if (jQuery.inArray(module, core_modules) !== -1)
	{	
		return true;
	};

	return false;
}


function cleanFieldEmpty(id, placeholder)
{	
	if ($(id).val() == placeholder)
	{
		$(id).val('');		
	}
	
	return false;
}

function cleanAllFieldsEmpty(validation_rules)
{
	$.each(validation_rules, function(key, item)
	{
		cleanFieldEmpty(item.element, item.holder);
	});
	
	return false;
}

function makePlaceholders(validation_rules)
{
	$.each(validation_rules, function(key, item)
	{
		doPlaceholder(item.element, item.holder);
	});
	
	return false;
}

// Really simple validator... should add rules
// If message is set it gets added to validate
function validationRules(validation_rules)
{
	var check_count = 0;
	var valid_count = 0;
	
	$.each(validation_rules, function(key, item)
	{			 
		if (item.message != '')
		{				
			check_count++;
			if (isFieldValid(item.element, item.holder, item.message) == true)
			{
				valid_count++;
			}	
		}
	});
	
	if (check_count == valid_count)
	{
		return true;
	}
	
	return false;
}


function displayModuleAssets(module, core_modules, core_assets)
{
	if (isCoreModule(module, core_modules) == true)
	{
		path = core_assets;
	}
	else
	{
		path = base_url + 'application/modules/' + module + '/assets/';
	}

	return path;
}


//For God's sake, disable autocomplete!
$(function(){ $('input').attr('autocomplete','off'); });

// Allows for easy user notifications and if "how" the notifiy ever works it'll be site wide.
// Use like: $('#content_message').notify({message:'Something has been updated!'});
(function($)
{
	$.fn.notify = function(options)
	{
		var settings =
		{
			status 	: 'error', 					// Status either: success, error
			message : 'Content has been saved', // The message
			appendTo: '.content_wrap', 			// Where to add the message
			timeout : 5000, 					// How long to wait before hiding message
			speed   : 'normal', 				// Animation speed
			complete: 'hide',					// Accepts 'hide' 'nohide' 'redirect' (last option needs value)
			redirect: ''
		};
		
		return this.each(function()
		{
			//Merge the options and settings
			options = $.extend({},settings,options);
			
			//Save "this"
			var $this = $(this);
			
			// Message Class
			if (options.status == 'success') var message_class = 'message_success';
			else var message_class = 'message_alert';
			
			// If it's not already, hide the thing to be shown, add content, classes, then show it!			
			$this.css({display:'none'}).delay(500).html(options.message).addClass(message_class).show(options.speed);
			
			// Do complete action
			if (options.complete == 'hide')
			{
				$this.delay(options.timeout).hide(options.speed, function()
				{
					$this.removeClass(message_class).empty();
				});
			}
			else if (options.complete == 'redirect')
			{
				setTimeout(function() { window.location.href = options.redirect }, options.timeout);
			}
		});
	};
})( jQuery );


/* Allows for easy dynamic "sluggable" urls to be previewed to the user based on an input field and the current URL */
(function($)
{
	$.fn.slugify = function(options)
	{
		/*
		 Settings:
		 slug	: Element where "slug" preview is. Can be .slug-preview & "http://mysite.com/" will be injected to it when the plugin is called
		 url	: The base url i.e. mysite.com/blog/ and then slugify will add "hello world" like: mysite.com/blog/hello-world
		name	: This is the name you want to give the hidden input field. For example: name:'slug' then <input name="slug"... will be added to the DOM read for your form's submission.
		
		classPrefix [default='slugify']
			Is prepended to the class names in the plugin, so, for example if change the default to slugger, you could do .slugger-input in your CSS and style the generated input
		*/
		var settings =
		{
			"slug"			: '',
			"url"			: '',
			"name"			: 'slug',
			"classPrefix"	: 'slugify',
			"slugTag"		: 'span',
			"inputType"		: 'text',
			"slugValue"		: ''
		};
		return this.each(function()
		{	
			options = $.extend({},settings,options);	//Merge the options and settings
			var $this = $(this);						//Save "this"
			
			// Converts string to valid "sluggable" URL (private function tho!)
			function _convertToSlug(str)
			{
				slug_val = str.replace(/ /g,'-').toLowerCase();						//Converts to lowercase then makes spaces into dahes
				slug_val = slug_val.match(/[\w\d\-]/g).toString().replace(/,/g,'');	// Strips special characters
				return slug_val;
			}
			
			// This add line the default url into the element specified by uset, creates span by default and puts the default value of the slug on load, if exists
			// Also creates the input element and gives it the same value as the span
			$(options.slug).html(options.url+'<'+options.slugTag+' class="'+options.classPrefix+'-preview">'+options.slugValue+'</'+options.slugTag+'><input class="'+options.classPrefix+'-input" type="'+options.inputType+'" value="'+options.slugValue+'" name="'+options.name+'">')
				
				.find('.'+options.classPrefix+'-input').hide()	// hide input element that was just created

				// bind a click event to make span editable that's holding the edited slug
				.end().find('.'+options.classPrefix+'-preview').bind('click',function()
				{	
					$(this).addClass(options.classPrefix+'-modified');	//Add class to original selected element that says element has been modified
					$(this).hide();										//Hide the span
					$(options.slug+' .'+options.classPrefix+'-input').show().focus().select()	//Show the input, focus on it, and highlight the text
					
					// When user clicks outside
					.blur(function()
					{	
						// If value is blank
						if($(this).val() == '')
						{
							// Convert the value to the input box again
							_revertedValue = _convertToSlug($(this).val());
							
							$(this).val(_revertedValue);
							
							// Update the span
							$(options.slug+' .'+options.classPrefix+'-preview').text(_revertedValue);
							
							// Make as if it wasn't editied. This will make any mods to the input show up here in the span again
							$this.removeClass(options.classPrefix+'-modified');
						}
						else
						{
							// Take value of input and convert it to a URL safe value
							$(this).val(_convertToSlug($(this).val())).hide();
							
							// Do the same to the span
							$(options.slug + ' .' + options.classPrefix + '-preview').text(_convertToSlug($(this).val())).show();
						}
					})
					.bind('keyup',function()
					{
						$(options.slug+' .'+options.classPrefix+'-preview').text($(this).val());
					});
				})
			
			//update on each keyup
			$this.bind('keyup',function()
			{
				if(!$this.hasClass(options.classPrefix+'-modified'))
				{
					var _sluggedURL = '';
					
					if($(this).val())
					{ //If there's a value, convert it to a slug
						_sluggedURL = _convertToSlug($(this).val());
					}
					//Actually add the new slug, then, rejoice!
					$(options.slug + ' .' + options.classPrefix + '-preview').text(_sluggedURL);
					$(options.slug + ' .' + options.classPrefix + '-input').val(_sluggedURL);
				}
			});
		});
	};
})( jQuery );


/* Returns null on a form submit if unchecked rather than browser default of nothing. */
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


/* Uploadigy - makes upload forms work via AJAX, just add water */
(function($){
	$.fn.uploadify = function(options)
	{
		var defaults = {
			type		:'text',
			onUpload	:function(){},
			afterUpload	:function(){}
		};
		
		return this.each(function(i)
		{
			options = $.extend(true, defaults, options);
			
			$this = $(this);
			
			//Make sure the form is set to send binary, and if not fix that
			if($this.attr('enctype')!=='multipart/form-data')
			{
				$this.attr('enctype','multipart/form-data');
			}
			
			//Make sure the form is set to POST the data, if not, fix that
			if($this.attr('method')!=='post')
			{
				$this.attr('method','post');
			}
			
			$this.attr('target','upload_target_'+i).append('<iframe style="display:none;" src="" id="upload_target_'+i+'" class="uploadify-iframe"></iframe>');			
			
			$this.bind('submit',function(e)
			{
				options.onUpload();
				
				$('#upload_target_'+i).load(function()
				{
					_returnValue = $(this).contents().find('body').html();
					
					if(options.type == 'json')
					{
						_returnValue = JSON.parse(_returnValue);
					}
					
					options.afterUpload.call(this,_returnValue);
					$(this).replaceWith($(this).clone());
				});
			});
		});
	};
})(jQuery);


/* Ellipsify - allows you to trim a line and add ellipsis after a string passes the max amount of characters you specify */
(function($){
	$.fn.ellipsify = function(options)
	{
		var defaults = {
			max:140
		};
		return this.each(function()
		{
			options = $.extend(true, defaults, options);
			
			$this = $(this);
			
			if(typeof options.max == 'number')
			{
				$this.attr('title',$this.html()).html($this.html().slice(0,options.max)+'&hellip;');
			}
			else
			{
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
	$.fn.editify = function(options) 
	{
		var defaults = {
			type:'input',
			autoWidth:'auto',
			autoHeight:'auto',
			content:'auto',
			on:'click'
		};
		return this.each(function() 
		{
			options = $.extend(true, defaults, options);
			
			$this = $(this);	
			
			_convertToEditableField = function(_$this){
				_displaType = _$this.css('display');
				if(options.type == 'input'){
					_$this.after('<input style="width:'+-$this.width()+'px" type="text" class="editify editify-input" value="'+_$this.html()+'">')
						.siblings('.editify').select().focus().blur(function(){
							_$this.css({display:_displaType}).text($(this).val());
							$(this).remove();
						})
					.end().css({display:'none'});
				}
				else if(options.type == 'textarea'){
					_$this.after('<textarea style="width:'+_$this.width()+'px" class="editify editify-textarea">'+_$this.html()+'</textarea>').siblings('.editify').select();
				}
			}
			
			if(options.on == 'load')
			{
				_convertToEditableField($this);
			}
			else
			{
				$this.bind(options.on,function(){
					_convertToEditableField($this)
				});
			}
		});
	};
})(jQuery);


/* Modal Maker */
(function($)
{	
	$.modalMaker = function(options)
	{
		var defaults = 
		{
    		partial	:'',
    		api		:'',
    		template:{},
    		callback:function() {}
  		}

		var settings = $.extend(defaults,options);

		$.get(settings.partial, function(html)
		{
			var modal_html = html;
			
			$.get(settings.api, function(json)
			{
				modal_html = $.template(modal_html, settings.template);
				settings.callback.call(this, modal_html);
			});
		});
	};
})(jQuery);


/* Google Maps API Functions */
var geocoder;
var map;
var markersArray = [];

// Loads Map Title
function getMap(lat_long, element)
{		
	geocoder = new google.maps.Geocoder();
	var myOptions =
	{
  		zoom				: 14,
  		center				: lat_long,
		panControl			: false,
		zoomControl			: true,
		mapTypeControl		: false,
		scaleControl		: true,
		streetViewControl	: false,
		overviewMapControl	: false,	  		
  		mapTypeId: google.maps.MapTypeId.ROADMAP
	}

	map = new google.maps.Map(document.getElementById(element), myOptions);
	
	addMarker(lat_long);	
}

function getMapGeocode(address)
{		
	geocoder.geocode({'address' : address}, function(results, status)
	{
		if (status == google.maps.GeocoderStatus.OK)
		{
			// Center Tile
	    	map.setCenter(results[0].geometry.location);
		
			// Clear & Make Markers
	    	clearOverlays();
	    	deleteOverlays();	
	    	addMarker(results[0].geometry.location);
	    	
	    	// Set Geo
	    	$("[name=geo_lat]").val(results[0].geometry.location.Aa);
	    	$("[name=geo_long]").val(results[0].geometry.location.Ca);
		}
		else
		{
			console.log("Could not get Google map");
		}
	}); 
}

function addMarker(location)
{
	marker = new google.maps.Marker(
  	{
  		position: location,
    	map: map
  });
  
  markersArray.push(marker);
}

function clearOverlays()
{
	if (markersArray)
	{
		for (i in markersArray)
		{
			markersArray[i].setMap(null);
		}
	}
}		

function deleteOverlays()
{
	if (markersArray)
	{
		for (i in markersArray)
		{
			markersArray[i].setMap(null);
		}
	
		markersArray.length = 0;
	}
}


/**
 * @requires jQuery
 * Takes a MySQL timestamp and renders it into a "relative" time like "2 days ago"
 * @todo make it notice "moments ago"
 * @todo make it accept unix timestamps
 * @todo make it accept just a string like $.relativetime('10-10-10...')
 * @todo make it accept future dates
 * @todo have it auto update times
 **/
(function($){
	$.relativetime = function(options) {
		var defaults = {
			time:new Date(),
			suffix:'ago',
			prefix:''
		};

		options = $.extend(true, defaults, options);
		
		//Fixes NaN in some browsers by removing dashes...
		_dateStandardizer = function(dateString){
			modded_date = options.time.toString().replace(/-/g,' ');
			return new Date(modded_date)
		}

		//Time object with all the times that can be used throughout
		//the plugin and for later extensions.
		time = {
			unmodified:options.time, //the original time put in
			original:_dateStandardizer(options.time).getTime(), //time that was given in UNIX time
			current:new Date().getTime(), //time right now
			displayed:'' //what will be shown
		}
		//The difference in the unix timestamps
		time.diff = time.current-time.original;

		//Here we save a JSON object with all the different measurements
		//of time. "week" is not yet in use.
		time.segments = {
			second:time.diff/1000,
			minute:time.diff/1000/60,
			hour:time.diff/1000/60/60,
			day:time.diff/1000/60/60/24,
			week:time.diff/1000/60/60/24/7,
			month:time.diff/1000/60/60/24/30,
			year:time.diff/1000/60/60/24/365
		}
		
		//Takes a string and adds the prefix and suffix options around it
		_uffixWrap = function(str){
			return options.prefix+' '+str+' '+options.suffix;
		}
		
		//Converts the time to a rounded int and adds an "s" if it's plural
		_niceDisplayDate = function(str,date){
			_roundedDate = Math.round(date);
			s='';
			if(_roundedDate !== 1){ s='s'; }
			return _uffixWrap(_roundedDate+' '+str+s)
		}
		
		//Now we loop through all the times and find out which one is
		//the right one. The time "days", "minutes", etc that gets
		//shown is based on the JSON time.segments object's keys
		for(x in time.segments){
			if(time.segments[x] >= 1){
				time.displayed = _niceDisplayDate(x,time.segments[x])
			}
			else{
				break;
			}
		}
		
		//If time.displayed is still blank (a bad date, future date, etc)
		//just return the original, unmodified date.
		if(time.displayed == ''){time.displayed = time.unmodified;}
		
		//Give it to em!
		return time.displayed;

	};
})(jQuery);

$(function(){	
	
	$('#fancybox-title').live('click',function(){
		$(this).editify({on:'load'});
	});
	
	
	//New way  to handle checkboxes!
	$('.nullify').nullify({
		//This allows us to do something AFTER we toggle, which in this case
		//updates uniform, however, this could be anything.
		afterToggle:function(){
			$.uniform.update();
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


/**
 * Checks for for a user image in the DB, if none
 * checks gravatar, if no image on gravatar either
 * sets a default image.
 *
 * @requires utf8_encode() required for md5()
 * @requires md5() required to get Gravatar image
 *
 * @param json {obj} json object containing json.image and json.gravatar
 * @param size {string} Can be either "small", "medium", or "large"
 *
 * @returns {string} URL to image
 **/

function getUserImageSrc(json, size)
{	
	//Sets the default size, medium and then changes the name to be easier to use.
	//instead of small, normal, and bigger, it changes it to small, medium, and large
	if(!size){size='medium';} //if no size was specified
	
	if(size == 'large')
	{
		_localImgSize = 'bigger'
	}
	else if(size == 'small')
	{
		_localImgSize = 'small' 
	}
	else
	{
		_localImgSize = 'medium'
	}
	
	//Default gravatar size is "medium", or, 48px
	//if you change it, this modifies the gravatar size from small, medium, or large
	//to the px sizes 35, 48, and 175
	_gravatarSize = '48'
	
	if(size == 'large')
	{
		_gravatarSize = '175'
	}
	else if(size == 'small')
	{
		_gravatarSize = '35'
	}
	
	//If the user uploaded his own image
	if (json.image != '')
	{	
		_imgSrcOutput = '/uploads/profiles/'+json.user_id+'/'+_localImgSize+'_'+json.image
	}
	//Otherwise check gravatar, and/or return the default "no image" image
	else
	{	
		_imgSrcOutput = 'http://gravatar.com/avatar.php?gravatar_id='+json.gravatar+'&s='+_gravatarSize+'&d='+base_url+'/uploads/profiles/'+_localImgSize+'_nopicture.png';
	}
	
	return _imgSrcOutput;
}

/**
 * Takes a string and JSON of a list of template tags and what to replace with
 * and returns a new string with all the replacements
 **/
(function($){
	$.template = function(str,json,callback) {
		for(x in json){
			pattern = new RegExp('{'+x+'}','g');
			str = str.replace(pattern,json[x]);
		}
		return str;
	};
})(jQuery);
 
 
/**
 * Fancy date stuff...
 * time returns a time like: 6:00 pm or, 7:00 am instead of 24hr time.
 * date returns a date like: 12/12/2012 or, 12/12/12 depending on what string you give it
 * @param str {string} Give it a MySQL formatted date string.
 **/

var mysqlDateParser = function(str){
	if(str){
		_str = str;
	}
	else{
		_str = '0000-00-00 00:00:00';
	}
	var api = {
		date: function(type){
			type = type || 'number';
			m = _str.match(/([0-9])+/gi);
			if(type=='short'){
				months = {'00':'00','01':'Jan','02':'Feb','03':'Mar','04':'Apr','05':'May','06':'Jun','07':'Jul','08':'Aug','09':'Sep','10':'Oct','11':'Nov','12':'Dec'};
			}
			else if(type=='long'){
				months = {'00':'00','01':'January','02':'February','03':'March','04':'April','05':'May','06':'June','07':'July','08':'August','09':'September','10':'October','11':'November','12':'December'};
			}
			if(type!=='number'){
				m[1]=months[m[1]];
				d=' ';
			}
			else{
				d='/';
			}
			return m[1]+d+m[2]+d+m[0];
		},
		time: function(){
			m = _str.match(/([0-9])+/gi)
			pmOrAm = 'AM';
			if(m[3]>12){
				m[3] = m[3]-12;
				pmOrAm = 'PM';
			}
			return m[3]+':'+m[4]+' '+pmOrAm;
		}
	}
	return api;
}

function utf8_encode ( argString ) {
    var string = (argString+''); // .replace(/\r\n/g, "\n").replace(/\r/g, "\n");

    var utftext = "";
    var start, end;
    var stringl = 0;

    start = end = 0;
    stringl = string.length;
    for (var n = 0; n < stringl; n++) {
        var c1 = string.charCodeAt(n);
        var enc = null;

        if (c1 < 128) {
            end++;
        } else if (c1 > 127 && c1 < 2048) {
            enc = String.fromCharCode((c1 >> 6) | 192) + String.fromCharCode((c1 & 63) | 128);
        } else {
            enc = String.fromCharCode((c1 >> 12) | 224) + String.fromCharCode(((c1 >> 6) & 63) | 128) + String.fromCharCode((c1 & 63) | 128);
        }
        if (enc !== null) {
            if (end > start) {
                utftext += string.substring(start, end);
            }
            utftext += enc;
            start = end = n+1;
        }
    }

    if (end > start) {
        utftext += string.substring(start, string.length);
    }

    return utftext;
}

/**
 * @returns {string} a MD5 hash of the string you give it
 * @requires utf8_encode()
 **/
function md5 (str) {
    var xl;

    var rotateLeft = function (lValue, iShiftBits) {
        return (lValue<<iShiftBits) | (lValue>>>(32-iShiftBits));
    };

    var addUnsigned = function (lX,lY) {
        var lX4,lY4,lX8,lY8,lResult;
        lX8 = (lX & 0x80000000);
        lY8 = (lY & 0x80000000);
        lX4 = (lX & 0x40000000);
        lY4 = (lY & 0x40000000);
        lResult = (lX & 0x3FFFFFFF)+(lY & 0x3FFFFFFF);
        if (lX4 & lY4) {
            return (lResult ^ 0x80000000 ^ lX8 ^ lY8);
        }
        if (lX4 | lY4) {
            if (lResult & 0x40000000) {
                return (lResult ^ 0xC0000000 ^ lX8 ^ lY8);
            } else {
                return (lResult ^ 0x40000000 ^ lX8 ^ lY8);
            }
        } else {
            return (lResult ^ lX8 ^ lY8);
        }
    };

    var _F = function (x,y,z) { return (x & y) | ((~x) & z); };
    var _G = function (x,y,z) { return (x & z) | (y & (~z)); };
    var _H = function (x,y,z) { return (x ^ y ^ z); };
    var _I = function (x,y,z) { return (y ^ (x | (~z))); };

    var _FF = function (a,b,c,d,x,s,ac) {
        a = addUnsigned(a, addUnsigned(addUnsigned(_F(b, c, d), x), ac));
        return addUnsigned(rotateLeft(a, s), b);
    };

    var _GG = function (a,b,c,d,x,s,ac) {
        a = addUnsigned(a, addUnsigned(addUnsigned(_G(b, c, d), x), ac));
        return addUnsigned(rotateLeft(a, s), b);
    };

    var _HH = function (a,b,c,d,x,s,ac) {
        a = addUnsigned(a, addUnsigned(addUnsigned(_H(b, c, d), x), ac));
        return addUnsigned(rotateLeft(a, s), b);
    };

    var _II = function (a,b,c,d,x,s,ac) {
        a = addUnsigned(a, addUnsigned(addUnsigned(_I(b, c, d), x), ac));
        return addUnsigned(rotateLeft(a, s), b);
    };

    var convertToWordArray = function (str) {
        var lWordCount;
        var lMessageLength = str.length;
        var lNumberOfWords_temp1=lMessageLength + 8;
        var lNumberOfWords_temp2=(lNumberOfWords_temp1-(lNumberOfWords_temp1 % 64))/64;
        var lNumberOfWords = (lNumberOfWords_temp2+1)*16;
        var lWordArray=new Array(lNumberOfWords-1);
        var lBytePosition = 0;
        var lByteCount = 0;
        while ( lByteCount < lMessageLength ) {
            lWordCount = (lByteCount-(lByteCount % 4))/4;
            lBytePosition = (lByteCount % 4)*8;
            lWordArray[lWordCount] = (lWordArray[lWordCount] | (str.charCodeAt(lByteCount)<<lBytePosition));
            lByteCount++;
        }
        lWordCount = (lByteCount-(lByteCount % 4))/4;
        lBytePosition = (lByteCount % 4)*8;
        lWordArray[lWordCount] = lWordArray[lWordCount] | (0x80<<lBytePosition);
        lWordArray[lNumberOfWords-2] = lMessageLength<<3;
        lWordArray[lNumberOfWords-1] = lMessageLength>>>29;
        return lWordArray;
    };

    var wordToHex = function (lValue) {
        var wordToHexValue="",wordToHexValue_temp="",lByte,lCount;
        for (lCount = 0;lCount<=3;lCount++) {
            lByte = (lValue>>>(lCount*8)) & 255;
            wordToHexValue_temp = "0" + lByte.toString(16);
            wordToHexValue = wordToHexValue + wordToHexValue_temp.substr(wordToHexValue_temp.length-2,2);
        }
        return wordToHexValue;
    };

    var x=[],
        k,AA,BB,CC,DD,a,b,c,d,
        S11=7, S12=12, S13=17, S14=22,
        S21=5, S22=9 , S23=14, S24=20,
        S31=4, S32=11, S33=16, S34=23,
        S41=6, S42=10, S43=15, S44=21;

    str = this.utf8_encode(str);
    x = convertToWordArray(str);
    a = 0x67452301; b = 0xEFCDAB89; c = 0x98BADCFE; d = 0x10325476;
    
    xl = x.length;
    for (k=0;k<xl;k+=16) {
        AA=a; BB=b; CC=c; DD=d;
        a=_FF(a,b,c,d,x[k+0], S11,0xD76AA478);
        d=_FF(d,a,b,c,x[k+1], S12,0xE8C7B756);
        c=_FF(c,d,a,b,x[k+2], S13,0x242070DB);
        b=_FF(b,c,d,a,x[k+3], S14,0xC1BDCEEE);
        a=_FF(a,b,c,d,x[k+4], S11,0xF57C0FAF);
        d=_FF(d,a,b,c,x[k+5], S12,0x4787C62A);
        c=_FF(c,d,a,b,x[k+6], S13,0xA8304613);
        b=_FF(b,c,d,a,x[k+7], S14,0xFD469501);
        a=_FF(a,b,c,d,x[k+8], S11,0x698098D8);
        d=_FF(d,a,b,c,x[k+9], S12,0x8B44F7AF);
        c=_FF(c,d,a,b,x[k+10],S13,0xFFFF5BB1);
        b=_FF(b,c,d,a,x[k+11],S14,0x895CD7BE);
        a=_FF(a,b,c,d,x[k+12],S11,0x6B901122);
        d=_FF(d,a,b,c,x[k+13],S12,0xFD987193);
        c=_FF(c,d,a,b,x[k+14],S13,0xA679438E);
        b=_FF(b,c,d,a,x[k+15],S14,0x49B40821);
        a=_GG(a,b,c,d,x[k+1], S21,0xF61E2562);
        d=_GG(d,a,b,c,x[k+6], S22,0xC040B340);
        c=_GG(c,d,a,b,x[k+11],S23,0x265E5A51);
        b=_GG(b,c,d,a,x[k+0], S24,0xE9B6C7AA);
        a=_GG(a,b,c,d,x[k+5], S21,0xD62F105D);
        d=_GG(d,a,b,c,x[k+10],S22,0x2441453);
        c=_GG(c,d,a,b,x[k+15],S23,0xD8A1E681);
        b=_GG(b,c,d,a,x[k+4], S24,0xE7D3FBC8);
        a=_GG(a,b,c,d,x[k+9], S21,0x21E1CDE6);
        d=_GG(d,a,b,c,x[k+14],S22,0xC33707D6);
        c=_GG(c,d,a,b,x[k+3], S23,0xF4D50D87);
        b=_GG(b,c,d,a,x[k+8], S24,0x455A14ED);
        a=_GG(a,b,c,d,x[k+13],S21,0xA9E3E905);
        d=_GG(d,a,b,c,x[k+2], S22,0xFCEFA3F8);
        c=_GG(c,d,a,b,x[k+7], S23,0x676F02D9);
        b=_GG(b,c,d,a,x[k+12],S24,0x8D2A4C8A);
        a=_HH(a,b,c,d,x[k+5], S31,0xFFFA3942);
        d=_HH(d,a,b,c,x[k+8], S32,0x8771F681);
        c=_HH(c,d,a,b,x[k+11],S33,0x6D9D6122);
        b=_HH(b,c,d,a,x[k+14],S34,0xFDE5380C);
        a=_HH(a,b,c,d,x[k+1], S31,0xA4BEEA44);
        d=_HH(d,a,b,c,x[k+4], S32,0x4BDECFA9);
        c=_HH(c,d,a,b,x[k+7], S33,0xF6BB4B60);
        b=_HH(b,c,d,a,x[k+10],S34,0xBEBFBC70);
        a=_HH(a,b,c,d,x[k+13],S31,0x289B7EC6);
        d=_HH(d,a,b,c,x[k+0], S32,0xEAA127FA);
        c=_HH(c,d,a,b,x[k+3], S33,0xD4EF3085);
        b=_HH(b,c,d,a,x[k+6], S34,0x4881D05);
        a=_HH(a,b,c,d,x[k+9], S31,0xD9D4D039);
        d=_HH(d,a,b,c,x[k+12],S32,0xE6DB99E5);
        c=_HH(c,d,a,b,x[k+15],S33,0x1FA27CF8);
        b=_HH(b,c,d,a,x[k+2], S34,0xC4AC5665);
        a=_II(a,b,c,d,x[k+0], S41,0xF4292244);
        d=_II(d,a,b,c,x[k+7], S42,0x432AFF97);
        c=_II(c,d,a,b,x[k+14],S43,0xAB9423A7);
        b=_II(b,c,d,a,x[k+5], S44,0xFC93A039);
        a=_II(a,b,c,d,x[k+12],S41,0x655B59C3);
        d=_II(d,a,b,c,x[k+3], S42,0x8F0CCC92);
        c=_II(c,d,a,b,x[k+10],S43,0xFFEFF47D);
        b=_II(b,c,d,a,x[k+1], S44,0x85845DD1);
        a=_II(a,b,c,d,x[k+8], S41,0x6FA87E4F);
        d=_II(d,a,b,c,x[k+15],S42,0xFE2CE6E0);
        c=_II(c,d,a,b,x[k+6], S43,0xA3014314);
        b=_II(b,c,d,a,x[k+13],S44,0x4E0811A1);
        a=_II(a,b,c,d,x[k+4], S41,0xF7537E82);
        d=_II(d,a,b,c,x[k+11],S42,0xBD3AF235);
        c=_II(c,d,a,b,x[k+2], S43,0x2AD7D2BB);
        b=_II(b,c,d,a,x[k+9], S44,0xEB86D391);
        a=addUnsigned(a,AA);
        b=addUnsigned(b,BB);
        c=addUnsigned(c,CC);
        d=addUnsigned(d,DD);
    }

    var temp = wordToHex(a)+wordToHex(b)+wordToHex(c)+wordToHex(d);

    return temp.toLowerCase();
}

/**
 *Converts a date to a ISO8601 format
 *@returns {string} Something like 2009-09-28T19:03:12Z
 **/
function ISODateString(d){
 function pad(n){return n<10 ? '0'+n : n}
 return d.getUTCFullYear()+'-'
      + pad(d.getUTCMonth()+1)+'-'
      + pad(d.getUTCDate())+'T'
      + pad(d.getUTCHours())+':'
      + pad(d.getUTCMinutes())+':'
      + pad(d.getUTCSeconds())+'Z'}
