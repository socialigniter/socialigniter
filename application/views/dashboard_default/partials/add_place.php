<form name="create_place" method="post" id="create_place" enctype="multipart/form-data">
	
	<div id="place_address">
		<h3>Title</h3>
		<p><input type="text" name="title" id="title" class="input_bigger" value="<?= $title ?>"></p>	
		<h3>Address</h3>
		<p><input type="text" name="address" id="address" class="input_bigger" value="<?= $address ?>"></p>
		<p><input type="text" name="district" id="district" class="input_bigger" value="<?= $district ?>"></p>
		<p>
			<input type="text" name="locality" id="locality" class="input_small" value="<?= $locality ?>">
			<input type="text" name="region" id="region" class="input_mini" value="<?= $region ?>">
			<input type="text" name="postal" id="postal" class="input_small" value="<?= $postal ?>">
		</p>
		<div id="place_country"><?php // country_dropdown('country', config_item('countries'), $country) ?></div>
		<a href="#" id="place_map_it">Map It</a>
		<div class="clear"></div>
	</div>

	<div id="place_map">
		<h3>Map</h3>
		<div id="place_map_map" class="map"></div>
	</div>

	<div class="clear"></div>

	<input type="hidden" name="geo_lat" id="geo_lat" value="<?= $geo_lat ?>" />
	<input type="hidden" name="geo_long" id="geo_long" value="<?= $geo_long ?>" />	

</form>