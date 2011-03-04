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
    
    function create_authd_post()
    {
        if($tags = $this->social_tools->process_tags($this->input->post('tags'), $this->input->post('content_id')))
        {
            $message = array('status' => 'success', 'message' => 'Created your tags', 'data' => $tags);
        }
        else
        {
            $message = array('status' => 'error', 'message' => 'Could not create tags');
        }
        
        $this->response($message, 200);    
    }
        
}