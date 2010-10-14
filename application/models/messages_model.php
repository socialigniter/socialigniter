<?php

class Messages_model extends CI_Model {
    
    function __construct()
    {
        parent::__construct();
    }
    
    function get_message($message_id, $direction)
    {
    	if ($direction == 'sender')
    		$join = 'messages.sender_id';
    	else
    		$join = 'messages.reciver_id';
    
 		$this->db->select('*');
 		$this->db->from('messages');    
 		$this->db->join('users', $join); 				
		$this->db->where('messages.message_id', $message_id);
 		$result = $this->db->get()->row();	
 		return $result;	      
    }
    
    function get_inbox($receiver_id)
    {
 		$this->db->select('*');
 		$this->db->from('messages');    
 		$this->db->join('users', 'messages.sender_id = users.user_id'); 				
		$this->db->where('messages.receiver_id', $receiver_id);
		$this->db->where('messages.status', 'S');
 		$result = $this->db->get();	
 		return $result->result();	      	
    }
    
    function get_inbox_new_count($receiver_id)
    {    	
 		$this->db->from('messages')->where(array('receiver_id' => $receiver_id, 'viewed' => 'N'));
 		return $this->db->count_all_results();
    }    

    function get_sent_or_drafts($sender_id, $status)
    {
 		$this->db->select('*');
 		$this->db->from('messages');    
 		$this->db->join('users', 'messages.receiver_id = users.user_id'); 				
		$this->db->where('messages.sender_id', $sender_id);
		$this->db->where('messages.status', $status);
 		$result = $this->db->get();	
 		return $result->result();	      	
    }
    
    function add_message($site_id, $message_data)
    {
 		$data = array(
 			'site_id'				=> $site_id,
			'receiver_id' 	 		=> $message_data['receiver_id'],
			'sender_id'				=> $message_data['sender_id'],
			'type'					=> $message_data['type'],
			'subject'				=> $message_data['subject'],
			'body'					=> $message_data['body'],
			'status'  	 			=> $message_data['status'],
			'sent_at'	 			=> unix_to_mysql(now())
		);	
		$insert 	= $this->db->insert('messages', $data);
		$status_id 	= $this->db->insert_id();
		return $this->db->get_where('messages', array('message_id' => $status_id))->row();	
    }
    
}