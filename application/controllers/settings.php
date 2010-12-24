<?php
class Settings extends Dashboard_Controller {
 
    function __construct() 
    {
        parent::__construct();
        
        $this->data['page_title']	= 'Settings';
    } 
 
 	function index()
 	{
 		redirect('settings/profile');
 	}

 	function profile()
 	{	
		$user			= $this->social_auth->get_user($this->session->userdata('user_id')); 
		$user_picture 	= $user->image; 

   		$this->form_validation->set_rules('name', 'Name', 'required');

        if ($this->form_validation->run() == true)
        {
        	if ($this->input->post('delete_pic') == 1)
        	{
				$this->load->helper('file');
        		delete_files($this->config->item('profile_images').$user->user_id."/");
        		$user_picture = '';
        	}
	           
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
					$data = array('upload_data' => $this->upload->data());
					$this->load->model('image_model');			
					$this->image_model->make_profile_images($data['upload_data']['file_name'], $data['upload_data']['image_width'], $data['upload_data']['image_height'], $this->session->userdata('user_id'));										
					$data['deleted'] = unlink("uploads/".$data['upload_data']['file_name']);
					$user_picture = $data['upload_data']['file_name'];		
				}	
			}	
	
	    	$update_data = array(
	        	'name'		=> $this->input->post('name'),
	        	'company'	=> $this->input->post('company'),
	        	'location'	=> $this->input->post('location'),
	        	'url'		=> $this->input->post('url'),
	        	'bio'		=> $this->input->post('bio'),
	        	'image'		=> $user_picture 
			);

	    	// Update the user
	    	if ($this->social_auth->update_user($this->session->userdata('user_id'), $update_data))
	    	{
				foreach ($update_data as $field => $value)
				{
				    $this->session->set_userdata($field,  $value);			
				}
	   		}
	   		
	   		redirect('settings/profile', 'refresh');
		} 
		else 
		{ 	
	 	    $this->data['sub_title'] 	= "Profile";
		    $this->data['message'] 		= validation_errors();
	 		$this->data['name'] 		= is_empty($user->name);
	        $this->data['company'] 		= is_empty($user->company);
	        $this->data['location'] 	= is_empty($user->location);
	        $this->data['url']      	= is_empty($user->url);
	        $this->data['bio']      	= is_empty($user->bio);
	 	 	$this->data['image']		= is_empty($user->image);
		 	$this->data['thumbnail']	= $this->social_igniter->profile_image($user->user_id, $user->image, $user->email, 'small');
		}	    
 	 	
 		$this->render();	
 	}
 	
 	
	function account()
 	{
	    $this->data['sub_title'] = "Account";
	    
	    $user = $this->social_auth->get_user($this->session->userdata('user_id')); 
	    
    	$this->form_validation->set_rules('username', 'Username', 'required|min_length['.config_item('min_username_length').']|max_length['.config_item('max_username_length').']');
    	$this->form_validation->set_rules('email', 'Email', 'required|valid_email');

        if ($this->form_validation->run() == true) { 

        	$update_data = array(
				'username' 		=> url_username($this->input->post('username'), 'none', true),
	        	'email'			=> $this->input->post('email'),
	        	'home_base'		=> $this->input->post('home_base'),
	        	'language'		=> $this->input->post('language'),
	        	'time_zone'		=> $this->input->post('timezones'),
	        	'geo_enabled'	=> $this->input->post('geo_enabled'),
	        	'privacy'		=> $this->input->post('privacy'),
	        	'utc_offset'	=> timezones($this->input->post('timezones')) * 60 * 60      					
			);
        	
        	// Update the user
        	if ($this->social_auth->update_user($this->session->userdata('user_id'), $update_data))
        	{        	
				foreach ($update_data as $field => $value)
				{
				    $this->session->set_userdata($field,  $value);			
				}
        		
       			redirect('settings/account', 'refresh');
       		}
       		else
       		{
       		    $this->session->set_flashdata('message', "Unable To Update Settings");       		
       			redirect('settings/account', 'refresh');
       		}   
		} 
		else
		{ 	
	        // Set the flash data error message if there is one
	        $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

			$home_base_array 									= array();
			$home_base_array[''] 								= '--select--';
			$home_base_array[config_item('site_slug')]	= config_item('site_slug');
			 
			foreach(config_item('social_connections') as $connection)
			{ 
			  $home_base_array[$connection] = $connection;
			}	        

			$this->data['username']      	= $user->username;			    
			$this->data['email']      		= $user->email;
			$this->data['home_base_array']	= $home_base_array;
			$this->data['home_base']		= $user->home_base;
			$this->data['language']			= $user->language;
			$this->data['time_zone']		= $user->time_zone;
			$this->data['geo_enabled']		= $user->geo_enabled;
			$this->data['privacy'] 			= $user->privacy;
            
 			$this->render();	
		}
 	}

 	
 	// Change user password
	function password() 
	{
	    $this->data['sub_title'] = "Password";
		    
	    $this->form_validation->set_rules('old_password', 'Old password', 'required');
	    $this->form_validation->set_rules('new_password', 'New Password', 'required|min_length['.config_item('min_password_length').']|max_length['.config_item('max_password_length').']|matches[new_password_confirm]');
	    $this->form_validation->set_rules('new_password_confirm', 'Confirm New Password', 'required');
	   	    
	    if ($this->form_validation->run() == true) // false) 
	    { 
	        $identity = $this->session->userdata(config_item('identity'));
	        
	        $change = $this->social_auth->change_password($identity, $this->input->post('old_password'), $this->input->post('new_password'));
		
			// If the password was successfully changed
    		if ($change)
    		{ 
    			$this->session->set_flashdata('message', 'Password Changed Successfully');
    			$this->social_auth->logout();
    			redirect('login');
    		}
    		else
    		{
    			$this->session->set_flashdata('message', 'Password Change Failed');
    			redirect('settings/password', 'refresh');
    		}
	    }
	    else
	    {
	        //set the flash data error message if there is one
	        $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
        												 
        	$this->data['old_password']   		= $this->input->post('old_password');
        	$this->data['new_password']   		= $this->input->post('new_password');
        	$this->data['new_password_confirm'] = $this->input->post('new_password_confirm');
	        
        	//render
 			$this->render();	
	    }
	}

  	function mobile()
 	{
 	    $this->data['sub_title'] = "Mobile";
 	    
 	   	$user = $this->social_auth->get_user($this->session->userdata('user_id'));

   		$this->form_validation->set_rules('phone', 'Phone', 'required|valid_phone_number');

        if ($this->form_validation->run() == true) { //check to see if we are updating user

        if ($user->phone_verify == 'verified') { $phone = $user->phone; }
        else { $phone = ereg_replace("[^0-9]", "", $this->input->post('phone')); }
                
        if ($user->phone_verify == 'verified') { $phone_verify = $user->phone_verify; }
        else { $phone_verify = random_element(config_item('mobile_verify')); }

	    	$update_data = array(
	        	'phone'			=> $phone,
	        	'phone_verify'	=> $phone_verify,
	        	'phone_active'	=> $this->input->post('phone_active'),
	        	'phone_search'	=> $this->input->post('phone_search')
			);
        	
        	// Update the user
        	if ($this->social_auth->update_user($this->session->userdata('user_id'), $update_data))
        	{
        		$this->session->set_flashdata('message', "Phone Number Added");
       			redirect('settings/mobile', 'refresh');
       		}
       		else
       		{
       			redirect('settings/mobile', 'refresh');
       		}
      
		} 
		else 
		{ 	
			//display the create user form
	        $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			
	 		$this->data['phone']		    	= $this->input->post('phone');
	 		$this->data['phone_active_array'] 	= array('1'=>'Yes','0'=>'No');
	 		$this->data['phone_active']     	= $this->input->post('phone_active');

			if ($user->phone_search) { $phone_search_checked = true; }
			else { $phone_search_checked = false; }	
	    
			$this->data['phone_search'] = array(
			    'name'      => 'phone_search',
	    		'id'        => 'phone_search',
			    'value'     => $user->phone_search,
			    'checked'   => $phone_search_checked,
			);      
		}	    

 		$this->data['phone']		    = is_empty($user->phone);
        $this->data['phone_verify']     = $user->phone_verify;
        $this->data['phone_active']     = $user->phone_active;

		if ($user->phone_search) { $phone_search_checked = true; }
		else { $phone_search_checked = false; }	
    
		$this->data['phone_search'] = array(
		    'name'      => 'phone_search',
    		'id'        => 'phone_search',
		    'value'     => $user->phone_search,
		    'checked'   => $phone_search_checked,
		);
    	
 		$this->render();	
 	}	
 	
 	function mobile_delete()
 	{
 	   	$user = $this->social_auth->get_user($this->session->userdata('user_id'));

		if ($user->phone != "")
		{
        	$update_data = array(
	        	'phone'			=> "",
	        	'phone_verify'	=> "",
	        	'phone_active'	=> "",
	        	'phone_search'	=> ""
			);
        	
        	// Update the user
        	if ($this->social_auth->update_user($this->session->userdata('user_id'), $update_data))
        	{
        		$this->session->set_flashdata('message', "Phone Number Deleted");
       			redirect("settings/mobile", 'refresh');
       		}
       		else
       		{
       			redirect("error");
       		}		
		}
	}
 	
  	function connections()
 	{
 	    $this->data['sub_title'] 			= "Connections";
		$this->data['social_connections']	= $this->social_igniter->get_settings_type_value('social_connection', 'TRUE');
		$this->data['user_connections']		= $this->social_auth->get_connections_user($this->session->userdata('user_id'));
	    $this->data['message'] 				= validation_errors();

 		$this->render();	
 	}		
   
    // Display Modules Settings
	function modules()
	{
		$this->data['core_modules']		= config_item('core_modules');
		$this->data['ignore_modules']	= config_item('ignore_modules');
		$this->data['modules']			= $this->social_igniter->scan_modules();
		$this->data['sub_title']		= 'Module';
	
		$this->render();
	}

	// Module Settings Update
	function update()
	{
		// If not Super or Admin redirect
		if ($this->data['level'] > 1) redirect('home');

		$settings_update = $_POST;
	
		if ($settings_update)
        {
			$this->social_igniter->update_settings($this->input->post('module'), $settings_update);
														
			redirect($this->session->userdata('previous_page'), 'refresh');
		}
		else
		{
			redirect($this->session->userdata('previous_page'), 'refresh');
		}
	}
	
	function comments()
	{	
		$this->data['sub_title'] = 'Comments';
    	$this->render();
    }	

	function home()
	{
		$this->data['sub_title'] = 'Home';
	
    	$this->render();
    }  

	function api()
	{
		$this->data['sub_title'] = 'API';
	
    	$this->render();
    }  
    
}