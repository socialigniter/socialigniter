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
		if ($this->session->userdata('user_level_id') > config_item('users_settings_level')) redirect(base_url(), 'refresh');        

		$user = $this->social_auth->get_user('user_id', $this->session->userdata('user_id')); 

		// Profile Data
	    $this->data['sub_title'] 	= 'Profile';     
 	 	$this->data['image']		= $user->image;
	 	$this->data['thumbnail']	= $this->social_igniter->profile_image($user->user_id, $user->image, $user->gravatar, 'small');
		$this->data['name']			= $user->name;
		$this->data['username']     = $user->username;			    
		$this->data['email']      	= $user->email;
		$this->data['language']		= $user->language;
		$this->data['time_zone']	= $user->time_zone;
		$this->data['geo_enabled']	= $user->geo_enabled;
		$this->data['privacy'] 		= $user->privacy;
		
		// Image Upload Settings
        $this->data['upload_size']	  = config_item('users_images_max_size') / 1024;
        $this->data['upload_formats'] = str_replace('|', ',', config_item('users_images_formats'));
        
		$this->render('dashboard_wide');
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
	 	
 		$this->render('dashboard_wide');	
 	}
 	
	function password() 
	{	
	    $this->data['sub_title']			= 'Password';			 
    	$this->data['old_password']   		= $this->input->post('old_password');
    	$this->data['new_password']   		= $this->input->post('new_password');
    	$this->data['new_password_confirm'] = $this->input->post('new_password_confirm');
        
		$this->render('dashboard_wide');
	}

  	function contact()
 	{
 	   	$user 		= $this->social_auth->get_user('user_id', $this->session->userdata('user_id')); 	
 		$phones		= $this->social_auth->get_user_meta_meta($this->session->userdata('user_id'), 'phone');
 		$addresses	= $this->social_auth->get_user_meta_meta($this->session->userdata('user_id'), 'address');

 	    $this->data['sub_title'] 	= 'Contact';
 		$this->data['phones']		= $phones;
 		$this->data['addresses']	= 'asdasd'; //$addresses;
    	
 		$this->render('dashboard_wide');	
 	}
 	
  	function connections()
 	{
 	    $this->data['sub_title'] 			= 'Connections';
		$this->data['social_connections']	= $this->social_igniter->get_settings_setting_value('social_connection', 'TRUE');
		$this->data['user_connections']		= $this->social_auth->get_connections_user($this->session->userdata('user_id'));
	    $this->data['message'] 				= validation_errors();

 		$this->render('dashboard_wide');	
 	}
 	
	function advanced() 
	{	
	    $this->data['sub_title']			= 'Advanced';			 

        
		$this->render('dashboard_wide');
	} 	
 	
	/* Site Settings */
	function site()
	{
		if ($this->session->userdata('user_level_id') != 1) redirect(base_url().config_item('home_view_redirect'), 'refresh');        
	
		$this->data['sub_title'] 	= 'Site';
		$this->data['this_module']	= 'site';
		$this->data['shared_ajax'] .= $this->load->view(config_item('dashboard_theme').'/partials/settings_modules_ajax.php', $this->data, true);		
		$this->render('dashboard_wide');	
	}

	function themes()
	{
		if ($this->session->userdata('user_level_id') != 1) redirect(base_url().config_item('home_view_redirect'), 'refresh');        

		$this->data['site']					= $this->social_igniter->get_site();
		$this->data['site_themes']			= $this->social_igniter->get_themes('site');
		$this->data['dashboard_themes']		= $this->social_igniter->get_themes('dashboard');
		$this->data['mobile_themes']		= $this->social_igniter->get_themes('mobile');
		$this->data['sub_title'] 			= 'Themes';
		$this->data['this_module']			= 'themes';
		$this->data['shared_ajax'] 		   .= $this->load->view(config_item('dashboard_theme').'/partials/settings_modules_ajax.php', $this->data, true);		
		$this->render('dashboard_wide');
	}
	
	function design()
	{
		if ($this->session->userdata('user_level_id') != 1) redirect(base_url().config_item('home_view_redirect'), 'refresh');        
	
		$this->data['sub_title'] 			= 'Design';
		$this->data['this_module']			= 'design';
		$this->data['shared_ajax'] 		   .= $this->load->view(config_item('dashboard_theme').'/partials/settings_modules_ajax.php', $this->data, true);
		$this->render('dashboard_wide');
	}

	function widgets()
	{
		if ($this->session->userdata('user_level_id') != 1) redirect(base_url().config_item('home_view_redirect'), 'refresh');        

		$this->data['sub_title'] 		= 'Widgets';
		$this->data['this_module']		= 'widgets';
		$this->data['layouts']			= $this->social_igniter->scan_layouts(config_item('site_theme'));

		// Build Widget Arrays
		// Will have to redo for different layouts		
		$current_widgets = array();		
				
		foreach ($this->site_widgets as $site_widget)
		{
			$current_widgets[$site_widget->setting][] = $site_widget;
		}
	
		// SET BASED ON URL
		if (!$this->uri->segment(3))
		{
			$layout = config_item('default_layout');		
		}
		else
		{
			$layout = $this->uri->segment(3);
		}

 		// Widget Regions	 		
 		foreach ($this->site_theme->layouts as $key => $site_layout)
 		{ 		
 			if ($key == $layout)
 			{
 				foreach ($site_layout as $region)
 				{
 					if (array_key_exists($region, $current_widgets))
 					{	
						$this->data[$region.'_widgets'] = $this->social_igniter->make_widgets_order($current_widgets[$region], $layout);	
 					}
 					else
 					{
 						$this->data[$region.'_widgets'] = '';
 					}
 				} 			
 			}
 		}
 		
 		// Load Regions From "Site Theme" folder
 		$this->data['layout_regions']	= $this->load->view(config_item('site_theme').'/partials/layout_regions_'.$layout, $this->data, true);	
		$this->data['layout_selected']	= $layout;

		$this->render('dashboard_wide');
	}

	function services()
	{
		if ($this->session->userdata('user_level_id') != 1) redirect(base_url().config_item('home_view_redirect'), 'refresh');
	
		$mobile_modules = $this->social_igniter->get_settings_setting('is_mobile_module');
	
		$this->data['mobile_modules']['none'] = '--none--';
		
		foreach ($mobile_modules as $mobile_module)
		{
			$this->data['mobile_modules'][$mobile_module->module] = ucwords($mobile_module->module);
		}
		
		$this->data['sub_title'] 	= 'Services';
		$this->data['this_module']	= 'services';
		$this->data['shared_ajax'] .= $this->load->view(config_item('dashboard_theme').'/partials/settings_modules_ajax.php', $this->data, true);		
		$this->render('dashboard_wide');	
	}
	
	function comments()
	{
		if ($this->session->userdata('user_level_id') != 1) redirect(base_url().config_item('home_view_redirect'), 'refresh');
		
		$this->data['sub_title'] 	= 'Comments';
		$this->data['this_module']	= 'comments';
		$this->data['shared_ajax'] .= $this->load->view(config_item('dashboard_theme').'/partials/settings_modules_ajax.php', $this->data, true);		
    	$this->render('dashboard_wide');
    }	

	function home()
	{
		if ($this->session->userdata('user_level_id') != 1) redirect(base_url().config_item('home_view_redirect'), 'refresh');

		$this->data['sub_title'] 	= 'Home';
		$this->data['this_module']	= 'home';
		$this->data['shared_ajax'] .= $this->load->view(config_item('dashboard_theme').'/partials/settings_modules_ajax.php', $this->data, true);
    	$this->render('dashboard_wide');
    }

	function users()
	{	
		if ($this->session->userdata('user_level_id') != 1) redirect(base_url().config_item('home_view_redirect'), 'refresh');

		$this->data['sub_title']	= 'Users';
		$this->data['this_module']	= 'users';
		$this->data['shared_ajax'] .= $this->load->view(config_item('dashboard_theme').'/partials/settings_modules_ajax.php', $this->data, true);
    	$this->render('dashboard_wide');
    }

	function places()
	{	
		if ($this->session->userdata('user_level_id') != 1) redirect(base_url().config_item('home_view_redirect'), 'refresh');

		$this->data['sub_title'] 	= 'Places';
		$this->data['this_module']	= 'places';
		$this->data['shared_ajax'] .= $this->load->view(config_item('dashboard_theme').'/partials/settings_modules_ajax.php', $this->data, true);
    	$this->render('dashboard_wide');
    } 
	
	function pages()
	{	
		if ($this->session->userdata('user_level_id') != 1) redirect(base_url().config_item('home_view_redirect'), 'refresh');

		$this->data['sub_title'] 	= 'Pages';
		$this->data['this_module']	= 'pages';
		$this->data['shared_ajax'] .= $this->load->view(config_item('dashboard_theme').'/partials/settings_modules_ajax.php', $this->data, true);
    	$this->render('dashboard_wide');
    }    

	function api()
	{
		if ($this->session->userdata('user_level_id') != 1) redirect(base_url().config_item('home_view_redirect'), 'refresh');

		$this->data['sub_title'] 	= 'API';
		$this->data['this_module']	= 'users';
		$this->data['shared_ajax'] .= $this->load->view(config_item('dashboard_theme').'/partials/settings_modules_ajax.php', $this->data, true);		
    	$this->render('dashboard_wide');
    }

    /* Modules Settings */
	function apps()
	{
		if ($this->session->userdata('user_level_id') != 1) redirect(base_url().config_item('home_view_redirect'), 'refresh');

		$this->data['sub_title']		= 'Apps';
		$this->data['core_modules']		= config_item('core_modules');
		$this->data['ignore_modules']	= config_item('ignore_modules');
		$this->data['modules']			= $this->social_igniter->scan_modules();
		$this->data['shared_ajax'] 	   .= $this->load->view(config_item('dashboard_theme').'/partials/settings_modules_ajax.php', $this->data, true);
		$this->render('dashboard_wide');
	}
	
	function get_apps()
	{
		if ($this->session->userdata('user_level_id') != 1) redirect(base_url().config_item('home_view_redirect'), 'refresh');

		$this->data['sub_title']		= 'Get Apps';	
		$this->render('dashboard_wide');
	}
	
	/* Categories */
	function categories()
	{
		$this->data['sub_title']		= 'Categories';
		
		$categories			= $this->social_tools->get_categories_view('module', $this->uri->segment(2));
		$categories_view	= NULL;
		
		if (!empty($categories))
		{		 
			foreach($categories as $category)
			{
				$this->data['item_id'] 			= $category->category_id;
				$this->data['item_type']		= $category->type;
	
				$this->data['title']			= $category->category;
				$this->data['title_link']		= base_url().$category->module.'/view/'.$category->category_id;
				$this->data['contents_count']	= manage_contents_count($category->contents_count);
				$this->data['publish_date']		= manage_published_date($category->created_at, $category->updated_at);
	
				// Alerts
				$this->data['item_alerts']		= '';

				// Actions
				$this->data['item_edit']		= base_url().'settings/'.$category->module.'/categories/'.$category->category_id;
				$this->data['item_delete']		= base_url().'api/categories/destroy/id/'.$category->category_id;
				
				// View
				$categories_view .= $this->load->view(config_item('dashboard_theme').'/partials/item_categories.php', $this->data, true);
			}
		}
	 	else
	 	{
	 		$categories_view = '<li>There are no '.ucwords($this->uri->segment(2)).' Categories</li>';
 		}

		// Final Output
		$this->data['categories_view'] = $categories_view;		

		$this->render('dashboard_wide');
	}
	
	function categories_manager()
	{
		if ($category = $this->social_tools->get_category($this->uri->segment(4)))
		{
			$this->data['sub_title']	= 'Categories';
			$this->data['page_title']	= $category->category;
		
			$this->data['category']		= $category->category;
			$this->data['category_url']	= $category->category_url;
			$this->data['description']	= $category->description;
			$this->data['parent_id']	= $category->parent_id;
			$this->data['access']		= $category->access;
			$this->data['site_id']		= $category->site_id;
			$this->data['details']		= $category->details;
			
			$details = json_decode($category->details);
			
			if ($details->thumb != '')
			{
				$this->data['thumb']	= base_url().config_item('categories_images_folder').$category->category_id.'/small_'.$details->thumb;
			}
			else
			{
				$this->data['thumb']	= '';
			}
		}
		else
		{
			$this->data['sub_title']	= 'Create';

			$this->data['category']		= '';
			$this->data['category_url']	= '';
			$this->data['description']	= '';		
			$this->data['parent_id']	= 0;
			$this->data['access']		= config_item('access');
			$this->data['thumb']		= '';
			$this->data['site_id']		= config_item('site_id');
			$this->data['details']		= '';
		}

		$this->data['categories_dropdown'] = $this->social_tools->make_categories_dropdown('type', 'class-category', $this->session->userdata('user_id'), $this->session->userdata('user_level_id'), FALSE);
		
		// Image Upload Settings
        $this->data['upload_size']	  = config_item('users_images_max_size') / 1024;
        $this->data['upload_formats'] = str_replace('|', ',', config_item('users_images_formats'));
        
		$this->render('dashboard_wide');
	}	

	/* Partials */
	function mobile_phone_editor()
	{
		$this->load->view(config_item('dashboard_theme').'/partials/mobile_phone_editor');
	}
}