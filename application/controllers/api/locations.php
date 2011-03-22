<?php defined('BASEPATH') OR exit('No direct script access allowed');
/* 
 * Locations API : Core : Social-Igniter
 *
 */
class Locations extends Oauth_Controller
{
    function __construct() 
    {
        parent::__construct();
                
		$this->load->model('locations_model');
    }


    function index_get()
    {
    
        $locations = $this->locations_model->get_locations();
        
        if($locations)
        {
            $message 	= array('status' => 'success', 'data' =>$locations)
            $response	= 200;
        }
        else
        {
            $message	= array('error' => 'Could not find any locations');
            $response	= 404;
        }
        
        $this->response($message, $response);
    }

	function view_get()
    {
        if(!$this->get('id'))
        {
            $message 	= array('status' => 'error', 'message' => 'Please specify an id in the url');
            $response	= 404;
        }

        $user = $this->locations_model->get_location('location_id', $this->get('id'));
		    	
        if($user)
        {
            $message = array('status' => 'success', 'data' => $user);
        	$response	= 200;
        }
        else
        {
            $message 	= array('error' => 'User could not be found');
            $response	= 404;
        }

        $this->response($message, $response);
    }

    
    function update_post()
    {
    
        $message 	= array('id' => $this->get('id'), 'name' => $this->post('name'), 'email' => $this->post('email'), 'message' => 'ADDED!');
        $response	= 200;
        
        $this->response($message, $response);
    }
    
    function destroy_delete()
    {
        $message 	= array('id' => $this->get('id'), 'message' => 'DELETED!');
        $response	= 200;
        
        $this->response($message, $response);
    }
    

}