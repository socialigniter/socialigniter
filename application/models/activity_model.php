<?php

class Activity_model extends CI_Model {

	private $lat_long;
    
    function __construct()
    {
        parent::__construct();
    }
    
    function get_timeline($limit=10, $where)
    {
 		$this->db->select('activity.*, sites.title, sites.favicon, users.username, users.email, users_meta.name, users_meta.location, users_meta.image');
 		$this->db->from('activity');    
 		$this->db->join('sites', 'sites.site_id = activity.site_id');
 		$this->db->join('users', 'users.user_id = activity.user_id'); 				
 		$this->db->join('users_meta', 'users_meta.user_id = activity.user_id'); 				    
    	$this->db->where($where); 
 		$this->db->order_by('created_at', 'desc'); 
		$this->db->limit($limit);    
 		$result = $this->db->get();	
 		return $result->result();	      
    }
    
    function get_timeline_user($user_id, $limit=8)
    {
 		$this->db->select('activity.*, sites.title, sites.favicon, users.username, users.email');
 		$this->db->from('activity');
 		$this->db->join('sites', 'sites.site_id = activity.site_id');
 		$this->db->join('users', 'users.user_id = activity.user_id');
 		$this->db->join('users_meta', 'users_meta.user_id = activity.user_id');
 		$this->db->where('activity.user_id', $user_id);
 		$this->db->order_by('activity.created_at', 'desc'); 
		$this->db->limit($limit);    
 		$result = $this->db->get();	
 		return $result->result();     
    }
    
    function get_activity($activity_id)
    {
 		$this->db->select('activity.*, sites.title, sites.favicon, users.username, users.email');
 		$this->db->from('activity'); 
 		$this->db->join('sites', 'sites.site_id = activity.site_id');
  		$this->db->join('users_meta', 'users_meta.user_id = activity.user_id');		  
 		$this->db->join('users', 'users.user_id = activity.user_id');  		 
 		$this->db->where('activity_id', $activity_id);
		$this->db->limit(1);    
 		$result = $this->db->get()->row();	
 		return $result; 
    }
    
    function add_activity($activity_data)
    {
    	$content_data = NULL;
    
    	if ($activity_data['content_id'])
    	{
	    	$content_data = array(
				'title'			=> $activity_data['title'],
				'url'			=> base_url().$activity_data['module'].'/view/'.$activity_data['content_id'],
				'description' 	=> character_limiter(strip_tags($activity_data['description'], ''), config_item('home_description_length'))
	    	);
		}
		// Make Other Conditions for Other Types Of 'data' like (friends add, joined group, etc...)
    
 		$activity_data = array(
			'site_id' 	 			=> $activity_data['site_id'],
			'user_id' 	 			=> $activity_data['user_id'],
			'verb'					=> $activity_data['verb'],
			'module'				=> $activity_data['module'],
			'type'					=> $activity_data['type'],
			'content_id' 	 		=> $activity_data['content_id'],
			'data'  	 			=> json_encode($content_data),
			'created_at' 			=> unix_to_mysql(now())
		);
		
		$insert = $this->db->insert('activity', $activity_data);
		
		if ($activity_id = $this->db->insert_id())
		{
			return $activity_id;
		}
		
		return FALSE;
    }
    
    function delete_activity($activity_id)
    {
    	$this->db->where('activity_id', $activity_id);
    	$this->db->delete('activity'); 
		return TRUE;
    } 
    
}