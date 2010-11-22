<?php

class Sites_model extends CI_Model {
    
	function __construct()
	{
        
    }
    
 	function get_site()
 	{
		if ($this->config->item('site_type') == 'default')
		{
	 		$where = array('type' => 'default');
		}
		else
		{
			$where = array('type' => 'additional', 'site_id' => $this->ci->config->item('site_id'));
		}
 		
		return $this->db->select('*')->where($where)->limit(1)->get('sites')->row();	
 	}   

}