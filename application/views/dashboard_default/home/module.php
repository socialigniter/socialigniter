<div id="manage_content_container">

	<div class="manage_content_search">	
		<h3>Search</h3>
		<input type="text" class="search input_medium" placeholder="Type Title of Content">
	</div>

	<div class="manage_content_filter">	
		<h3>Filter By</h3>
		<p>
		<?= form_dropdown('filter_categories', $filter_categories, '', 'id="filter_category"') ?>	
		<?= form_dropdown('filter_users', $filter_users, '', 'id="filter_user"') ?>
		<?= form_dropdown('filter_details', $filter_details, '', 'id="filter_details"') ?>
		</p>
	</div>

	<div class="manage_content_order">	
	    <h3>Order By </h3>
	    <select name="sort_list" id="sort_list">
	        <option value="">---select---</option>
	        <option value="name">Name</option>
	        <option value="item_category">Category</option>
	        <option value="item_user_id">Users</option>    
	        <option value="item_details">Details</option>
	    </select>	    
	</div>

	<ol id="feed" class="list">
		<?= $timeline_view ?>
	</ol>
	<div class="clear"></div>

</div>

<style type="text/css">
#manage_content_container h3 {
	margin: 0px 0px 15px 0px;
}

span.item_category, span.item_user_id, span.item_details {
	display: none;
}
div.manage_content_search {
	width: 275px;
	float: left;
}
div.manage_content_filter {
	width: 445px; 
	float: left;
	margin-left: 25px;
}
div.manage_content_filter select {
	margin: 0 15px 0 0;
}
div.manage_content_order {
	width: 125px; 
	float: left;
}

</style>
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
	$('#filter_category').change(function()
	{			
		var category_id = $(this).val().toString();        
		if (category_id == 'none')
		{
	        featureList.filter();
	    }
	    else
	    {
	        featureList.filter(function(item)
	        {        
	            if (item.values().item_category == category_id)
	            {
	                return true;
	            } else {
	                return false;
	            }
	        });
	    }
        return false;		
  	});

  	// Sort By User
	$('#filter_user').change(function()
	{	
		var user_id = $(this).val();
		if (user_id == 'none')
		{
	        featureList.filter();
	    }
	    else
	    {
	        featureList.filter(function(item)
	        {
	            if (item.values().item_user_id == user_id.toString())
	            {
	                return true;
	            } else {
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
 		if (details == 'none')
		{
	        featureList.filter();
	    }
	    else
	    {
	        featureList.filter(function(item)
	        {
	            if (item.values().item_details == details)
	            {
	                return true;
	            } else {
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



