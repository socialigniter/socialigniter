if (jQuery.url.attr('port'))
{
	var url_port = ':' + jQuery.url.attr('port');
}
else
{
	var url_port = '';
}
var base_url = jQuery.url.attr('protocol') + '://' + jQuery.url.attr('host') + url_port + '/';
var current_module = jQuery.url.segment(1);

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
			
	$.get(base_url + 'home/' + type + '/count_new', function(html)
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



/*
  YQL Geo library by Christian Heilmann
  Homepage: http://isithackday.com/geo/yql-geo-library
  Copyright (c)2010 Christian Heilmann
  Code licensed under the BSD License:
  http://wait-till-i.com/license.txt
*/
var yqlgeo = function(){
  var callback;
  function get(){
    var args = arguments;
    for(var i=0;i<args.length;i++){
      if(typeof args[i] === 'function'){
        callback = args[i];
      }
    }
    if(args[0] === 'visitor'){getVisitor();}
    if(typeof args[0] === 'string' && args[0] != 'visitor'){
      if(args[0]){
        if(/^http:\/\/.*/.test(args[0])){
          getFromURL(args[0]);
        } else if(/^[\d+\.?]+$/.test(args[0])){
          getFromIP(args[0]);
        } else {
          getFromText(args[0]);
        }
      } 
    }
    var lat = args[0];
    var lon = args[1];
    if(typeof lat.join !== undefined && args[0][1]){
      lat = args[0][0];
      lon = args[0][1];
    };    
    if(isFinite(lat) && isFinite(lon)){
      if(lat > -90 && lat < 90 &&
         lon > -180 && lon < 180){
        getFromLatLon(lat,lon);
      }
    }
  }
  function getVisitor(){
    if(navigator.geolocation){
       navigator.geolocation.getCurrentPosition(
        function(position){
          getFromLatLon(position.coords.latitude,
                        position.coords.longitude);
        },
        function(error){
          retrieveip();
        }
      );
    } else{
      retrieveip();
    }
  };

  function getFromIP(ip){
    var yql = 'select * from geo.places where woeid in ('+
              'select place.woeid from flickr.places where (lat,lon) in('+
              'select Latitude,Longitude from ip.location'+
              ' where ip="'+ip+'"))';
    load(yql,'yqlgeo.retrieved');
  };

  function retrieveip(){
    jsonp('http://jsonip.appspot.com/?callback=yqlgeo.ipin');
  };

  function ipin(o){
    getFromIP(o.ip);
  };

  function getFromLatLon(lat,lon){
    var yql = 'select * from geo.places where woeid in ('+
              'select place.woeid from flickr.places where lat='+
              lat + ' and  lon=' + lon + ')';
    load(yql,'yqlgeo.retrieved');
  };

  function getFromURL(url){
    var yql = 'select * from geo.places where woeid in ('+
              'select match.place.woeId from geo.placemaker where '+
              'documentURL="' + url + '" and '+
              'documentType="text/html" and appid="")';
    load(yql,'yqlgeo.retrieved');
  }

  function getFromText(text){
    var yql = 'select * from geo.places where woeid in ('+
              'select match.place.woeId from geo.placemaker where'+
              ' documentContent = "' + text + '" and '+
              'documentType="text/plain" and appid = "")';
    load(yql,'yqlgeo.retrieved');
  };

  function jsonp(src){
    if(document.getElementById('yqlgeodata')){
      var old = document.getElementById('yqlgeodata');
      old.parentNode.removeChild(old);
    }
    var head = document.getElementsByTagName('head')[0];
    var s = document.createElement('script');
    s.setAttribute('id','yqlgeodata');
    s.setAttribute('src',src);
    head.appendChild(s);
  };

  function load(yql,cb){
    if(document.getElementById('yqlgeodata')){
      var old = document.getElementById('yqlgeodata');
      old.parentNode.removeChild(old);
    }
    var src = 'http://query.yahooapis.com/v1/public/yql?q='+
              encodeURIComponent(yql) + '&format=json&callback=' + cb + '&'+
              'env=store%3A%2F%2Fdatatables.org%2Falltableswithkeys';
    var head = document.getElementsByTagName('head')[0];
    var s = document.createElement('script');
    s.setAttribute('id','yqlgeodata');
    s.setAttribute('src',src);
    head.appendChild(s);
  };

  function retrieved(o){
    if(o.query.results){
      callback(o.query.results);
    } else {
      callback({error:o.query});
    }
  };
  return {
    get:get,
    retrieved:retrieved,
    ipin:ipin
  };
}();