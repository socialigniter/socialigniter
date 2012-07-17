<?php
	
include('Oauth2/OAuth2_Exception.php');
include('Oauth2/OAuth2_Token.php');
include('Oauth2/OAuth2_Provider.php');

/**
 * OAuth2.0
 *
 * @author Phil Sturgeon < @philsturgeon >
 */
class OAuth2 {
	
	/**
	 * Create a new provider.
	 *
	 *     // Load the Twitter provider
	 *     $provider = $this->oauth2->provider('twitter');
	 *
	 * @param   string   provider name
	 * @param   array    provider options
	 * @return  OAuth_Provider
	 */
	public static function provider($name, array $options = NULL)
	{
		//include_once 'providers/'.strtolower($name).'.php';
		include_once(APPPATH.'modules/'.strtolower($name).'/libraries/oauth2_provider.php');
		
		$class = 'OAuth2_Provider_'.ucfirst($name);

		return new $class($options);
	}
	
}