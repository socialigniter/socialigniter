<form name="<?= $form_name ?>" id="<?= $form_name ?>" action="<?= $form_url ?>" method="post" enctype="multipart/form-data">

	<div id="content_wide_content">
		<h3>Title</h3>
		<input type="text" name="title" id="title" class="input_full" value="<?= $title ?>">
		<p id="title_slug" class="slug_url"></p>
		
		<div id="place_address">
			<h3>Address</h3>
			<p><input type="text" name="address" id="address" class="input_bigger" value="<?= $address ?>"></p>
			<p><input type="text" name="district" id="district" class="input_bigger" value="<?= $district ?>"></p>
			<p>
				<input type="text" name="locality" id="locality" class="input_small" value="<?= $locality ?>">
				<input type="text" name="region" id="region" class="input_mini" value="<?= $region ?>">
				<input type="text" name="postal" id="postal" class="input_small" value="<?= $postal ?>">
			</p>
			<div id="place_country"><?= country_dropdown('country', config_item('countries'), $country) ?></div>
			<a href="#" id="place_map_it">Map It</a>
			<div class="clear"></div>
		</div>

		<div id="place_map">
			<h3>Map</h3>
			<div id="place_map_map" class="map"></div>
		</div>
		
		<div class="clear"></div>
	
		<p><input type="button" id="add_details" value="Add More Details"></p>
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
		
		<input type="hidden" name="geo_lat" id="geo_lat" value="<?= $geo_lat ?>" />
		<input type="hidden" name="geo_long" id="geo_long" value="<?= $geo_long ?>" />

	</div>
	
	<div id="content_wide_toolbar">
		<?= $content_publisher ?>
	</div>	
	
</form>
<div class="clear"></div>

<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<script type="text/javascript">

// Elements for Placeholder
var validation_rules = [{
	'element' 	: '#title', 
	'holder'	: 'Joes Oyster Shack', 
	'message'	: 'You need a place title'
},{
	'element' 	: '#address', 
	'holder'	: '15229 Some St.', 
	'message'	: 'You need an address'
},{
	'element' 	: '#district', 
	'holder'	: 'Waterfront', 
	'message'	: ''	
},{
	'element' 	: '#locality', 
	'holder'	: 'Someville', 
	'message'	: 'You need a city'	
},{
	'element' 	: '#region', 
	'holder'	: 'DC', 
	'message'	: ''	
},{
	'element' 	: '#postal', 
	'holder'	: '90000', 
	'message'	: ''	
},{
	'element' 	: '#place_content', 
	'holder'	: 'Joe is a good man but he makes even better oysters...', 
	'message'	: ''	
},{
	'element' 	: '#tags', 
	'holder'	: 'Oysters, Lobster, Seafood', 
	'message'	: ''	
}]

$(document).ready(function()
{
	// Placeholders
	makePlaceholders(validation_rules);

	// Slugify Title
	$('#title').slugify({url:base_url + 'places/', slug:'#title_slug', name:'title_url', slugValue:'<?= $title_url ?>'});

	// Autocomplete Tags
	autocomplete("[name=tags]", 'api/tags/all');

	// Initialize Map
    var initial_place = new google.maps.LatLng(<?= $geo_lat ?>, <?= $geo_long ?>);	
	getMap(initial_place, 'place_map_map');
	
	// On Completing Address
	$('[name=postal], [name=region], [name=locality]').live('blur', function(eve)
	{
		if ($("[name=postal]").val().length > 0 && $("[name=locality]").val().length > 0 && $("[name=region]").val().length > 0 && $("[name=address]").val().length > 0) 
		{
			var address = $('[name=address]').val() + " " + $('[name=locality]').val() + ", " + $('[name=region]').val() + " " + $('[name=postal]').val();
			getMapGeocode(address, '#geo_lat', '#geo_long');
		}
	});

	// Click Map It
	$('#place_map_it').live('click', function(eve)
	{
		eve.preventDefault();
		var address = $('[name=address]').val() + " " + $('[name=locality]').val() + ", " + $('[name=region]').val() + " " + $('[name=postal]').val();	
		getMapGeocode(address, '#geo_lat', '#geo_long');
	
	});

	// Add Details
	$('#add_details').live('click', function(eve)
	{
		eve.preventDefault();
		$(this).hide();
		$('#place_details').show('slow');
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