if (jQuery.url.attr('port'))
{
	var url_port = ':' + jQuery.url.attr('port');
}
else
{
	var url_port = '';
}
var base_url = jQuery.url.attr('protocol') + '://' + jQuery.url.attr('host') + url_port;

// Renders placeholder
function doPlaceholder(id, placeholder)
{		
	if ($(id).length && $(this).val() == '')
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
function ValidateEmailAddress(email)
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

// Gets Count of Feed Items
function getCountNew(element)
{	
	var request 		= $(element).attr('id');
	var current_class	= $(element).attr('class');
	var type			= $(element).attr('rel');
			
	$.get(base_url + '/' + type + '/count_new', function(html)
	{
		if(parseFloat(html))
		{	// Adds msg_notifation class to feed_count_new
			$('#' + request).html(html).addClass(current_class + ' msg_notification');
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

// For Geo Locating Updates, Posts, Etc...
function initiate_geolocation()
{
	if (navigator.geolocation)
	{
	  navigator.geolocation.getCurrentPosition(handle_geolocation_query, handle_error);
	}
	else
	{
	  yqlgeo.get('visitor', normalize_yql_response);
	}
}

function handle_error(error)
{
	switch(error.code)
	{
	  case error.PERMISSION_DENIED: alert("user did not share Geolocation data");
	  break;
	  case error.POSITION_UNAVAILABLE: alert("could not detect current position");
	  break;
	  case error.TIMEOUT: alert("retrieving position timedout");
	  break;
	  default: alert("unknown error");
	  break;
	}
}

function normalize_yql_response(response)
{
	if (response.error)
	{
	  var error = { code : 0 };
	  handle_error(error);
	  return;
	}
	
	var position = {
		coords : {
			latitude: response.place.centroid.latitude,
			longitude: response.place.centroid.longitude
		},
		address : {
			city: response.place.locality2.content,
			region: response.place.admin1.content,
			country: response.place.country.content
		}
	};

	handle_geolocation_query(position);
}

function handle_geolocation_query(position)
{  
    $('#latitude').val(position.coords.latitude)
    $('#longitude').val(position.coords.longitude)
    $('#accuracy').val(position.coords.accuracy)
 /* For Google Map  
 		var image_url = "http://maps.google.com/maps/api/staticmap?sensor=false&center=" + position.coords.latitude+','+position.coords.longitude +  
                    "&zoom=14&size=140x80&markers=color:red|label:S|"+position.coords.latitude+','+position.coords.longitude;  
 
	jQuery("#status_find_location").css("background-image", "url(../images/dashboard_default/icons/location_24.png) 0px 0px no-repeat").html('<span>'+position.address.city+', '+position.address.region+'</span>');  
 */

}  

jQuery(window).ready(function(){
	jQuery(".find_location").click(initiate_geolocation);
})  
