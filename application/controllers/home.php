<?php
class Home extends Dashboard_Controller 
{ 
    function __construct() 
    {
        parent::__construct();
    }
 
 	function index()
 	{	
		if ($this->uri->total_segments() == 1)
		{
		
	 	    $this->data['page_title'] 		= 'Home';	
 	    	$this->data['message']		 	= random_element($this->config->item('home_greeting'));

	 	    // Social
			$this->data['social_post']			= $this->social_igniter->get_social_post($this->session->userdata('user_id'));
			$this->data['social_checkin']		= $this->social_igniter->get_social_checkin($this->session->userdata('user_id'));
			
			// Geo
			$this->data['geo_locate']			= $this->session->userdata('geo_enabled');
			
			// Updater
			$this->data['status_update']		= '';
			$this->data['status_updater']		= $this->load->view($this->config->item('dashboard_theme').'/partials/status_updater', $this->data, true); 	    
 	    
 	    	$feed_module = NULL;
 	    }
 	    // Fix For MODULE Checking
 	    else
 	    {
 	    	
	 	    $this->data['page_title'] 		= ucwords($this->uri->segment(2));
 			$this->data['sub_title']		= 'Recent';
 
 	    	$feed_module = $this->uri->segment(2);
 	    }
 	    
		// Feed 
		$timeline 							= $this->social_igniter->get_timeline(10, $feed_module);
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
				$this->data['item_content']			= $this->social_igniter->render_item($activity->verb, $activity->type, $activity->data);
				$this->data['item_date']			= format_datetime(config_item('home_date_style'), $activity->created_at);

		 		// Actions
			 	$this->data['item_comment']			= base_url().'comment/item/'.$activity->activity_id;
				$this->data['item_delete']			= base_url().'status/delete/'.$activity->activity_id;

				// View
				$timeline_view .= $this->load->view($this->config->item('dashboard_theme').'/partials/feed_timeline.php', $this->data, true);
	 		}
	 	}
	 	else
	 	{
	 		$timeline_view = '<li>Nothing to show from anyone!</li>';
 		}

		// Final Output
		$this->data['timeline_view'] 	= $timeline_view;
		$this->render();
 	}   

 	function mentions()
 	{ 	
		$user_connections 				= $this->connections_model->get_user_connections_array($this->session->userdata('user_id'));

 	    $this->data['page_title'] 		= "@ Replies";
		$this->data['timeline'] 		= $this->status_model->get_status_timeline(18);
		$this->data['post_to_social']	= $this->social_igniter->post_to_social($this->session->userdata('user_id'), $user_connections);
		 	 	
		$this->render();
 	}

	function likes()
	{
		
	
	
	}

}