<div id="manage_content_container">

	<div class="manage_content_search">	
		<h3>Search <input type="text" class="search input_medium" placeholder="Type Title of Content"></h3>
	</div>

	<div class="manage_content_filter">	
		<h3>Filter
		<select name="filter_category" id="filter_category">
			<option value="none">By Category</option>
			<?php foreach ($all_categories as $category): if (in_array($category->category_id, $filter_categories)): ?>
			<option value="<?= $category->category_id ?>"><?= $category->category ?></option>
			<?php endif; endforeach; ?>
		</select>
		<?= form_dropdown('filter_users', $filter_users, '', 'id="filter_user"') ?>
		<?= form_dropdown('filter_details', $filter_details, '', 'id="filter_details"') ?>
	    <select name="sort_list" id="sort_list">
	        <option value="">Order By</option>
	        <option value="name">Name</option>
	        <option value="item_category">Category</option>
	        <option value="item_user_id">Users</option>    
	        <option value="item_details">Details</option>
	    </select>
		</h3>	    
	</div>
	<div class="clear"></div>
	<div class="manage_separator"></div>

	<ol id="manage_list" class="list">
		<?= $timeline_view ?>
	</ol>
	<div class="clear"></div>

</div>

<script src="<?= base_url() ?>js/list.min.js"></script>
<script type="text/javascript">
$(document).ready(function()
{
	// Create List & Params
    var options = {
	    valueNames: ['name', 'item_category', 'item_user_id', 'item_details']
    };

    var featureList = new List('manage_content_container', options);

	// Sort By Category
	$('#filter_category').change(function() {			
		var category_id = $(this).val().toString();        
		if (category_id == 'none') {
	        featureList.filter();
	    }
	    else {
	        featureList.filter(function(item) {        
	            if (item.values().item_category == category_id) {
	                return true;
	            }
	            else {
	                return false;
	            }
	        });
	    }
        return false;		
  	});

  	// Sort By User
	$('#filter_user').change(function() {	
		var user_id = $(this).val();
		if (user_id == 'none') {
	        featureList.filter();
	    }
	    else {
	        featureList.filter(function(item) {
	            if (item.values().item_user_id == user_id.toString()) {
	                return true;
	            }
	            else {
	                return false;
	            }
	        });
	    }
        return false;		
  	});

  	// Sort By User
	$('#filter_details').change(function()
	{	
		var details = $(this).val();
 		if (details == 'none') {
	        featureList.filter();
	    }
	    else {
	        featureList.filter(function(item) {
	            if (item.values().item_details == details) {
	                return true;
	            }
	            else {
	                return false;
	            }
	        });
	    }
        return false;		
  	});

  	$('#sort_list').change(function() {
	  	var sort_by = $(this).val();
	  		  	
	  	featureList.sort(sort_by, { asc: true });	
  	});

});
</script>



