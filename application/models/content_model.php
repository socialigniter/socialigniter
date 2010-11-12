<?php

class Content_model extends CI_Model {
    
    function __construct()
    {
        parent::__construct();
    }

    function check_content_duplicate($user_id, $title, $content)
    {
 		$this->db->select('*');
 		$this->db->from('content');  
 		$this->db->where('user_id', $user_id);
 		$this->db->where('title', $title);
 		$this->db->where('content', $content);
 		$result = $this->db->count_all_results();	
 		return $result; 
    }
    
    function get_content($content_id)
    {
 		$this->db->select('*');
 		$this->db->from('content');  
 		$this->db->where('content_id', $content_id);
		$this->db->limit(1);    
 		$result = $this->db->get()->row();	
 		return $result; 
    }

    function get_content_recent($site_id, $limit)
    {
 		$this->db->select('*');
 		$this->db->from('content');  
  		$this->db->join('users_meta', 'users_meta.user_id = content.user_id');		  
 		$this->db->join('users', 'users.user_id = content.user_id'); 
 		$this->db->where('site_id', $site_id);
		$this->db->limit($limit);
		$this->db->order_by('created_at', 'desc');
 		$result = $this->db->get();	
 		return $result->result();
    }

    function get_content_user($content_id)
    {
 		$this->db->select('content.*, users_meta.name, users_meta.image, users_meta.url, users.username, users.email');
 		$this->db->from('content');  
  		$this->db->join('users_meta', 'users_meta.user_id = content.user_id');		  
 		$this->db->join('users', 'users.user_id = content.user_id'); 				
 		$this->db->where('content_id', $content_id);
		$this->db->limit(1);    
 		$result = $this->db->get()->row();	
 		return $result;      
    }
    
    function add_content($site_id, $content_data)
    {
 		$data = array(
			'site_id' 	 		=> $site_id,
			'parent_id'			=> $content_data['parent_id'],
			'category_id'		=> $content_data['category_id'],
			'module'			=> $content_data['module'],
			'type'				=> $content_data['type'],
			'source'			=> $content_data['source'],
			'order'				=> $content_data['order'],
			'user_id'			=> $content_data['user_id'],
			'title'  	 		=> $content_data['title'],
			'title_url'  	 	=> $content_data['title_url'],
			'content'			=> $content_data['content'],
			'details'			=> $content_data['details'],
			'access'			=> $content_data['access'],
			'comments_allow'	=> $content_data['comments_allow'],
			'comments_count'  	=> 0,
			'geo_lat'			=> $content_data['geo_lat'],
			'geo_long'			=> $content_data['geo_long'],
			'geo_accuracy'		=> $content_data['geo_accuracy'],
			'status'			=> $content_data['status'],
			'created_at' 		=> unix_to_mysql(now()),
			'updated_at' 		=> '0000-00-00 00:00:00'
		);
		
		$insert 	= $this->db->insert('content', $data);
		$content_id = $this->db->insert_id();
		return $this->db->get_where('content', array('content_id' => $content_id))->row();	
    }

    function update_content($content_id, $content_data)
    {
 		$data = array(
			'parent_id'			=> $content_data['parent_id'],
			'category_id'		=> $content_data['category_id'],
			'order'				=> $content_data['order'],
			'title'				=> $content_data['title'],
			'title_url'			=> $content_data['title_url'],
			'content'			=> $content_data['content'],
			'details'			=> $content_data['details'],
			'access'			=> $content_data['access'],
			'comments_allow'  	=> $content_data['comments_allow'],
			'status'			=> $content_data['status'],
			'updated_at' 		=> unix_to_mysql(now())
		);

		$this->db->where('content_id', $content_id);
		$this->db->update('content', $data);
		
		return TRUE;
    }    

    function update_content_comments_count($content_id, $comments_count)
    {
		$this->db->where('content_id', $content_id);
		$this->db->update('content', array('comments_count' => $comments_count));
		return TRUE;
    }
    
    function delete_content($content_id)
    {
    	$this->db->where('content_id', $content_id);
    	$this->db->delete('content'); 
		return TRUE;
    }    
    
}