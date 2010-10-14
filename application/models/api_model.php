<?php

class Api_model extends CI_Model 
{
    
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
    
    function get_users()
    {        
      /*  
		$this->db->select('users.user_id, users.username, users.created_on, users.last_login, users_meta.name, users_meta.location, users_meta.image, users_meta.time_zone, users_meta.utc_offset');
		$this->db->from('users');
		$this->db->join('users_meta', 'users_meta.user_id = users.user_id');
		$this->db->where('users.active', '1');
        
		return $this->db->get();
	  */
			
		$sql = "SELECT users.user_id, users.username, users.created_on, users.last_login, users_meta.name, users_meta.location, users_meta.image, users_meta.time_zone, users_meta.utc_offset
		 		FROM users 
		 		JOIN users_meta ON users.user_id = users_meta.user_id
		 		WHERE users.active = '1'";
		$q = $this->db->query($sql);
		
		if($q->num_rows() > 0) {
			foreach ($q->result() as $row) {
				$result[] = $row;
			}
			return $result;
		}			
    }
	
	function get_user($user_id)
	{
		$sql = "SELECT users.user_id, users.username, users.created_on, users.last_login, users_meta.name, users_meta.location, users_meta.image, users_meta.time_zone, users_meta.utc_offset
		 		FROM users 
		 		JOIN users_meta ON users.user_id = users_meta.user_id
		 		WHERE users.active = '1' AND users.user_id = ?";
		$q = $this->db->query($sql, $user_id);

		if($q->num_rows() > 0) {
			foreach ($q->result() as $row) {
				return $row;
			}
		}			
	}
	
}