<?php

/**
 * Sites_model — model representing sites
 * 
 * @author Brennan Novak @brennannovak
 * @package Social Igniter\Models
 */
class Sites_model extends CI_Model
{    
	function __construct()
	{
        
    }
    
 	function get_site()
 	{
		if (config_item('site_type') == 'default')
		{
	 		$where = array('type' => 'default');
		}
		else
		{
			$where = array('type' => 'additional', 'site_id' => config_item('site_id'));
		}
 		
		return $this->db->select('*')->where($where)->limit(1)->get('sites')->row();	
 	}
 	
 	function get_site_view($parameter, $value)
 	{
     	if (in_array($parameter, array('site_id','url','module','type')))
    	{    
	 		$this->db->select('*');
	 		$this->db->from('sites');
	 		$this->db->where('sites.'.$parameter, $value);
	 		$this->db->order_by('sites.title', 'desc'); 
	 		$result = $this->db->get();	
	 		return $result->result();     
		}
		else
		{
			return FALSE;
		}		
 	}
	
	/**
 	 * Get Site View Row
 	 * 
 	 * Gets a record from the sites DB
 	 * 
 	 * @param string $parameter The column to search by
 	 * @param string $value The value column with name $parameter should be
 	 * @return array|bool An array representing the fetched row or false
 	 */
 	function get_site_view_row($parameter, $value)
 	{
     	if (in_array($parameter, array('site_id','url','module','type')))
    	{    
	 		$this->db->select('*');
	 		$this->db->from('sites');
	 		$this->db->where('sites.'.$parameter, $value);
	 		$this->db->order_by('sites.title', 'desc'); 
			$this->db->limit(1);    
	 		$result = $this->db->get()->row();	
	 		return $result;    
		}
		else
		{
			return FALSE;
		}		
 	}
 	
 	/**
 	 * Add Site
 	 * 
 	 * Creates a new record in the sites DB table
 	 * 
 	 * @param array $site_data The data about the site to store
 	 * @return int|string The id of the inserted site
 	 */
 	function add_site($site_data)
 	{		
		$insert = $this->db->insert('sites', $site_data);
   		return $this->db->insert_id();
 	}

}