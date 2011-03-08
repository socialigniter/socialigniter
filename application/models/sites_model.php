<?php

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
}