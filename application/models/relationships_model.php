<?php

class Relationships_model extends CI_Model
{    
    function __construct()
    {
        parent::__construct();
    }
    
    function check_relationship_exists($relationship_data)
    {
 		$this->db->select('*');
 		$this->db->from('relationships');
 		$this->db->where($relationship_data); 
 		$count = $this->db->count_all_results();
 		
 		if ($count >= 1)
 		{
 			return 'dogs';
 		}
 		else
 		{
 			return 'catss';
 		}
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
    
    function add_relationship($relationship_data)
    {
 		$relationship_data['created_at']	= unix_to_mysql(now()); 			
		$this->db->insert('relationships', $relationship_data);
		$relationship_id = $this->db->insert_id();
		return $this->db->get_where('relationships', array('relationship_id' => $relationship_id))->row();	
    } 

    function update_relationship($relationship_id, $data)
    {
		$this->db->where('relationship_id', $relationship_id);
		$this->db->update('relationships', array('data' => $data));        
    }
    
}