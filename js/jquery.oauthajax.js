// jQuery Plugin for making OAuth API calls
(function($)
{
	$.fn.oauthAjax = function(settings)
	{
		var oauth_consumer_key 		= settings.oauth.consumer_key;
		var oauth_consumer_secret 	= settings.oauth.consumer_secret;
		var oauth_token				= settings.oauth.token;
		var oauth_token_secret 		= settings.oauth.token_secret;		

		var accessor = { 
			consumerSecret	: oauth_consumer_secret,
			tokenSecret		: oauth_token_secret,
		};	
		
		var parameters = [
			["oauth_consumer_key", oauth_consumer_key],
			["oauth_token", oauth_token]
		];
		
		if (settings.data)
		{
			for (var i = 0; i < settings.data.length; i++)
			{
				parameters.push([settings.data[i].name, settings.data[i].value]);
			}
		}
				
		var message = {
			method: settings.type || "GET",
			action: settings.url,
			parameters: parameters
		}
		
		OAuth.setTimestampAndNonce(message);
		OAuth.SignatureMethod.sign(message, accessor);
		
		var oldBeforeSend = settings.beforeSend;
		settings.beforeSend = function(xhr) {
			xhr.setRequestHeader("Authorization", OAuth.getAuthorizationHeader("", message.parameters))
			if (oldBeforeSend) oldBeforeSend(xhr);
		};
	
		jQuery.ajax(settings);
	};
})(jQuery);
