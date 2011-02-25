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
					// Load Image Model
					$this->load->model('image_model');
					
					// Upload & Sizes
					$file_data		= $this->upload->data();
					$image_sizes	= array('full', 'large', 'medium', 'small');
					$create_path	= config_item('users_images_folder').$this->session->userdata('user_id').'/';
					
					// Do Resizes					
					$this->image_model->make_images($file_data, 'users', $image_sizes, $create_path, TRUE);										
					
					// Delete Upload
					$file_data['deleted'] = unlink(config_item('uploads_folder').$file_data['file_name']);
					$user_picture = $file_data['file_name'];		
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
 	
 	function details()
 	{
		$user		= $this->social_auth->get_user('user_id', $this->session->userdata('user_id'));
		$user_meta	= $this->social_auth->get_user_meta($this->session->userdata('user_id'));

		foreach (config_item('user_meta_details') as $config_meta)
		{
			$this->data[$config_meta] = $this->social_auth->find_user_meta_value($config_meta, $user_meta);
		}

 	    $this->data['sub_title'] = "Details";
	 	
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
 	   	$user 		= $this->social_auth->get_user('user_id', $this->session->userdata('user_id')); 	
 		$user_meta	= $this->social_auth->get_user_meta_meta($this->session->userdata('user_id'), 'phone');
 	
 		$this->data['phones']		= $user_meta;
 	    $this->data['sub_title'] 	= "Mobile";
    	
 		$this->render();	
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
		$mobile_modules = $this->social_igniter->get_settings_type('is_mobile_module');
	
		$this->data['mobile_modules']['none'] = '--none--';
		
		foreach ($mobile_modules as $mobile_module)
		{
			$this->data['mobile_modules'][$mobile_module->module] = ucwords($mobile_module->module);
		}
		
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