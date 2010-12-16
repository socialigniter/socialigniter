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

    function get_content_recent($site_id, $type, $limit)
    {    		
 		$this->db->select('content.*, users_meta.name, users_meta.image, users_meta.url, users.username, users.email');
 		$this->db->from('content');  
  		$this->db->join('users_meta', 'users_meta.user_id = content.user_id');		  
 		$this->db->join('users', 'users.user_id = content.user_id'); 
 		$this->db->where('site_id', $site_id);

    	if ($type == 'all')  
		{
 			$this->db->where('type !=', 'status');
		}
		else
		{
			$this->db->where('type', $type);
		}

		$this->db->limit($limit);
		$this->db->order_by('created_at', 'desc');
 		$result = $this->db->get();	
 		return $result->result();
    }

    function get_content_module($site_id, $module, $limit)
    {    		
 		$this->db->select('content.*, users_meta.name, users_meta.image, users_meta.url, users.username, users.email');
 		$this->db->from('content');  
  		$this->db->join('users_meta', 'users_meta.user_id = content.user_id');		  
 		$this->db->join('users', 'users.user_id = content.user_id'); 
 		$this->db->where('site_id', $site_id);
		$this->db->where('module', $module);
		$this->db->limit($limit);
		$this->db->order_by('created_at', 'desc');
 		$result = $this->db->get();	
 		return $result->result();
    }
    
    function get_content_view($parameter, $value)
    {
    	if (in_array($parameter, array('site_id','parent_id','category_id', 'module','type','user_id')))
    	{
	 		$this->db->select('*');
	 		$this->db->from('categories'); 
	 		$this->db->where($parameter, $value);
	 		$this->db->order_by('created_at', 'desc'); 
	 		$result = $this->db->get();	
	 		return $result->result();	      
		}
		else
		{
			return FALSE;
		}
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
    
    function add_content($content_data, $site_id)
    {
 		$content_data = array(
			'site_id' 	 		=> $site_id,
			'parent_id'			=> $content_data['parent_id'],
			'category_id'		=> $content_data['category_id'],
			'module'			=> $content_data['module'],
			'type'				=> $content_data['type'],
			'source'			=> $content_data['source'],
			'order'				=> $content_data['order'],
			'title'				=> $content_data['title'],
			'title_url'			=> $content_data['title_url'],
			'content'			=> $content_data['content'],
			'details'			=> $content_data['details'],
			'access'			=> $content_data['access'],
			'comments_allow'  	=> $content_data['comments_allow'],
			'comments_count'  	=> 0,
			'geo_lat'			=> $content_data['geo_lat'],
			'geo_long'			=> $content_data['geo_long'],
			'geo_accuracy'		=> $content_data['geo_accuracy'],
			'viewed'			=> $content_data['viewed'],
			'approval'			=> $content_data['approval'],
			'status'			=> $content_data['status'],
			'created_at' 		=> unix_to_mysql(now()),
			'updated_at' 		=> '0000-00-00 00:00:00'
		);
		
		$insert = $this->db->insert('content', $content_data);
		
		if ($content_id = $this->db->insert_id())
		{
			return $content_id;	
    	}
    	
    	return FALSE;
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