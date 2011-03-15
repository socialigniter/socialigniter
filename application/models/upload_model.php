<?php

class Upload_model extends CI_Model {
    
    function __construct()
    {
        parent::__construct();
    }
    
    function check_upload_hash($consumer_key, $file_hash)
    {
		$this->db->select('*');
		$this->db->from('uploads');
		$this->db->where('consumer_key', $consumer_key);
		$this->db->where('file_hash', $file_hash);
		$this->db->limit(1);    
		$result = $this->db->get()->row();	
        
		if ($result)
		{
		    return $result;
		}    
    
 		return FALSE;
    }
    
    function get_upload($upload_id)
    {
		$this->db->select('*');
 		$this->db->from('uploads');
		$this->db->where('upload_id', $upload_id);
		$this->db->limit(1);    
 		$result = $this->db->get()->row();	
 		return $result;    
    }
       
    function add_upload($upload_data)
    {
 		$upload_data['status']		= 'P';
		$upload_data['uploaded_at'] = unix_to_mysql(now());
		
		$this->db->insert('uploads', $upload_data);
		$upload_id = $this->db->insert_id();
		
		if ($upload_id)
		{
			return $upload_id;
		}
		
		return FALSE;
    }
    
    function delete_upload($upload_id)
    {
    	$this->db->where('upload_id', $upload_id);
		$this->db->update('uploads', array('status' => 'D'));
		return TRUE;
    }
    
}