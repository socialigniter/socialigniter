<?php
class Profile extends Public_Controller {
 
    function __construct() 
    {
        parent::__construct();

		if (!$this->uri->segment(2) || (config_item('users_profile') != 'TRUE')) redirect(base_url());	
	
		$user = $this->social_auth->get_user_by_username($this->uri->segment(2)); 
 	
		if($user)
		{	
			$this->data['user_id'] 		= $user->user_id;	
			$this->data['username'] 	= $user->username;
			$this->data['name'] 		= $user->name;
			$this->data['company'] 		= $user->company;
			$this->data['location'] 	= $user->location; 
			$this->data['url'] 			= $user->url; 
			$this->data['bio'] 			= $user->bio; 
			$this->data['home_base'] 	= $user->home_base; 
			$this->data['image'] 		= $user->image; 
			$this->data['created_on'] 	= $user->created_on;

			// Links
	 		$this->data['follow_url'] = 'SDFsdf23@#$sfsfsdf';
			
			// Sidebar
			$this->data['sidebar_profile'] = $this->load->view(config_item('site_theme').'/partials/sidebar_profile.php', $this->data, true);
			
			///$this->load->model('status_model');
			$this->data['user_timeline'] = ''; //$this->status_model->get_status_timeline_user($user->user_id, 8);			
			
		}
		else
		{
			show_404('page');
		}

    }

 	function index()
 	{
 	
 		$this->data['page_title'] = $this->data['name']."'s profile"; 		
 		
		$this->render('profile');
 	}
 	
 	function image()
 	{
 		$this->data['page_title'] = $this->data['name']."'s profile picture";	

 		$this->render('main_single');
 	}
 	
 
}