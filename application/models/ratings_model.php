<?php

class Ratings_model extends CI_Model {
    
    function __construct()
    {
        parent::__construct();
    }
    
    function get_ratings($content_id)
    {
 		$this->db->select('ratings.*, users.username, users.gravatar, users.name, users.image');
 		$this->db->from('ratings');    
 		$this->db->join('users', 'users.user_id = ratings.user_id'); 				
		$this->db->where('ratings.content_id', $content_id);
 		$this->db->order_by('created_at', 'desc'); 
 		$result = $this->db->get();	
 		return $result->result();	      
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
    
    function add_rating($site_id, $data)
    {
 		$data = array(
			'site_id' 	 			=> $site_id,
			'user_id' 	 			=> $data['user_id'],
			'content_id'			=> $data['content_id'],
			'type'  	 			=> $data['type'],
			'rating'  	 			=> $data['rating'],
			'created_at' 			=> unix_to_mysql(now())
		);	
		$insert 	= $this->db->insert('ratings', $data);
		$rating_id 	= $this->db->insert_id();
		return $this->db->get_where('ratings', array('rating_id' => $rating_id))->row();	
    }   

    function update_rating($data_id, $data)
    {
		$this->db->where('rating_id', $data_id);
		$this->db->update('ratings', array('data' => $data));        
    }
    
}