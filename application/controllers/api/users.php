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
    
    function recent_get()
    {
    	//$this->get('limit')
        $users = $this->social_auth->get_users(10);
        
        if($users)
        {
            $this->response($users, 200); // 200 being the HTTP response code
        }

        else
        {
            $this->response(array('error' => 'Couldn\'t find any users!'), 404);
        }
    }

	function view_get()
    {
        if(!$this->get('user_id'))
        {
        	$this->response(NULL, 400);
        }

        $user = $this->social_auth->get_user($this->get('id'));
    	
        if($user)
        {
            $this->response($user, 200); // 200 being the HTTP response code
        }
        else
        {
            $this->response(array('error' => 'User could not be found'), 404);
        }
    }

    function create_post()
    {
        // Validation Rules
    	$this->form_validation->set_rules('name', 'Name', 'required');
    	$this->form_validation->set_rules('email', 'Email Address', 'required|valid_email');
    	$this->form_validation->set_rules('password', 'Password', 'required|min_length['.config_item('min_password_length').']|max_length['.$this->config->item('max_password_length').']|strong_pass['.config_item('password_strength').']|matches[password_confirm]');
    	$this->form_validation->set_rules('password_confirm', 'Password Confirmation', 'required');
    	$this->form_validation->set_rules('phone', 'Phone', 'required|valid_phone_number');

		// Validates
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
	    	        	
	    	// Register User
	    	if ($this->social_auth->register($username, $password, $email, $additional_data, config_item('default_group')))
	    	{
		        $message	= array('message' => 'Created!');
		        $response	= 200;
	   		}
	   		else
	   		{
		        $message	= array('message' => 'Could Not Create!');
		        $response	= 400;
	   		}     
        
        } 
		else
		{ 
		        $message	= array('message' => 'Missing: '.validation_errors());
		        $response	= 400;        
        }
        
        $this->response($message, $response); // 200 being the HTTP response code
    }
    
    function update_post()
    {
    	$update_data = array(
    		'username'	=> url_username($this->input->post('name'), 'none', true),
        	'name'		=> $this->input->post('name'),
        	'bio'		=> $this->input->post('bio')
    	);
    	
    	// Update the user
    	if ($this->social_auth->update_user($this->get('id'), $update_data))
    	{
	        $message	= array('message' => 'Updated!');
	        $response	= 200;
   		}
   		else
   		{
	        $message	= array('message' => 'Could Not Update!');
	        $response	= 400;
   		}        
        
        $this->response($message, $response); // 200 being the HTTP response code
    }
    
    function destroy_delete()
    {
    	//$this->some_model->deletesomething( $this->get('id') );
        $message = array('id' => $this->get('id'), 'message' => 'DELETED!');
        
        $this->response($message, 200); // 200 being the HTTP response code
    }

}