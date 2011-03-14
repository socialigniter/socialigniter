<?php  if  ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
* Name:		Connections Model
* 
* Author:	Brennan Novak
* 		  	contact@social-igniter.com
*         	@brennannovak
* 
* Location: http://github.com/socialigniter
* 
* Created:  03-27-2010
* 
* Description:  A model that deals with storing credentials from numerous different social sites & feeds
*/

class Connections_model extends CI_Model 
{
	private $type;
	private $user_id;
	private $token_one;
	private $token_two;
	private $connection_user_id;
	private $connection_username;
 	private $connection_password;
 
    function __construct()
    {
        parent::__construct();
    }

 	function check_connection_id($connection_id)
 	{
 		$where = array('connection_id' => $connection_id);
		return $this->db->select('*')->where($where)->limit(1)->get('connections')->row();	
 	}

 	function check_connection_user($user_id, $module, $type)
 	{
 		$where = array('user_id' => $user_id, 'module' => $module, 'type' => $type);
		$result = $this->db->select('*')->where($where)->limit(1)->get('connections')->row();
		
		if ($result)
		{
			return $result;
		}
		
		return FALSE;
 	}

 	function check_connection_user_id($connection_user_id, $module)
 	{
 		$where = array('connection_user_id' => $connection_user_id, 'module' => $module);
		return $this->db->select('*')->where($where)->limit(1)->get('connections')->row();	
 	}

 	function check_connection_auth($module, $connection_user_id)
 	{
 		$where = array('module' => $module, 'connection_user_id' => $connection_user_id);
 		 	
 		$result = $this->db->select('*')->where($where)->limit(1)->get('connections')->row(); 	
 		 		
 		if (!$result) 
 		{
 			return FALSE;
 		}
 		
		return $result;
 	}

 	function get_connection($connection_id)
 	{
 	 	$this->db->select('*');
 		$this->db->from('connections');    
  		$this->db->join('sites', 'sites.site_id = connections.site_id');
 		$this->db->where('connection_id', $connection_id);
 		$result = $this->db->get()->row();
 		return $result;
 	}

 	function get_connections_user($user_id)
	{
 		$this->db->select('*');
 		$this->db->from('connections');
  		$this->db->join('sites', 'sites.site_id = connections.site_id');		  
		$this->db->where('user_id', $user_id);
 		$result = $this->db->get();	
 		return $result->result();
	}
 	
	function add_connection($connection_data)
	{
		$connection_data['created_at'] = unix_to_mysql(now());
		$connection_data['updated_at'] = unix_to_mysql(now());
	
		if($this->db->insert('connections', $connection_data))
		{		
			$connection_id 	= $this->db->insert_id();
			return $this->db->get_where('connections', array('connection_id' => $connection_id))->row();
		}
		else
		{
			return FALSE;
		}
	}
	
    function update_connection($connection_id, $update_data)
    {
		$update_data['updated_at'] = unix_to_mysql(now());
    
		$this->db->where('connection_id', $connection_id);
		$this->db->update('connections', $update_data);
		return TRUE;
    }	

	function delete_connection($connection_id)
	{
		$this->db->delete('connections', array('connection_id' => $connection_id));
	}
}