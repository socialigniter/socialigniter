<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

require_once(APPPATH.'libraries/Oauth/OauthRequestVerifier.php');

class Oauth
{
    private $ci;
    
    function __construct($config = array())
    {
        $this->ci =& get_instance();
        
  		// Pulls In DB settings from SI
        $database = array(
        	'server'	=> $this->ci->db->hostname, 
        	'username'	=> $this->ci->db->username, 
        	'password'	=> $this->ci->db->password, 
        	'database'	=> $this->ci->db->database
        );
        
        OAuthStore::instance('MySQL', $database);
 
        log_message('debug', 'oauthcrap: inside construct of Oauth lib');
    }
    
    function request_is_signed()
    {
    	try
    	{
        	return OAuthRequestVerifier::requestIsSigned();
    	}
    	catch (OAuthException2 $e)
    	{
    		return FALSE;
    	}
    }
    
    function get_oauth_user_id()
    {
        try
        {
            $verifier = new OAuthRequestVerifier();
            return $verifier->verify();
        }
        catch (OAuthException2 $e)
        {
        	log_message('debug', 'Exception while verifying request: '.$e->getMessage());
            return FALSE;
        }
    }
    
    // creates a consumer for the user. if creating, returns the consumer
    // with additional keys: consumer_key, consumer_secret.
    // possible keys for consumer are: requester_name, requester_email,
    // callback_uri, application_uri, application_title, application_descr
    // application_notes, application_commercial 
    function create_or_update_consumer($consumer, $user_id)
    {
        $store = OAuthStore::instance();
        $key = $store->updateConsumer($consumer, $user_id);
        $consumer = $store->getConsumer($key, $user_id);
        return $consumer;
    }
    
    // returns an array with keys 'token' and 'token_secret' which can be used
    // to sign requests.
    function grant_access_token_to_consumer($consumer_key, $user_id)
    {
    	$store = OAuthStore::instance();
        $token_info = $store->addConsumerRequestToken($consumer_key);
        $store->authorizeConsumerRequestToken($token_info['token'], $user_id);
        $token_and_secret = $store->exchangeConsumerRequestForAccessToken($token_info['token']);
        return $token_and_secret;
    }
}
?>