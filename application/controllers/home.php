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
			$this->data['social_post']		= $this->social_igniter->get_social_post($this->session->userdata('user_id'));
			$this->data['social_checkin']	= $this->social_igniter->get_social_checkin($this->session->userdata('user_id'));
			
			// Geo
			$this->data['geo_locate']		= $this->session->userdata('geo_enabled');
			
			// Updater
			$this->data['status_update']	= '';
			$this->data['status_updater']	= $this->load->view($this->config->item('dashboard_theme').'/partials/status_updater', $this->data, true); 	    
 	    
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
			    $object 	= json_decode($activity->data);
			
				// Item
				$this->data['item_id']				= $activity->activity_id;
				$this->data['item_type']			= item_type_class($activity->type);
				
				// Contributor
				$this->data['item_user_id']			= $activity->user_id;
				$this->data['item_avatar']			= $this->social_igniter->profile_image($activity->user_id, $activity->image, $activity->email);
				$this->data['item_contributor']		= $activity->name;
				$this->data['item_profile']			= base_url().'profile/'.$activity->username;
				
				// Activity
				$this->data['item_content']			= $this->social_igniter->render_item($activity->verb, $activity->type, $object);
				$this->data['item_content_id']		= $activity->content_id;
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


 	function comments()
 	{ 		
 		if ($this->uri->segment(3)) $comment_module = $this->uri->segment(3);
 		else						$comment_module = 'all';
 	
		$comments 					= $this->social_tools->get_comments($comment_module);		
		$comments_view 				= NULL;
		$this->data['feed_type']	= 'comments';
    	$this->data['item_verb']	= item_type($this->lang->line('object_types'), 'comment');
	
		if (empty($comments))
		{
			 $comments_view = '<li>No comments to show!</li>';
	 	}
	 	else
	 	{
			foreach ($comments as $item)
			{
				// Item
				$this->data['item_id']				= $item->comment_id;
				$this->data['item_type']			= item_type_class($item->type);
				$this->data['item_viewed']			= item_viewed($item->viewed);
				
				// Contributor
				$this->data['item_avatar']			= $this->social_igniter->profile_image($item->user_id, $item->image, $item->email);
				$this->data['item_contributor']		= $item->name;
				$this->data['item_profile']			= base_url().'profile/'.$item->username;

				// Activity
				$this->data['item_object']			= $item->title;
				$this->data['item_text']			= $item->comment;
				$this->data['item_date']			= human_date('SIMPLE', mysql_to_unix($item->created_at));
				$this->data['item_approval']		= $item->approval;
		
		 		// Actions
				$this->data['item_view'] 			= base_url().'comments/'.$item->type.'/view/'.$item->content_id.'/'.$item->comment_id;
				$this->data['item_reply'] 			= base_url().'comments/'.$item->type.'/reply/id/'.$item->content_id.'/'.$item->comment_id;
				$this->data['item_approve']			= base_url().'api/comments/approve/id/'.$item->comment_id;
				$this->data['item_delete']			= base_url().'api/comments/destroy/id/'.$item->comment_id;

				// Alerts
				$this->data['item_alerts']			= item_alerts($item->comment_id, $item->viewed, $item->approval, $this->data['item_approve']);

				// Load Partial For Items
				$comments_view 				   	   .= $this->load->view(config_item('dashboard_theme').'/partials/feed_comments.php', $this->data, true);
	 		}
 		}
		
		$this->data['comments_view'] = $comments_view;	
				
		$this->render();
	}

}