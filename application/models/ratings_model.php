<?php

class Ratings_model extends CI_Model {
    
    function __construct()
    {
        parent::__construct();
    }
    
    function get_ratings($content_id)
    {
 		$this->db->select('*');
 		$this->db->from('ratings');    
		$this->db->where('content_id', $content_id);
 		$this->db->order_by('created_at', 'desc'); 
 		$result = $this->db->get();	
 		return $result->result();	      
    }
    
    function get_ratings_view($parameter, $value)
    {
    	if (in_array($parameter, array('site_id', 'user_id', 'content_id', 'type', 'rating', 'ip_address')))
    	{
	 		$this->db->select('*');
	 		$this->db->from('ratings');
	 		$this->db->where($parameter, $value);
	 		$this->db->order_by('created_at', 'desc');	 		
	 		$result = $this->db->get();
	 		return $result->result();
		}
		else
		{
			return FALSE;
		}
    }    
    
	function get_ratings_likes_user($user_id)
    {
 		$this->db->select('ratings.*, users.username, users.gravatar, users.name, users.image');
 		$this->db->from('ratings');    
 		$this->db->join('users', 'users.user_id = ratings.user_id'); 				
		$this->db->where('ratings.user_id', $user_id);
 		$this->db->order_by('created_at', 'desc'); 
 		$result = $this->db->get();	
 		return $result->result();    	
    }
    
    function check_rating($rating_data)
    {
 		$this->db->select('*');
 		$this->db->from('ratings');    
		$this->db->where($rating_data);
 		$result = $this->db->get()->row();
 		return $result;	      
    }
        
    function add_rating($rating_data)
    {
 		$rating_data['created_at'] = unix_to_mysql(now());
		$this->db->insert('ratings', $rating_data);
		$rating_data['rating_id'] = $this->db->insert_id();
		return $rating_data;	
    }
}