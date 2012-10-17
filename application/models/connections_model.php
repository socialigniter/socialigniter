<?php  if  ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Connections Model
 * 
 * A model that deals with storing credentials from numerous different social sites & feeds
 * 
 * @author Brennan Novak <contact@social-igniter.com> @brennannovak
 * @package Social Igniter\Models
 * @link http://github.com/socialigniter
 * @created 2012-27-03
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
	
	/**
	 * Check Connection Auth
	 * 
	 * @param string $module The Module to check the connecton on
	 * @todo Need help documenting this = WTF are auth_one and auth_two?!
	 */
 	function check_connection_auth($module, $auth_one, $auth_two)
 	{ 		
 		$this->db->select('*');
 		$this->db->from('connections');    
 		$this->db->where(array('module' => $module, 'auth_one' => $auth_one, 'auth_two' => $auth_two));
 		$this->db->limit(1);
 		$result = $this->db->get()->row(); 		
		return $result;
 	}
	
	/**
	 * Check Connection ID
	 * 
	 * @param int $conenction_id The ID of the connection to get
	 * @return array The connection
	 */
 	function check_connection_id($connection_id)
 	{
 		$where = array('connection_id' => $connection_id);
		return $this->db->select('*')->where($where)->limit(1)->get('connections')->row();	
 	}
	
	/**
	 * Check Connection User
	 * 
	 * @param int $user_id The user ID to check
	 * @todo Need help documenting this
	 */
 	function check_connection_user($user_id, $module, $type)
 	{
 		$where = array('user_id' => $user_id, 'module' => $module, 'type' => $type);
		$result = $this->db->select('*')->where($where)->limit(1)->get('connections')->row();
		return $result;
 	}
	
	/**
	 * Check Connection User ID
	 * 
	 * @param int $user_id The user ID to check
	 * @todo Need help documenting this
	 */
 	function check_connection_user_id($connection_user_id, $module)
 	{
 		$where = array('connection_user_id' => $connection_user_id, 'module' => $module);
		return $this->db->select('*')->where($where)->limit(1)->get('connections')->row();	
 	}
	
	/**
	 * Check Connection Username
	 * 
	 * @param int $user_id The user ID to check
	 * @todo Need help documenting this
	 */
 	function check_connection_username($connection_username, $site_id)
 	{
 		$where = array('connection_username' => $connection_username, 'site_id' => $site_id);
		return $this->db->select('*')->where($where)->limit(1)->get('connections')->row();	
 	}
 	
 	/**
	 * Get Connection
	 * 
	 * @param int $connection_id The ID of the conenction to fetch
	 * @return array An assoc. array representing the connection
	 */
 	function get_connection($connection_id)
 	{
 	 	$this->db->select('*');
 		$this->db->from('connections');    
  		$this->db->join('sites', 'sites.site_id = connections.site_id');
 		$this->db->where('connection_id', $connection_id);
 		$result = $this->db->get()->row();
 		return $result;
 	}
	
	/**
	 * Get Connections for User
	 * 
	 * @param int $user_id The user ID to get connections for
	 * @return array An array of all the connections associated with the given user
	 */
 	function get_connections_user($user_id)
	{
 		$this->db->select('*');
 		$this->db->from('connections');
  		$this->db->join('sites', 'sites.site_id = connections.site_id');		  
		$this->db->where('user_id', $user_id);
 		$result = $this->db->get();	
 		return $result->result();
	}
 	
 	/**
	 * Add Connection
	 * 
	 * @param array $connection_data The data for the connection to store
	 * @return array|bool The inserted connection or false
	 */
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
	
	/**
	 * Update Connection
	 * 
	 * @param int $connection_id The ID of the connection to update
	 * @param array $update_data Assoc array of column name => data to update it with
	 * @return bool Always returns true
	 * @todo Make this return more meaningful data
	 */
    function update_connection($connection_id, $update_data)
    {
		$update_data['updated_at'] = unix_to_mysql(now());
    
		$this->db->where('connection_id', $connection_id);
		$this->db->update('connections', $update_data);
		return TRUE;
    }	
	
	/**
	 * Delete Connection
	 * 
	 * @param int $connection_id The ID of the connection to delete
	 * @todo Make this return something
	 */
	function delete_connection($connection_id)
	{
		$this->db->delete('connections', array('connection_id' => $connection_id));
	}
}