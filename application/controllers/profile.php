<?php
class Profile extends Site_Controller {
 
    function __construct() 
    {
        parent::__construct();

		if (!$this->uri->segment(2) || (config_item('users_profile') != 'TRUE')) redirect(base_url());	
	
		$this->user = $this->social_auth->get_user_by_username($this->uri->segment(2)); 
 	
		if($this->user)
		{	
			$this->data['user_id'] 			= $this->user->user_id;	
			$this->data['username'] 		= $this->user->username;
			$this->data['email'] 			= $this->user->email;
			$this->data['name'] 			= $this->user->name;
			$this->data['company'] 			= $this->user->company;
			$this->data['location'] 		= $this->user->location; 
			$this->data['url'] 				= $this->user->url; 
			$this->data['bio'] 				= $this->user->bio; 
			$this->data['home_base'] 		= $this->user->home_base; 
			$this->data['image'] 			= $this->user->image; 
			$this->data['created_on'] 		= $this->user->created_on;

			// Links
	 		$this->data['follow_url'] 		= base_url().'api/relationships/follow/'.$this->user->user_id;
	 		$this->data['message_url'] 		= base_url().'api/message/send/'.$this->user->user_id;
			
			// Social Connections
			$this->data['connections']		= $this->social_auth->get_connections_user($this->user->user_id);
						
			// Sidebar
			$this->data['sidebar_profile'] = $this->load->view(config_item('site_theme').'/partials/sidebar_profile.php', $this->data, true);			
		}
		else
		{
			redirect(404);
		}
    }

 	function index()
 	{ 		
 		$this->data['page_title'] = $this->data['name']."'s profile";
 		
		// Timeline 		
		$timeline 							= $this->social_igniter->get_timeline_user($this->user->user_id, 8);
		$timeline_view 						= NULL;		
				 			
		if (!empty($timeline))
		{
			foreach ($timeline as $activity)
			{			
				// Item
				$this->data['item_id']				= $activity->activity_id;
				$this->data['item_type']			= item_type_class($activity->type);
				
				// Contributor
				$this->data['item_user_id']			= $activity->user_id;
				$this->data['item_avatar']			= $this->social_igniter->profile_image($activity->user_id, $activity->image, $activity->email);
				$this->data['item_contributor']		= $activity->name;
				$this->data['item_profile']			= base_url().'profile/'.$activity->username;
				
				// Activity
				$this->data['item_content']			= $this->social_igniter->render_item($activity);
				$this->data['item_content_id']		= $activity->content_id;
				$this->data['item_date']			= format_datetime(config_item('home_date_style'), $activity->created_at);			

		 		// Actions
			 	$this->data['item_comment']			= base_url().'comment/item/'.$activity->activity_id;
			 	$this->data['item_comment_avatar']	= $this->data['logged_image'];
			 	
			 	$this->data['item_can_modify']		= $this->social_tools->has_access_to_modify($activity->type, $activity->activity_id);
				$this->data['item_edit']			= base_url().'home/'.$activity->module.'/manage/'.$activity->content_id;
				$this->data['item_delete']			= base_url().'status/delete/'.$activity->activity_id;

				// View
				$timeline_view .= $this->load->view(config_item('site_theme').'/partials/user_timeline.php', $this->data, true);
	 		}
	 	}
	 	else
	 	{
	 		$timeline_view = '<li>Nothing to show from anyone!</li>';
 		}

		// Final Output
		$this->data['timeline_view'] 	= $timeline_view; 		
 		
		$this->render('profile');
 	}
 	
 	function image()
 	{
 		$this->data['page_title'] = $this->data['name']."'s profile picture";	

 		$this->render('profile');
 	}
 	
 
}