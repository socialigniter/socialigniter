<?php

class Upload_model extends CI_Model {
    
    function __construct()
    {
        parent::__construct();
    }
    
    function verify_upload($consumer_key, $file_hash)
    {
 		$this->db->select('*');
 		$this->db->from('uploads');  
 		$this->db->where('consumer_key', $consumer_key);
	 	$this->db->where('file_hash', $file_hash);
		$this->db->limit(1);    
 		$result = $this->db->get()->row();	
 		return $result;
    }

    function add_upload($activity_info, $activity_data)
    {
		if (array_key_exists('content_id', $activity_info)) $content_id = $activity_info['content_id'];
		else $content_id = 0;

 		$insert_data = array(
			'consumer_key' 	 	=> $activity_info['consumer_key'],
			'file_hash'			=> $activity_info['file_hash'],
			'status'			=> 'P',
			'uploaded_at' 		=> unix_to_mysql(now())
		);
		
		$this->db->insert('uploads', $insert_data);
		$activity_id = $this->db->insert_id();
		
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