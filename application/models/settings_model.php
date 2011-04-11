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

    function get_setting($settings_id)
    {
 		$this->db->select('*');
 		$this->db->from('settings');
		$this->db->where('settings_id', $settings_id);
		$this->db->limit(1);    
 		$result = $this->db->get()->row();	
 		return $result; 
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
    
    function add_setting($setting_data)
    {		
		$insert = $this->db->insert('settings', $setting_data);
		return $this->db->get_where('settings', array('settings_id' => $this->db->insert_id()))->row();	
    }   

    function update_setting($setting_id, $update_data)
    {
		$this->db->where('settings_id', $setting_id);
		return $this->db->update('settings', $update_data);
    }
    
	function delete_setting($settings_id)
	{
		return $this->db->delete('settings', array('settings_id' => $settings_id));
	}    
}