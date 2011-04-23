<?php
class Profile extends Site_Controller
{ 
    function __construct() 
    {
        parent::__construct();

		if (!$this->uri->segment(2) || (config_item('users_profile') != 'TRUE')) redirect(base_url());	
		$timeline_view = null;
		$this->user = $this->social_auth->get_user('username', $this->uri->segment(2)); 
 	
		if($this->user)
		{
	 		$this->user_meta				= $this->social_auth->get_user_meta($this->user->user_id);		
		
			// User Data
			$this->data['user_id'] 			= $this->user->user_id;	
			$this->data['username'] 		= $this->user->username;
			$this->data['gravatar'] 		= $this->user->gravatar;
			$this->data['name'] 			= $this->user->name;
			$this->data['image'] 			= $this->user->image; 
			$this->data['created_on'] 		= $this->user->created_on;
			
			// User Meta
			$this->data['company']			= $this->social_auth->find_user_meta_value('company', $this->user_meta);
			$this->data['location']			= $this->social_auth->find_user_meta_value('location', $this->user_meta);
			$this->data['url']				= $this->social_auth->find_user_meta_value('url', $this->user_meta);
			$this->data['bio']				= $this->social_auth->find_user_meta_value('bio', $this->user_meta);

			
			// Social Connections
			$this->data['connections']		= $this->social_auth->get_connections_user($this->user->user_id);
			
			// Relationships
			$this->data['followers']		= $this->social_tools->get_relationships_followers($this->user->user_id);
			$this->data['follows']			= $this->social_tools->get_relationships_follows($this->user->user_id);

			// Links
			if ($this->social_auth->logged_in())
			{			
				if ($follow_check = $this->social_tools->check_relationship_exists(array('site_id' => config_item('site_id'), 'owner_id' => $this->session->userdata('user_id'), 'user_id' => $this->user->user_id, 'module' => 'users', 'type' => 'follow', 'status' => 'Y')))
				{
			 		$this->data['follow_word']	= 'unfollow';
				}
				else
				{
			 		$this->data['follow_word']	= 'follow';				
				}			
			}
			else
			{
			 	$this->data['follow_word']	= 'follow';			
			}

	 		$this->data['message_url'] 		= base_url().'api/message/send/id/'.$this->user->user_id;
	 		

			// Sidebar
			$this->data['sidebar_profile'] = $this->load->view(config_item('site_theme').'/partials/sidebar_profile.php', $this->data, true);	
			
			
			// Timeline 		
			$timeline 							= $this->social_igniter->get_timeline_user($this->user->user_id, 8);
			$timeline_view 						= NULL;	
			$timeline_count						= 1;
			$this->data['activities']			= array();
			$this->data['social_igniter']		= $this->social_igniter;
					 			
			if (!empty($timeline))
			{
				foreach ($timeline as $activity)
				{	
					$timeline_count++;
						
					// Item
					array_push($this->data['activities'], $activity);
					$this->data['item_id']				= $activity->activity_id;
					$this->data['item_type']			= item_type_class($activity->type);
					// Contributor
					$this->data['item_user_id']			= $activity->user_id;
					$this->data['item_avatar']			= $this->social_igniter->profile_image($activity->user_id, $activity->image, $activity->gravatar);
					$this->data['item_contributor']		= $activity->name;
					$this->data['item_profile']			= base_url().'profile/'.$activity->username;
					
					// Activity
					$this->data['item_content']			= $this->social_igniter->render_item($activity);
					$this->data['item_content_id']		= $activity->content_id;
					$this->data['item_date']			= format_datetime(config_item('home_date_style'), $activity->created_at);			

			 		// Actions
				 	$this->data['item_comment']			= base_url().'comment/item/'.$activity->activity_id;
				 	$this->data['item_comment_avatar']	= $this->data['logged_image'];


				 	$this->data['item_can_modify']		= $this->social_auth->has_access_to_modify('activity', $activity, $this->session->userdata('user_id'), $this->session->userdata('user_level_id'));			 	
					$this->data['item_edit']			= base_url().'home/'.$activity->module.'/manage/'.$activity->content_id;
					$this->data['item_delete']			= base_url().'status/delete/'.$activity->activity_id;

					// View
					$timeline_view  .= $this->load->view(config_item('site_theme').'/partials/user_timeline.php', $this->data, true);
		 		}
		 	}
		 	else
		 	{
		 		$timeline_view = '<li>Nothing to show from anyone!</li>';
	 		}		
		}
		else
		{
			redirect(404);
		}
		$this->data['timeline_view'] 	= $timeline_view; 		
 		$this->data['timeline_count']	= $timeline_count;

    }

 	function index()
 	{ 		
 		$this->data['page_title'] = $this->data['name']."'s profile";
 		


		// Final Output

 		
		$this->render('profile');
 	}

 	function feed()
 	{
 		$this->output->set_header('Content-type:application/atom+xml');
 		$this->load->view('site_default/partials/feed', $this->data);
 	}

 	function add_friend()
 	{
 		$this->data['webfinger'] = $this->uri->segment(4);
 		$this->render('profile');
 	}
 	
 	function image()
 	{
 		$this->data['page_title'] = $this->data['name']."'s profile picture";	

 		$this->render('profile');
 	} 
}