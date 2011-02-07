<?php

class Relationships_model extends CI_Model {
    
    function __construct()
    {
        parent::__construct();
    }
    
    function get_relationships_user($user_id)
    {
 		$this->db->select('relationships.*, users.username, users.email, users_meta.name, users_meta.image');
 		$this->db->from('relationships');
 		$this->db->join('users', 'users.user_id = relationships.owner_id');		
 		$this->db->join('users_meta', 'users_meta.user_id = relationships.owner_id');
 		$this->db->where('owner_id', $user_id);
 		$result = $this->db->get();	
 		return $result->result();	      
    }
    
    function add_data($user_id, $status_data)
    {
 		$data = array(
			'user_id' 	 			=> $user_id,
			'source'				=> $status_data['source'],
			'text'  	 			=> $status_data['text'],
			'lat'		 			=> $status_data['lat'],
			'long'					=> $status_data['long'],
			'created_at' 			=> unix_to_mysql(now())
		);	
		$insert 	= $this->db->insert('status', $data);
		$status_id 	= $this->db->insert_id();
		return $this->db->get_where('status', array('status_id' => $status_id))->row();	
    }   

    function update_data($data_id, $data)
    {
		$this->db->where('data_id', $data_id);
		$this->db->update('table', array('data' => $data));        
    }
    
}