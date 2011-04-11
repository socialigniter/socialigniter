<?php
class Users extends Dashboard_Controller {
 
    function __construct() 
    {
        parent::__construct();
        
		// If not Super redirect
		if ($this->data['logged_user_level_id'] > 2) redirect('home');	        
         
 	    $this->data['page_title'] = "Users";
    }
 
 	function index()
 	{   		
		// Get Users
		$users 			= $this->social_auth->get_users('active', 1);
		$users_view 	= NULL;

		// Title Stuff
		$this->data['page_title']	= 'Users';
		$this->data['sub_title']	= 'Manage';
		 
		// Users View Loop 
		foreach($users as $user):
		
			$this->data['user_id'] 			= $user->user_id;
			$this->data['name']				= $user->name;
			$this->data['avatar']			= $this->social_igniter->profile_image($user->user_id, $user->image, $user->email);
			$this->data['profile']			= base_url().'profile/'.$user->username;
			$this->data['created_on']		= format_datetime('SIMPLE_ABBR', $user->created_on);
			$this->data['last_login']		= format_datetime('SIMPLE_TIME_ABBR', $user->last_login);

			// Alerts
			$this->data['user_alerts']		= item_alerts_user($user);			
			
			// Actions
			$this->data['user_state']		= item_user_state($user->active);
			$this->data['user_edit']		= base_url().'users/manage/'.$user->user_id;
			$this->data['user_delete']		= base_url().'api/users/destroy/id/'.$user->user_id;
			
			// View
			$users_view .= $this->load->view(config_item('dashboard_theme').'/partials/item_users.php', $this->data, true);			

		endforeach;	

		// Final Output
		$this->data['users_view'] 	= $users_view;	
        $this->data['message'] 		= (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
        
	    $this->render('dashboard_wide');
 	}
 	
  	// Create / Edit
	function editor() 
	{
		if (($this->uri->segment(2) == 'manage') && ($this->uri->segment(3)))
		{	
			$user 		= $this->social_auth->get_user('user_id', $this->uri->segment(3));
			if (!$user) redirect(base_url().'users');
	 		$user_meta	= $this->social_auth->get_user_meta($user->user_id);		

		
        	$this->data['sub_title'] 	= "Edit";
 
 			$this->data['user_level_id']= $user->user_level_id;      
       		$this->data['name']			= $user->name;
       		$this->data['username']		= $user->username;
       		$this->data['email']		= $user->email;
       		$this->data['company']		= $this->social_auth->find_user_meta_value('company', $user_meta);
       		$this->data['location']		= $this->social_auth->find_user_meta_value('location', $user_meta);
       		$this->data['url']			= $this->social_auth->find_user_meta_value('url', $user_meta);
       		$this->data['bio']			= $this->social_auth->find_user_meta_value('bio', $user_meta);

        }
        else
        {        
        	$this->data['sub_title'] 	= 'Create';

			$this->data['user_level_id']= 4;
       		$this->data['name']			= '';
       		$this->data['username']		= '';
       		$this->data['email']		= '';
       		$this->data['name']			= '';
       		$this->data['company']		= '';
       		$this->data['location']		= '';
       		$this->data['url']			= '';
       		$this->data['bio']			= '';
        }
				
		$this->render('dashboard_wide');
    }

    
    function levels()
    {
    
    	$this->render('dashboard_wide');
    }    
 	
  
}
