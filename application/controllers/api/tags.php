<?php defined('BASEPATH') OR exit('No direct script access allowed');
/* 
 * Tags API : Core : Social-Igniter
 *
 */
class Tags extends Oauth_Controller
{
    function __construct()
    {
        parent::__construct();      
	}
	
    function all_get()
    {   
        if($tags = $this->social_tools->get_tags())
        {
            $message = array('status' => 'success', 'message' => 'Found some tags', 'data' => $tags);
        }
        else
        {
            $message = array('status' => 'error', 'message' => 'Could not find any tags');
        }
        
        $this->response($message, 200);        
    }
}