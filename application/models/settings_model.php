<?php

class Settings_model extends CI_Model {
    
    function __construct()
    {
        parent::__construct();
    }
    
    function get_settings($site_id, $module)
    {
    	if ($module)
    	{
    		$where = array('site_id' => $site_id, 'module' => $module);
    	}
    	else
    	{
    		$where = array('site_id' => $site_id);
    	}
    
 		$this->db->select('*');
 		$this->db->from('settings');    
		$this->db->where($where);
 		$result = $this->db->get();	
 		return $result->result();	      
    }

    function get_settings_setting($setting)
    {
 		$this->db->select('*');
 		$this->db->from('settings');
		$this->db->where('setting', $setting);
 		$result = $this->db->get();	
 		return $result->result();   
    }

    function get_settings_setting_value($setting, $value)
    {
 		$this->db->select('*');
 		$this->db->from('settings');
		$this->db->where('setting', $setting);
		$this->db->where('value', $value);
 		$result = $this->db->get();	
 		return $result->result();   
    }

    function get_settings_module($module)
    {
 		$this->db->select('*');
 		$this->db->from('settings');
		$this->db->where('module', $module);
 		$result = $this->db->get();	
 		return $result->result(); 
    }
    
    function add_setting($user_id, $status_data)
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

    function update_setting($setting_id, $update_data)
    {
		$this->db->where('settings_id', $setting_id);
		$this->db->update('settings', $update_data);
    }
    
}