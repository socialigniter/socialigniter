<form name="place_editor" id="place_editor" action="<?= $form_url ?>" method="post" enctype="multipart/form-data">

	<div id="content_wide_content">
		<h3>Title</h3>
		<input type="text" name="title" id="title" class="input_full" value="<?= $title ?>">
		<p id="title_slug" class="slug_url"></p>
		
		<div id="place_address">
			<h3>Address</h3>
			<p><input type="text" name="address" class="input_bigger" value="<?= $address ?>"></p>
			<p><input type="text" name="district" class="input_bigger" value="<?= $district ?>"></p>
			<p>
				<input type="text" name="locality" class="input_small" value="<?= $locality ?>">
				<input type="text" name="region" class="input_mini" value="<?= $region ?>">
				<input type="text" name="postal" class="input_small" value="<?= $postal ?>">
			</p>
			<p><?= country_dropdown($country, config_item('countries')) ?></p>
		</div>

		<div id="place_map">
			<h3>Map</h3>
			<div id="place_map_map" class="map">
			
			</div>
		</div>
		
		<div class="clear"></div>
	
		<p><input type="button" id="add_details" value="Add Details"></p>
		<div id="place_details" style="display:none">

			<h3>Description</h3>
			<p><textarea name="content" id="place_content" rows="4" cols="100"></textarea></p>
	
		    <h3>Category</h3>
		    <p><?= form_dropdown('category_id', $categories, $category_id) ?></p>
		    
		    <h3>Tags</h3>
		    <p><input name="tags" type="text" id="tags" size="75" placeholder="Blogging, Internet, Web Design" /></p>
	
			<h3>Access</h3>
			<p><?= form_dropdown('access', config_item('access'), $access) ?></p>
		
			<h3>Comments</h3>
			<p><?= form_dropdown('comments_allow', config_item('comments_allow'), $comments_allow) ?></p>
		
		</div>
		
		<input type="hidden" name="details" id="layout" value="<?= $details ?>">
		<input type="hidden" name="geo_lat" id="geo_lat" value="" />
		<input type="hidden" name="geo_long" id="geo_long" value="" />
		<input type="hidden" name="geo_accuracy" id="geo_accuracy" value="" />

	</div>
	
	<div id="content_wide_toolbar">
		<?= $content_publisher ?>
	</div>	
	
</form>
<div class="clear"></div>

<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<script type="text/javascript">
$(document).ready(function()
{
	// Placeholders
	doPlaceholder('#title', 'Joes Oyster Shack');
	doPlaceholder('[name=address]', '15229 Ocean St.');
	doPlaceholder('[name=district]', 'Waterfront');
	doPlaceholder('[name=locality]', 'Los Angeles');
	doPlaceholder('[name=region]', 'CA');
	doPlaceholder('[name=country]', 'USA');
	doPlaceholder('[name=postal]', '91405');
	doPlaceholder('[name=content]', 'Joe is a good man but he makes even better oysters...');
	doPlaceholder('#tags', 'Oysters, Lobster, Seafood');

	// Slugify Title
	$('#title').slugify({url:base_url + 'places/', slug:'#title_slug', name:'title_url', slugValue:'<?= $title_url ?>'});

	// Make Map Title
	// Currently using Portland, should use lookup
  /*
    var lat		= 45.52342768;
    var long 	= -122.67522811;
    var coords 	= new google.maps.LatLng(lat, long);

	getMap(coords);
	
	function getMap(lat_long)
	{
		var myOptions = 
		{     
			disableDefaultUI 	: true,
			zoom 				: 13,
			center 				: lat_long,
			mapTypeId 			: google.maps.MapTypeId.ROADMAP
		};
		
		
		var map = new google.maps.Map(document.getElementById("place_map_map"), myOptions);
	}	
	*/
	var geocoder;
	var map;
	
	function initialize()
	{
		geocoder = new google.maps.Geocoder();
		var latlng = new google.maps.LatLng(-34.397, 150.644);
		var myOptions =
		{
	  		zoom: 13,
	  		center: latlng,
	  		mapTypeId: google.maps.MapTypeId.ROADMAP
		}
	
		map = new google.maps.Map(document.getElementById("place_map_map"), myOptions);
	}	
	
	function getMapGeocode(address)
	{
		//var coder = new GClientGeocoder();
		//coder.getLatLng('2562 26th Ave, San Francisco, CA 94116', function(gpoint)
//		var address = '2562 26th Ave, San Francisco, CA 94116'; //document.getElementById('input_name').value;
		
		geocoder.geocode({'address' : address}, function(results, status)
		{
			if (status == google.maps.GeocoderStatus.OK)
			{
		    	map.setCenter(results[0].geometry.location);
		    	var marker = new google.maps.Marker(
		   		{
		        	map: map, 
		        	position: results[0].geometry.location
		    	});	    	
			}
			else
			{
				alert("Geocode was not successful for the following reason: " + status);
			}
		});	  
	}
	
	initialize();
	getMapGeocode('2562 26th Ave, San Francisco, CA 94116');
		
	$('[name=postal]').focusout(function(e)
	{
		if ($("[name=postal]").val().length > 0 && $("[name=locality]").val().length > 0 && $("[name=region]").val().length > 0 && $("[name=address]").val().length > 0) 
		{
			var address = $('[name=address]').val() + " " + $('[name=locality]').val() + ", " + $('[name=region]').val() + " " + $('[name=postal]').val();
			
			getMapGeocode(address);
		}
	});

/*	
	var canGo = false;
	
	$(".map").gMap(
	{
		address:"2562 26th Ave, San Francisco, CA 94116", 
		zoom: 16
	});
	
	$(".mapit").click(function(e)
	{
		var address = $('#street').val() + " " + $('#city').val() + ", " + $('#state').val() + " " + $('#postal').val();
		
		$(".map").gMap(
		{
			address	: address,
			zoom 	: 16,
			markers : [{ address: address }]
		});

		var coder = new GClientGeocoder();
		
		coder.getLatLng(address, function(gpoint)
		{ 
			console.log("long: "+gpoint.x+"\nLat: "+gpoint.y);
			
			$("#long").val(gpoint.x);
			$("#lat").val(gpoint.y);
		});
	
		canGo = true;
		$(".disabled1").removeClass('disabled1');
		
		return false;
	});
*/
	
	// Add Details
	$('#add_details').live('click', function(eve)
	{
		eve.preventDefault();
		$(this).hide();
		$('#place_details').show('normal');
		
	});
		
	
	// Create / Modify Place 
	$("#place_editor").bind("submit", function(eve)
	{
		eve.preventDefault();
		var valid_title	= isFieldValid('#title', "Joes Oyster Shack", 'You need a title');

		// Validation	
		if (valid_title == true)
		{	
			var page_data = $('#place_editor').serializeArray();
			page_data.push({'name':'module','value':'places'},{'name':'type','value':'place'},{'name':'source','value':'website'});

			console.log(page_data);

			$(this).oauthAjax(
			{
				oauth 		: user_data,
				url			: $(this).attr('ACTION'),
				type		: 'POST',
				dataType	: 'json',
				data		: page_data,
		  		success		: function(result)
		  		{	
		  			console.log(result);
		  				  			  			
					if (result.status == 'success')
					{
				 		$('#content_message').notify({message: result.message + ' <a href="' + base_url + 'pages/view/' + result.data.content_id + '">' + result.data.title + '</a>'});
				 		var status = displayContentStatus(result.data.status);				 		
				 		$('#article_status').html(status);				 	
				 	}
				 	else
				 	{
					 	$('#content_message').html(result.message).addClass('message_alert').show('normal');
					 	$('#content_message').oneTime(3000, function(){$('#content_message').hide('fast')});			
				 	}	
			 	}
			});
		}
		else
		{
			eve.preventDefault();
		}		
	});

	// Add Category
	$('[name=category_id]').change(function()
	{	
		if($(this).val() == 'add_category')
		{
			$('[name=category_id]').find('option:first').attr('selected','selected');
			$.uniform.update('[name=category_id]');

			$.categoryEditor(
			{
				url_api		: base_url + 'api/categories/view/module/places',
				url_pre		: base_url + 'places/',
				url_sub		: base_url + 'api/categories/create',				
				module		: 'places',
				type		: 'places',
				title		: 'Add Place Category',
				slug_value	: '',
				trigger		: $('.content [name=category_id]')
			});			
		}
	});		
	
});
</script>