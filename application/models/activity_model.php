<?php

class Activity_model extends CI_Model
{    
    function __construct()
    {
        parent::__construct();
    }
    
    function get_timeline($where, $limit)
    {
 		$this->db->select('activity.*, sites.url, sites.title, sites.favicon, users.username, users.gravatar, users.name, users.image');
 		$this->db->from('activity');    
 		$this->db->join('sites', 'sites.site_id = activity.site_id');
 		$this->db->join('users', 'users.user_id = activity.user_id'); 				
    	$this->db->where($where);
	 	$this->db->where('activity.status !=', 'D');    	
 		$this->db->order_by('created_at', 'desc'); 
		$this->db->limit($limit);    
 		$result = $this->db->get();	
 		return $result->result();	      
    }
    
    function get_activity($activity_id)
    {
	 	$this->db->select('activity.*, sites.title, sites.favicon, users.username, users.gravatar, users.name, users.image');
 		$this->db->from('activity'); 
 		$this->db->join('sites', 'sites.site_id = activity.site_id');
 		$this->db->join('users', 'users.user_id = activity.user_id');  		 
 		$this->db->where('activity_id', $activity_id);
	 	$this->db->where('activity.status !=', 'D');
		$this->db->limit(1);    
 		$result = $this->db->get()->row();	
 		return $result; 
    }

    function get_activity_view($parameter, $value, $limit)
    {
    	if (in_array($parameter, array('site_id','user_id','verb', 'module','type','content_id')))
    	{    
	 		$this->db->select('activity.*, sites.title, sites.favicon, users.username, users.gravatar, users.name, users.image');
	 		$this->db->from('activity');
	 		$this->db->join('sites', 'sites.site_id = activity.site_id');
	 		$this->db->join('users', 'users.user_id = activity.user_id');
	 		$this->db->where('activity.'.$parameter, $value);
	 		$this->db->where('activity.status !=', 'D');
	 		$this->db->order_by('activity.created_at', 'desc'); 
			$this->db->limit($limit);    
	 		$result = $this->db->get();	
	 		return $result->result();     
		}
		else
		{
			return FALSE;
		}		
    }    
    
    function add_activity($activity_info, $activity_data)
    {
		if (array_key_exists('content_id', $activity_info)) $content_id = $activity_info['content_id'];
		else $content_id = 0;

 		$insert_data = array(
			'site_id' 	 		=> $activity_info['site_id'],
			'user_id' 	 		=> $activity_info['user_id'],
			'verb'				=> $activity_info['verb'],
			'module'			=> $activity_info['module'],
			'type'				=> $activity_info['type'],
			'content_id' 	 	=> $content_id,
			'data'  	 		=> json_encode($activity_data),
			'status'			=> 'P',
			'created_at' 		=> unix_to_mysql(now())
		);
		
		$this->db->insert('activity', $insert_data);
		$activity_id = $this->db->insert_id();
		
		if ($activity_id)
		{
			return $activity_id;
		}
		
		return FALSE;
    }
    
    function delete_activity($activity_id)
    {
    	$this->db->where('activity_id', $activity_id);
		$this->db->update('activity', array('status' => 'D'));
		return TRUE;
    }
}