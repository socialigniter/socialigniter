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
		$user_settings		= $this->social_auth->get_user($this->session->userdata('user_id')); 
		$user_picture 		= $user_settings->image; 

   		$this->form_validation->set_rules('name', 'Name', 'required');

        if ($this->form_validation->run() == true) {
        
        	if ($this->input->post('delete_pic') == 1)
        	{
				$this->load->helper('file');
        		delete_files($this->config->item('profile_images').$user_settings->user_id."/");
        		$user_picture = '';
        	}
	           
			if (!$this->input->post('userfile'))
			{
				$config['upload_path'] 		= "./uploads/";
				$config['allowed_types'] 	= 'gif|jpg|jpeg|png|GIF|JPG|JPEG|PNG';		
				$config['overwrite']		= true;
				$config['max_size']			= '2500';
				$config['max_width']  		= '2200';
				$config['max_height']  		= '2200';
			
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
	    		$this->session->set_flashdata('message', "Profile Updated");
		        $this->session->set_userdata('name',  $update_data['name']);   		
		        $this->session->set_userdata('image', $update_data['image']);
	   		
	   			redirect('settings/profile', 'refresh');
	   		}
	   		else
	   		{
	   			redirect('settings/profile', 'refresh');
	   		}
		} 
		else 
		{ 
	        // Set the flash data error message if there is one
	        $this->data['message'] 		= (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
	 		$this->data['name']		    = $this->input->post('name');
	        $this->data['company']      = $this->input->post('company');
	        $this->data['location']     = $this->input->post('location');
	        $this->data['url']          = $this->input->post('url');
	        $this->data['bio']          = $this->input->post('bio');
	 	 	$this->data['image']		= $this->input->post('image');

			if ($this->input->post('delete_pic')) { $delete_pic_checked = true; }
			else { $delete_pic_checked = false; }	 	 	
	 	 	
			$this->data['delete_pic']   = array(
			    'name'		=> 'delete_pic',
			 	'id'        => 'delete_pic',   
			    'value'     => $this->input->post('delete_pic'),
			    'checked'	=> $delete_pic_checked,
			);
		}	    

 	    $this->data['sub_title'] 	= "Profile";
 		$this->data['name'] 		= is_empty($user_settings->name);
        $this->data['company'] 		= is_empty($user_settings->company);
        $this->data['location'] 	= is_empty($user_settings->location);
        $this->data['url']      	= is_empty($user_settings->url);
        $this->data['bio']      	= is_empty($user_settings->bio);
 	 	$this->data['image']		= is_empty($user_settings->image);
 	 	
 		$this->render();	
 	} 
 	
 	
	function account()
 	{
	    $this->data['sub_title'] = "Account";
	    
	    $user_settings = $this->social_auth->get_user($this->session->userdata('user_id')); 
	    
    	$this->form_validation->set_rules('username', 'Username', 'required|min_length['.$this->config->item('min_username_length').']|max_length['.$this->config->item('max_username_length').']');
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
        		$this->session->set_flashdata('message', "Account Settings Updated");

				// Update user session data	
		        $this->session->set_userdata('username', $update_data['username']);   		
		        $this->session->set_userdata('email', $update_data['email']);        		
		        $this->session->set_userdata('geo_enabled', $update_data['geo_enabled']);
        		
       			redirect('settings/account', 'refresh');
       		}
       		else
       		{
       		    $this->session->set_flashdata('message', "Unable To Update Settings");

       		
       			redirect('settings/account', 'refresh');
       		}   
       		
		} 
		else { 
			
	        // Set the flash data error message if there is one
	        $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

			$home_base_array 									= array();
			$home_base_array[''] 								= '--select--';
			$home_base_array[$this->config->item('site_slug')]	= $this->config->item('site_slug');
			 
			foreach($this->config->item('social_connections') as $connection)
			{ 
			  $home_base_array[$connection] = $connection;
			}	        

			$this->data['username']      	= $user_settings->username;			    
			$this->data['email']      		= $user_settings->email;
			$this->data['home_base_array']	= $home_base_array;
			$this->data['home_base']		= $user_settings->home_base;
			$this->data['language']			= $user_settings->language;
			$this->data['time_zone']		= $user_settings->time_zone;

			if ($user_settings->geo_enabled) { $geo_enabled_checked = true; }
			else { $geo_enabled_checked = false; }	

			$this->data['geo_enabled'] = array(
			    'name'		=> 'geo_enabled',
			 	'id'        => 'geo_enabled',   
			    'value'     => $user_settings->geo_enabled,
			    'checked'	=> $geo_enabled_checked,
			);
											    
			if ($user_settings->privacy) { $privacy_checked = true; }
			else { $privacy_checked = false; }	
										    
			$this->data['privacy'] = array(
			    'name'      => 'privacy',
	    		'id'        => 'privacy',
			    'value'     => $user_settings->privacy,
			    'checked'   => $privacy_checked,
			);
            
 			$this->render();	

		}
 	}

 	
 	// Change user password
	function password() 
	{

	    $this->data['sub_title'] = "Password";
		    
	    $this->form_validation->set_rules('old_password', 'Old password', 'required');
	    $this->form_validation->set_rules('new_password', 'New Password', 'required|min_length['.$this->config->item('min_password_length').']|max_length['.$this->config->item('max_password_length').']|matches[new_password_confirm]');
	    $this->form_validation->set_rules('new_password_confirm', 'Confirm New Password', 'required');
	   	    
	    if ($this->form_validation->run() == true) // false) 
	    { 

	        $identity = $this->session->userdata($this->config->item('identity'));
	        
	        $change = $this->social_auth->change_password($identity, $this->input->post('old_password'), $this->input->post('new_password'));
		
			// If the password was successfully changed
    		if ($change) { 
    		
    			$this->session->set_flashdata('message', 'Password Changed Successfully');
    			$this->social_auth->logout();
    			redirect('login');
    		}
    		else {
    			$this->session->set_flashdata('message', 'Password Change Failed');
    			redirect('settings/password', 'refresh');
    		}
	        
	    }
	    else {

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
 	    
 	   	$user_settings = $this->social_auth->get_user($this->session->userdata('user_id'));

   		$this->form_validation->set_rules('phone', 'Phone', 'required|valid_phone_number');

        if ($this->form_validation->run() == true) { //check to see if we are updating user

        if ($user_settings->phone_verify == 'verified') { $phone = $user_settings->phone; }
        else { $phone = ereg_replace("[^0-9]", "", $this->input->post('phone')); }
                
        if ($user_settings->phone_verify == 'verified') { $phone_verify = $user_settings->phone_verify; }
        else { $phone_verify = random_element($this->config->item('mobile_verify')); }

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

			if ($user_settings->phone_search) { $phone_search_checked = true; }
			else { $phone_search_checked = false; }	
	    
			$this->data['phone_search'] = array(
			    'name'      => 'phone_search',
	    		'id'        => 'phone_search',
			    'value'     => $user_settings->phone_search,
			    'checked'   => $phone_search_checked,
			);      
												                
		}	    

 		$this->data['phone']		    = is_empty($user_settings->phone);
        $this->data['phone_verify']     = $user_settings->phone_verify;
        $this->data['phone_active']     = $user_settings->phone_active;

		if ($user_settings->phone_search) { $phone_search_checked = true; }
		else { $phone_search_checked = false; }	
    
		$this->data['phone_search'] = array(
		    'name'      => 'phone_search',
    		'id'        => 'phone_search',
		    'value'     => $user_settings->phone_search,
		    'checked'   => $phone_search_checked,
		);        
    	
 		$this->render();	
 	 	
 	}	
 	
 	function mobile_delete()
 	{
 	
 	   	$user_settings = $this->social_auth->get_user($this->session->userdata('user_id'));

		if ($user_settings->phone != "")
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
	    $this->data['message'] 				= (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

 		$this->render();	
 	}		
   
	function modules()
	{
		$this->data['core_modules'] = config_item('core_modules');
		$this->data['modules']		= $this->social_igniter->scan_modules();
		$this->data['sub_title']	= 'Module';
	
		$this->render();
	}

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
	
	function pages()
	{
		$this->data['sub_title'] = 'Pages';
	
    	$this->render();
    }	

	function users()
	{
		$this->data['sub_title'] = 'Users';
	
    	$this->render();
    }

	function home()
	{
		$this->data['sub_title'] = 'Home';
	
    	$this->render();
    }  

	function messages()
	{
		$this->data['sub_title'] = 'Messages';
	
    	$this->render();
    }
    
	function comments()
	{
		$this->data['sub_title'] = 'Comments';
	
    	$this->render();
    }    
    
}