<div id="manage_content_container">

	<div class="manage_content_search">	
		<h3>Search <input type="text" class="search input_medium" placeholder="Type Title of Content"></h3>
	</div>

	<div class="manage_content_filter">	
		<h3>Filter
		<?= form_dropdown('user_level_id', config_item('users_levels'), 1) ?>
		<select name="filter_active" id="filter_active">
			<option value="1">Active</option>
			<option value="0">Inactive</option>
		</select>
	    <select name="sort_list" id="sort_list">
	        <option value="">Order By</option>
	        <option value="name">Name</option>
	        <option value="item_email">Email</option>
	        <option value="item_user_level_id">User Level</option>
	        <option value="item_active">Active</option>
	    </select>
		</h3>	    
	</div>
	<div class="clear"></div>
	<div class="manage_separator"></div>

	<ol id="manage_list" class="list">
		<?= $users_view ?>
	</ol>
	<div class="clear"></div>

</div>

<script src="<?= base_url() ?>js/list.min.js"></script>
<script type="text/javascript">
$(document).ready(function()
{
	// Create List & Params
    var options = {
	    valueNames: ['name', 'item_email', 'item_user_level_id', 'item_active']
    };

    var featureList = new List('manage_content_container', options);

	// Sort By active
	$('#filter_active').change(function()
	{			
		var active_id = $(this).val().toString();        
		if (active_id == 'none') {
	        featureList.filter();
	    }
	    else {
	        featureList.filter(function(item) {        
	            if (item.values().item_active == active_id) {
	                return true;
	            }
	            else {
	                return false;
	            }
	        });
	    }
        return false;		
  	});

  	$('#sort_list').change(function()
  	{
	  	var sort_by = $(this).val();
	  		  	
	  	featureList.sort(sort_by, { asc: true });	
  	});

});
</script>