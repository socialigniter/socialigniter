<?php
class Users extends Home_Controller {
 
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
		$users 			= $this->social_auth->get_users();
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
			$user = $this->social_auth->get_user($this->uri->segment(3));
		
        	$this->data['sub_title'] 	= "Edit";
       
       		$this->data['name']			= $user->name;
       		$this->data['username']		= $user->username;
       		$this->data['email']		= $user->email;
       		$this->data['company']		= $user->company;
       		$this->data['phone']		= $user->phone;
       		$this->data['location']		= $user->location;
       		$this->data['url']			= $user->url;
       		$this->data['bio']			= $user->bio;

        }
        else
        {        
        	$this->data['sub_title'] = 'Create';

       		$this->data['name']			= '';
       		$this->data['username']		= '';
       		$this->data['email']		= '';
       		$this->data['name']			= '';
       		$this->data['phone']		= '';
       		$this->data['company']		= '';
       		$this->data['location']		= '';
       		$this->data['url']			= '';
       		$this->data['bio']			= '';
       
        }
        				
		$this->data['users_levels'] = $this->social_auth->get_users_levels();		
				
		$this->render('dashboard_wide');
    }

    
    function levels()
    {
    
    	$this->render('dashboard_wide');
    }    
 	
  
}
