<?php
class Home extends Dashboard_Controller
{
    function __construct()
    {
        parent::__construct();
        
        if (config_item('twitter_enabled') != 'TRUE') redirect(base_url());

		$this->load->library('tweet');
		   
		$this->data['page_title'] 	= 'Twitter';

		$this->check_connection = $this->social_auth->check_connection_user($this->session->userdata('user_id'), 'twitter', 'primary');
	}
	
	function test()
	{
		echo '<h2>Testing</h2>';
	
		$user = $this->tweet->call('get', 'direct_messages', array('count' => 100));
		
		echo 'Count: '.count($user);
		echo '<pre>';
		print_r($user);
	
	}

	function username()
	{
		//$user = $this->tweet->call('get', 'users/show', array('screen_name' => 'tylergillies'));
		$user = $this->tweet->call('get', 'users/show');
		$stuff = $this->social_auth->get_connections_user($this->session->userdata('user_id'));
		//var_dump($stuff);
		$username = $stuff[0]->connection_username;
		//var_dump($username);
		return $username;
	}
	
	function timeline()
	{
		// No Connection
		if (!$this->check_connection) redirect(base_url().'home/twitter/connect', 'refresh');

		$this->tweet->set_tokens(array('oauth_token' => $this->check_connection->auth_one, 'oauth_token_secret' => $this->check_connection->auth_two));

 		$timeline		= NULL;
		$timeline_view	= NULL;

 		// Type of Feed
		if ($this->uri->segment(3) == 'timeline')
		{
			$timeline 						= $this->tweet->call('get', 'statuses/home_timeline'); 	   
 	   		$this->data['sub_title'] 		= "Timeline";
 	   	}
		elseif ($this->uri->segment(3) == 'mentions')
		{
			$timeline 						= $this->tweet->call('get', 'statuses/mentions');
	 	    $this->data['sub_title'] 		= "@ Replies";		
		}
		elseif ($this->uri->segment(3) == 'direct_messages')
		{
			$timeline 						= $this->tweet->call('get', 'direct_messages');
	 	    $this->data['sub_title'] 		= "Direct Messages";		
		}
		elseif ($this->uri->segment(3) == 'favorites')		
		{
			$timeline 						= $this->tweet->call('get', 'favorites');
	 	    $this->data['sub_title'] 		= "Favorites";		
		}

		// Build Feed				 			
		if (!empty($timeline))
		{
			foreach ($timeline as $tweet)
			{
				// Item
				$this->data['item_id']				= $tweet->id;
				$this->data['item_type']			= 'tweet';
				
				// Contributor
				$this->data['item_user_id']			= $tweet->user->id;
				$this->data['item_avatar']			= $tweet->user->profile_image_url;
				$this->data['item_contributor']		= $tweet->user->name;
				$this->data['item_profile']			= 'http://twitter.com/'.$tweet->user->screen_name;
				
				// Activity
				$this->data['item_content']			= item_linkify($tweet->text);
				$this->data['item_content_id']		= $tweet->id;
				$this->data['item_date']			= timezone_datetime_to_elapsed($tweet->created_at);			

		 		// Actions
			 	$this->data['item_comment']			= base_url().'comment/item/'.$tweet->id;
			 	$this->data['item_comment_avatar']	= $this->data['logged_image'];
			 	
			 	$this->data['item_can_modify']		= FALSE; //$this->social_tools->has_access_to_modify('activity', $tweet, $this->session->userdata('user_id'), $this->session->userdata('user_level_id'));
				$this->data['item_edit']			= ''; //base_url().'home/'.$tweet->module.'/manage/'.$tweet->content_id;
				$this->data['item_delete']			= ''; //base_url().'api/activity/destroy/id/'.$tweet->activity_id;

				// View
				$timeline_view .= $this->load->view(config_item('dashboard_theme').'/partials/item_timeline.php', $this->data, true);
	 		}
	 	}
	 	else
	 	{
	 		$timeline_view = '<li><p>No tweets to show from anyone</p></li>';
 		}
 		
	 	$this->data['social_post'] 		= $this->social_igniter->get_social_post($this->session->userdata('user_id'), 'social_post_horizontal'); 		
		$this->data['status_updater']	= $this->load->view(config_item('dashboard_theme').'/partials/status_updater', $this->data, true);
		$this->data['timeline_view'] 	= $timeline_view;				
		$this->render();	
	}
	
	function connect()
	{
 	   	$this->data['sub_title'] = "Connect";
		$this->render();
	}
 
 	function post_to_social()
 	{
	    if ((config_item('twitter_social_post') == 'TRUE') && ($this->input->post('post_to_twitter') == 1))
    	{
			if ($this->check_connection)
			{
				$auth 	= $this->twitter->oauth($this->config->item('twitter_consumer_key'), $this->config->item('twitter_consumer_key_secret'), $check_connection->token_one, $check_connection->token_two);									
				$post	= $this->twitter->call('statuses/update', array('status' => $this->input->post('update')));
			}
    	}
	}
	
}