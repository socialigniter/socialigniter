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
    
    function add_activity($info, $data)
    {
 		$data = array(
			'site_id' 	 			=> $info['site_id'],
			'user_id' 	 			=> $info['user_id'],
			'verb'					=> $info['verb'],
			'module'				=> $info['module'],
			'type'					=> $info['type'],
			'data'  	 			=> $data,
			'created_at' 			=> unix_to_mysql(now())
		);
		
		$insert			= $this->db->insert('activity', $data);
		$activity_id	= $this->db->insert_id();
		return $this->db->get_where('activity', array('activity_id' => $activity_id))->row();
    }
    
    function delete_activity($activity_id)
    {
    	$this->db->where('activity_id', $activity_id);
    	$this->db->delete('activity'); 
		return TRUE;
    } 
    
}