<?php defined('BASEPATH') OR exit('No direct script access allowed');
/* 
 * Relationships API : Core : Social-Igniter
 *
 */
class Relationships extends Oauth_Controller
{
    function __construct()
    {
        parent::__construct();      
	}
	
    function followers_get()
    {
    	$followers = $this->relantionships_model->get_relationships_user('follows', $this->get('id'));
    	
        if($followers)
        {
            $message = array('status' => 'error', 'message' => 'User has have followers', 'data' => $followers);
        }
        else
        {
            $message = array('status' => 'error', 'message' => 'User does not have followers');
        }

        $this->response($message, 200);        
    }

    function follow_authd_post()
    {   	
		if ($this->input->post('site_id')) $site_id = $this->input->post('site_id');
		else $site_id = config_item('site_id');
	
    	$follow_data = array(
			'site_id'	=> $site_id,		
    		'owner_id'	=> $this->oauth_user_id,
    		'user_id'	=> $this->get('id'),
			'module'	=> $this->input->post('module'),
			'type'		=> 'follow',
    	);
 		
 		$exists = $this->social_tools->check_relationship_exists($follow_data);
       	
       	if ($exists)
       	{
			if ($exists->status == 'Y')
			{
	        	$message = array('status' => 'error', 'message' => 'You already follow this person');
			}
			elseif ($exists->status == 'D')
			{
				$follow = $this->social_tools->update_relationship($exists->relationship_id, array('status' => 'Y'));
				
				$message = array('status' => 'success', 'message' => 'You now follow that user', 'data' => $follow);
			}
		}
		else
		{
	    	$user = $this->social_auth->get_user('user_id', $this->get('id'));
	    	
			if ($user->privacy) $follow_data['status'] = 'N';
			else $follow_data['status'] = 'Y';
       	    
		    $follow = $this->social_tools->add_relationship($follow_data);

			if ($follow)
			{
	        	$message = array('status' => 'success', 'message' => 'User was successfully followed', 'data' => $follow);
	        }
	        else
	        {
		        $message = array('status' => 'error', 'message' => 'Oops unable to follow user');
	        }
		}	

        $this->response($message, 200);
    }

	// For decentralized goodness!!!
    function follow_remote_get()
    {   	
		$webfinger = $this->get('webfinger_id');

		if ($webfinger)
		{
			$remote_values		= explode('@', $webfinger);
			$remote_username	= $remote_values[0];
			$remote_site		= $remote_values[1];		
			$check_site 		= $this->social_igniter->get_site_view_row('url', $remote_site);
			
			// Then check for webfinger
			if ($user = $this->social_auth->get_user('email', $webfinger))
			{	
				$user_id = $user->user_id;
				$site_id = $check_site->site_id;
			}
			else
			{				
				// GOODIE Site Exists
				if ($check_site)
				{
					$site_id = $check_site->site_id;			
				}
				else
				{
					$site_data = array(
						'url'		=> $remote_site,
						'module'	=> 'site',
						'type'		=> 'remote',
						'title'		=> '',
						'favicon'	=> ''
					);
				
					$site_id = $this->social_igniter->add_site($site_data);	
				}
								
				// Check Connection
				if ($check_connection = $this->social_auth->check_connection_username($remote_username, $site_id))
				{
					$user_id = $check_connection->user_id;
				}
				else
				{
			    	$additional_data = array(
						'name' 	=> 'King King',
						'image' => ''
			    	);
			    			       			      				
			    	// Register User
			  		$user_id = $this->social_auth->social_register($remote_username, $webfinger, $additional_data);
			  		
			   		$connection_data = array(
			   			'site_id'				=> $site_id,
			   			'user_id'				=> $user_id,
			   			'module'				=> 'site',
			   			'type'					=> 'primary',
			   			'connection_user_id'	=> '',
			   			'connection_username'	=> $remote_username,
			   			'auth_one'				=> '',
			   			'auth_two'				=> ''
			   		);
			   							
					// Add Connection
					$connection = $this->social_auth->add_connection($connection_data);			  		
				}
			}		
	
	    	$follow_data = array(
				'site_id'	=> $site_id,		
	    		'owner_id'	=> 1,//$this->oauth_user_id,
	    		'user_id'	=> $user_id,
				'module'	=> 'users',
				'type'		=> 'follow',
				'status'	=> 'Y'
	    	);
	 		
	 		$exists = $this->social_tools->check_relationship_exists($follow_data);
	       	
	       	if ($exists)
	       	{
				if ($exists->status == 'Y')
				{
		        	$message = array('status' => 'error', 'message' => 'You already follow this person');
				}
				elseif ($exists->status == 'D')
				{
					$follow = $this->social_tools->update_relationship($exists->relationship_id, array('status' => 'Y'));
					
					$message = array('status' => 'success', 'message' => 'You now follow that user', 'data' => $follow);
				}
				else
				{
					$follow = $this->social_tools->add_relationship($follow_data);

					$message = array('status' => 'success', 'message' => 'You now follow that user', 'data' => $follow);
				}
			}
			else
			{	
				if ($follow = $this->social_tools->add_relationship($follow_data))
				{
		        	$message = array('status' => 'success', 'message' => 'User was successfully followed', 'data' => $follow);
		        }
		        else
		        {
			        $message = array('status' => 'error', 'message' => 'Oops unable to follow user');
		        }
			}
		}
        else
        {
	        $message = array('status' => 'error', 'message' => 'Oops, no web finger id was specified');
        }

        $this->response($message, 200);
    }
    

    function unfollow_authd_post()
    {
		if ($this->input->post('site_id')) $site_id = $this->input->post('site_id');
		else $site_id = config_item('site_id');
	
    	$follow_data = array(
			'site_id'	=> $site_id,		
    		'owner_id'	=> $this->oauth_user_id,
    		'user_id'	=> $this->get('id'),
			'module'	=> $this->input->post('module'),
			'type'		=> 'follow',
			'status'	=> 'Y'
    	);
 		
 		$exists = $this->social_tools->check_relationship_exists($follow_data);
       	
       	if ($exists)
       	{
		    $follow = $this->social_tools->update_relationship($exists->relationship_id, array('status' => 'D'));

			if ($follow)
			{
	        	$message = array('status' => 'success', 'message' => 'User was unfollowed');
	        }
	        else
	        {
		        $message = array('status' => 'error', 'message' => 'Oops unable to unfollow user');
	        }
		}
		else
		{
	        $message = array('status' => 'error', 'message' => 'You do not follow that user');
		}

        $this->response($message, 200);     
    }

}