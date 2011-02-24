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
	    		'phone_verify'	=> random_element(config_item('mobile_verify'))
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
    function modify_authd_post()
    {    
    	// Delete Picture
    	if ($this->input->post('delete_pic') == 1)
    	{
			$this->load->helper('file');
    		delete_files($this->config->item('profile_images').$user->user_id."/");
    		$user_picture = '';
    	}    
    
    	// Upload Picture
		if (!$this->input->post('userfile'))
		{
			$config['upload_path'] 		= config_item('uploads_folder');
			$config['allowed_types'] 	= config_item('users_images_formats');		
			$config['overwrite']		= true;
			$config['max_size']			= config_item('users_images_max_size');
			$config['max_width']  		= config_item('users_images_max_dimensions');
			$config['max_height']  		= config_item('users_images_max_dimensions');
		
			$this->load->library('upload',$config);
			
			if (!$this->upload->do_upload())
			{
				$error = array('error' => $this->upload->display_errors());
			}	
			else
			{
				// Load Image Model
				$this->load->model('image_model');
				
				// Upload & Sizes
				$file_data		= $this->upload->data();
				$image_sizes	= array('full', 'large', 'medium', 'small');
				$create_path	= config_item('users_images_folder').$user->user_id.'/';
				
				// Do Resizes					
				$this->image_model->make_images($file_data, 'users', $image_sizes, $create_path, TRUE);										
				
				// Delete Upload
				$file_data['deleted'] = unlink(config_item('uploads_folder').$file_data['file_name']);
				$user_picture = $file_data['file_name'];		
			}	
		}	   
    
    	$update_data = array(
    		'username'		=> url_username($this->input->post('username'), 'none', true),
        	'email'			=> $this->input->post('email'),
        	'gravatar'		=> md5($this->input->post('email')),
        	'name'			=> $this->input->post('name'),
        	'image'			=> $user_picture,
        	'time_zone'		=> $this->input->post('time_zone'),
        	'privacy'		=> $this->input->post('privacy'),
        	'language'		=> $this->input->post('language'),
        	'geo_enable'	=> $this->input->post('geo_enable'),
    	);
    	
    	if ($this->social_auth->update_user($this->get('id'), $update_data))
    	{
	        $message = array('status' => 'success', 'message' => 'User changes saved');
   		}
   		else
   		{
	        $message = array('status' => 'error', 'message' => 'Could not save user change');
   		}        
        
        $this->response($message, 200);
    }
    
    function details_authd_post()
    {
    	if ($this->oauth_user_id == $this->get('id'))
    	{
			$user_meta_data = array();
			
			// User
			$user_id = $this->oauth_user_id;
			
			// Site
	    	if ($this->input->post('site_id')) $site_id = $this->input-->post('site_id');
	    	else $site_id = config_item('site_id');			
			
			// Build Meta
			foreach (config_item('user_data_details') as $config_meta)
			{
				$user_meta_data[$config_meta] = $this->input->post($config_meta);
			}
	    	
	    	// Update
	    	if ($update_meta = $this->social_auth->update_user_meta($site_id, $user_id, $this->input->post('module'), $user_meta_data))
	    	{
		        $message = array('status' => 'success', 'message' => 'User details saved', 'data' => $user_meta_data);
	   		}
	   		else
	   		{
		        $message = array('status' => 'error', 'message' => 'Could not save user details at this time');
	   		}
    	}

    	$this->response($message, 200);
    }
    
    function password_authd_post()
    {
	    $this->form_validation->set_rules('old_password', 'Old password', 'required');
	    $this->form_validation->set_rules('new_password', 'New Password', 'required|min_length['.config_item('min_password_length').']|max_length['.config_item('max_password_length').']|matches[new_password_confirm]');
	    $this->form_validation->set_rules('new_password_confirm', 'Confirm New Password', 'required');
	   	
	    if ($this->form_validation->run() == true) 
	    {
    		if ($change = $this->social_auth->change_password($this->oauth_user_id, $this->input->post('old_password'), $this->input->post('new_password')))
    		{
		        $message = array('status' => 'success', 'message' => 'Password changed Successfully');
    		}
    		else
    		{
		        $message = array('status' => 'success', 'message' => 'Oops could not change your password');    		
    		}
	    }
	    else
	    {
	        $message = array('status' => 'error', 'message' => validation_errors());
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