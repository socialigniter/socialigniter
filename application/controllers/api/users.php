<?php defined('BASEPATH') OR exit('No direct script access allowed');
/* 
* Social-Igniter : Core : Users : API Controller
*
* @package		Social Igniter
* @subpackage	Social Igniter Library
* @author		Brennan Novak
* @link			http://social-igniter.com
* 
*/
class Users extends Oauth_Controller
{
    function __construct()
    {
        parent::__construct();      
	}
    
    function recent_get()
    {
        $users = $this->social_auth->get_users(10);
        
        if($users)
        {
            $message = array('status' => 'success', 'message' => '1 - 10 recent users', 'data' => $users);
        }
        else
        {
            $message = array('status' => 'error', 'message' => 'Oops could not find any users');
        }
        
        $this->response($message, 200);        
    }

	function view_get()
    {
        if(!$this->get('user_id'))
        {
        	$message = array('status' => 'error', 'message' => 'You must specific a user_id in the url');
        }

        $user = $this->social_auth->get_user($this->get('id'));
    	
        if($user)
        {
            $mesage = array('status' => 'success', 'message' => 'User found', 'data' => $user);
        }
        else
        {
            $message = array('status' => 'error', 'message' => 'User could not be found');
        }

        $this->response($message, 200);
    }

    function create_post()
    {
    	$this->form_validation->set_rules('name', 'Name', 'required');
    	$this->form_validation->set_rules('email', 'Email Address', 'required|valid_email');
    	$this->form_validation->set_rules('password', 'Password', 'required|min_length['.config_item('min_password_length').']|max_length['.$this->config->item('max_password_length').']|strong_pass['.config_item('password_strength').']|matches[password_confirm]');
    	$this->form_validation->set_rules('password_confirm', 'Password Confirmation', 'required');
    	$this->form_validation->set_rules('phone', 'Phone', 'required|valid_phone_number');

        if ($this->form_validation->run() == true)
        {
            
			$username			= url_username($this->input->post('name'), 'none', true);
	    	$email				= $this->input->post('email');
	    	$password			= $this->input->post('password');
	    	$additional_data 	= array(
	    		'name'			=> $this->input->post('name'),
	    		'phone'			=> preg_replace("/[^0-9]*/", "", $this->input->post('phone')),
	    		'phone_verify'	=> random_element(config_item('mobile_verify')),
	        	'location'		=> $this->input->post('location'),
	        	'license_plate'	=> $this->input->post('license_plate'),
	    		'is_car'		=> 1,
	    		'is_driveway'	=> 0,
	    		'user_state'	=> 'needs-verify'
	    	);
	    	        	
	    	if ($this->social_auth->register($username, $password, $email, $additional_data, config_item('default_group')))
	    	{
		        $message = array('status' => 'success', 'message' => 'User created');
	   		}
	   		else
	   		{
		        $message = array('status' => 'error', 'message' => 'Oops could not create that user');
	   		}     
        } 
		else
		{ 
		        $message = array('message' => 'Oops you are missing '.validation_errors());
        }
        
        $this->response($message, 200);
    }

	// Update User    
    function update_authd_post()
    {
    	$update_data = array(
    		'username'	=> url_username($this->input->post('name'), 'none', true),
        	'name'		=> $this->input->post('name'),
        	'bio'		=> $this->input->post('bio')
    	);
    	
    	if ($this->social_auth->update_user($this->get('id'), $update_data))
    	{
	        $message = array('status' => 'success', 'message' => 'User updated');
   		}
   		else
   		{
	        $message = array('status' => 'error', 'message' => 'Could not update user');
   		}        
        
        $this->response($message, 200);
    }
    
	// Activate User
	function activate_authd_put() 
	{        
		$activation = $this->social_auth->activate($this->get('id'), $this->get('code'));
		
        if ($activation)
        {
	        $message = array('status' => 'success', 'message' => 'User activated');
        }
        else
        {
	        $message = array('status' => 'error', 'message' => 'User could not be activated');
        }
        
        $this->response($message, 200);        
    }
    
    // Deactivate User
	function deactivate_authd_delete($id) 
	{
	    $this->social_auth->deactivate($id);

        $this->response($message, $response);
    }    
    
    function destroy_delete()
    {
    	// $this->some_model->deletesomething($this->get('id'));
        $message = array('status' => 'success', 'message' => 'User was deleted');
        
        $this->response($message, 200);
    }

}