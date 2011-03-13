<?php

class Upload_model extends CI_Model {
    
    function __construct()
    {
        parent::__construct();
    }
    
    function check_upload_hash($consumer_key, $file_hash)
    {
	   $query = $this->db->select('*')
						 ->where('consumer_key', $consumer_key)
						 ->where('file_hash', $file_hash)
						 ->limit(1)
						 ->get('uploads');

        $result = $query->row();
        
		if ($query->num_rows() !== 1)
		{
		    return FALSE;
		}    
    
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