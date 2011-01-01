(function($)
{
	$.mediaManager = function(options)
	{
		var settings = {
			parameter 	: '',
			value 		: '',
			after 		: function(){}
		};
		
		options = $.extend({},settings,options);

	/* Media Manager */
	$(".media_manager").fancybox({
		'autoDimensions'		: false,
		'width'					: 500,
		'height'				: 600,
		'padding'				: 20,
		'scrolling'				: 'auto',
		'transitionIn'			: 'none',
		'transitionOut'			: 'none',
		'hideOnOverlayClick'	: true,
		'overlayColor'			: '#000000',
		'overlayOpacity'		: 0.7,
		'type'					: 'iframe'
	});	
	
	
	embed_video = function(vidURL)
	{
		var embed = '<object width="500" height="375"><param name="allowfullscreen" value="true"><param name="wmode" value="opaque"><param name="allowscriptaccess" value="always"><param name="movie" value="' + vidURL + '"><embed src="' + vidURL + '" type="application/x-shockwave-flash" allowfullscreen="true" allowscriptaccess="always" height="375" width="500" wmode="opaque"></object>';
		return embed;
	};
		
	$('.insert_image').live('click', function()
	{
		var insertedImageUrl = $(this).attr('rel');		
			
		parent.$().wysiwyg('insertImage', insertedImageUrl);
		parent.$.fancybox.close();
	});	
	
	$('.insert_video').live('click', function()
	{
		var insertVideoUrl = $(this).attr('rel');
		var embedVideo = embed_video(insertVideoUrl);
		
		parent.$().wysiwyg('insertHtml', embedVideo);
		parent.$.fancybox.close();
	});
	
	};
})( jQuery );