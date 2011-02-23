<?php
class Settings extends Dashboard_Controller
{ 
    function __construct() 
    {
        parent::__construct();
        
        $this->data['page_title'] = 'Settings';
    } 
 
 	function index()
 	{
 		redirect('settings/profile');
 	}

	/* User Settings */
	function profile()
 	{
	    $this->data['sub_title'] = "Profile";
	    
		$user = $this->social_auth->get_user('user_id', $this->session->userdata('user_id')); 

/*
    	$update_data = array(
			'username' 		=> url_username($this->input->post('username'), 'none', true),
        	'email'			=> $this->input->post('email'),
        	'language'		=> $this->input->post('language'),
        	'time_zone'		=> $this->input->post('timezones'),
        	'geo_enabled'	=> $this->input->post('geo_enabled'),
        	'privacy'		=> $this->input->post('privacy'),
        	'utc_offset'	=> timezones($this->input->post('timezones')) * 60 * 60      					
		);
        	
    	// Update the user
    	$this->social_auth->update_user($this->session->userdata('user_id'), $update_data);
*/       
 	 	$this->data['image']		= is_empty($user->image);
	 	$this->data['thumbnail']	= $this->social_igniter->profile_image($user->user_id, $user->image, $user->gravatar, 'small');
		$this->data['name']			= $user->name;
		$this->data['username']     = $user->username;			    
		$this->data['email']      	= $user->email;
		$this->data['language']		= $user->language;
		$this->data['time_zone']	= $user->time_zone;
		$this->data['geo_enabled']	= $user->geo_enabled;
		$this->data['privacy'] 		= $user->privacy;
        
		$this->render();
 	}
 	
 		
 	function details()
 	{	
		$user		= $this->social_auth->get_user('user_id', $this->session->userdata('user_id'));
		$user_meta	= $this->social_auth->get_user_meta($this->session->userdata('user_id'));

		foreach (config_item('user_data_meta') as $config_meta)
		{
			if ($this_meta = $this->social_auth->find_user_meta_value($config_meta, $user_meta))
			{
				$this->data[$config_meta] = $this_meta;		
			}
			else
			{
				$add_user_meta = array(
					'user_id'	=> $this->session->userdata('user_id'),
					'site_id'	=> config_item('site_id'),
					'module'	=> 'users',
					'meta'		=> $config_meta,
					'value'		=> ''
				);
			
				$this->social_auth->add_user_meta($add_user_meta);
				$this->data[$config_meta] = '';
			}
		}

 	    $this->data['sub_title'] 	= "Details";
	 	
 		$this->render();	
 	}
 	
	function password() 
	{
	    $this->data['sub_title']			= "Password";			 
    	$this->data['old_password']   		= $this->input->post('old_password');
    	$this->data['new_password']   		= $this->input->post('new_password');
    	$this->data['new_password_confirm'] = $this->input->post('new_password_confirm');
        
		$this->render();
	}

  	function mobile()
 	{
 	    $this->data['sub_title'] = "Mobile";
 	    
 	   	$user = $this->social_auth->get_user($this->session->userdata('user_id'));

   		$this->form_validation->set_rules('phone', 'Phone', 'required|valid_phone_number');

        if ($this->form_validation->run() == true)
        {
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
 	
	/* Site Settings */
	function site()
	{
		$this->data['sub_title'] = 'Site';
		$this->render();	
	}

	function themes()
	{
		$this->data['site']					= $this->social_igniter->get_site();
		$this->data['site_themes']			= $this->social_igniter->get_themes('site');
		$this->data['dashboard_themes']		= $this->social_igniter->get_themes('dashboard');
		$this->data['mobile_themes']		= $this->social_igniter->get_themes('mobile');
		$this->data['sub_title'] 			= 'Themes';	
		$this->render('dashboard_wide');
	}

	function widgets()
	{
		$this->data['sub_title'] = 'Widgets';
		$this->render();
	}

	function services()
	{
		$this->data['sub_title'] = 'Services';
		$this->render();	
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

	function users()
	{	
		$this->data['sub_title'] = 'Users';
    	$this->render();
    }
	
	function pages()
	{	
		$this->data['sub_title'] = 'Pages';
    	$this->render();
    }    

	function api()
	{
		$this->data['sub_title'] = 'API';
    	$this->render();
    }

    /* Modules Settings */
	function modules()
	{
		$this->data['core_modules']		= config_item('core_modules');
		$this->data['ignore_modules']	= config_item('ignore_modules');
		$this->data['modules']			= $this->social_igniter->scan_modules();
		$this->data['sub_title']		= 'Module';
	
		$this->render();
	}

	/* Update Settings */
	function update()
	{
		if ($this->data['logged_user_level_id'] > 1) redirect('home');

		$settings_update = $_POST;
	
		if ($settings_update)
        {
			$this->social_igniter->update_settings($this->input->post('module'), $settings_update);
														
			redirect(base_url().'settings/'.$this->input->post('module'), 'refresh');
		}
		else
		{
			redirect($this->session->userdata('previous_page'), 'refresh');
		}
	}
}