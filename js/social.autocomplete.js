/**
 * Turns on auto suggestions for any input with the name="tags" set
 * Simply call autocomplete();
 * @requires jQuery
 * @requires jQuery UI
 * @requires jQuery UI Autocomplete module and all of it's dependcies
 */
var autocomplete = function(trigger_element, api_data)
{
  	$.get(base_url + api_data,function(json)
  	{
		var data = json.data;
		var tags = [];
		for(x in data)
		{
			tags[x] = data[x].tag;
		}
		suggestions(tags);
	});
		
	var suggestions = function(availableTags)
	{
		function split(val)
		{
			return val.split( /,\s*/ );
		}
		
		function extractLast( term )
		{
			return split( term ).pop();
		}
		
		$(trigger_element)
			// don't navigate away from the field on tab when selecting an item
			.bind( "keydown", function( event ) {
				if (event.keyCode === $.ui.keyCode.TAB && $(this).data("autocomplete").menu.active)
				{
					event.preventDefault();
				}
			})
			.autocomplete(
			{
				minLength: 0,
				source: function(request, response)
				{
					// delegate back to autocomplete, but extract the last term
					response($.ui.autocomplete.filter(availableTags, extractLast(request.term)));
				},
				focus: function()
				{
					// prevent value inserted on focus
					return false;
				},
				select: function(event, ui)
				{
					var terms = split(this.value);
					
					// remove the current input
					terms.pop();
					
					// add the selected item
					terms.push(ui.item.value);
					
					// add placeholder to get the comma-and-space at the end
					terms.push("");
					this.value = terms.join(", ");
					return false;
				}
		});
	} 
}