<?php
class Profile extends Public_Controller {
 
    function __construct() 
    {
        parent::__construct();

		if (!$this->uri->segment(2) || (config_item('users_profile') != 'TRUE')) redirect(base_url());	
	
		$this->user = $this->social_auth->get_user_by_username($this->uri->segment(2)); 
 	
		if($this->user)
		{	
			$this->data['user_id'] 		= $this->user->user_id;	
			$this->data['username'] 	= $this->user->username;
			$this->data['email'] 		= $this->user->email;
			$this->data['name'] 		= $this->user->name;
			$this->data['company'] 		= $this->user->company;
			$this->data['location'] 	= $this->user->location; 
			$this->data['url'] 			= $this->user->url; 
			$this->data['bio'] 			= $this->user->bio; 
			$this->data['home_base'] 	= $this->user->home_base; 
			$this->data['image'] 		= $this->user->image; 
			$this->data['created_on'] 	= $this->user->created_on;

			// Links
	 		$this->data['follow_url'] 	= base_url().'api/relationships/follow/'.$this->user->user_id;
	 		$this->data['message_url'] 	= base_url().'api/message/send/'.$this->user->user_id;

			
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