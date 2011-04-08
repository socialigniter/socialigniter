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
 		$this->db->select('content.*, users.username, users.gravatar, users.name, users.image');
 		$this->db->from('content');  
  		$this->db->join('users', 'users.user_id = content.user_id');		  
 		$this->db->where('content_id', $content_id);
	 	$this->db->where('content.status !=', 'D');
		$this->db->limit(1);    
 		$result = $this->db->get()->row();	
 		return $result;
    }

    function get_content_recent($site_id, $type, $limit)
    {    		
 		$this->db->select('content.*, users.username, users.gravatar, users.name, users.image');
 		$this->db->from('content');  
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
	 	
	 	$this->db->where('content.status !=', 'D');
		$this->db->limit($limit);
		$this->db->order_by('created_at', 'desc');
 		$result = $this->db->get();	
 		return $result->result();
    }
    
    function get_content_view($parameter, $value, $status, $limit)
    {
 		if (in_array($parameter, array('site_id','parent_id','category_id', 'module','type','user_id')))
    	{
	 		$this->db->select('content.*, users.username, users.gravatar, users.name, users.image');
	 		$this->db->from('content');
 			$this->db->join('users', 'users.user_id = content.user_id');
	 		$this->db->where('content.'.$parameter, $value);
	 		
	 		if ($status == 'all')
	 		{
		 		$this->db->where('content.status !=', 'D');	 		
	 		}
	 		elseif ($status == 'saved')
	 		{
		 		$this->db->where('content.status', 'S');
				$this->db->where('content.approval', 'Y');
				$this->db->limit($limit);
	 		}
	 		elseif ($status == 'awaiting')
	 		{
				$this->db->where('content.approval', 'N');
				$this->db->limit($limit);	 		 
	 		}
	 		else
	 		{
		 		$this->db->where('content.status', 'P');
				$this->db->where('content.approval', 'Y');
				$this->db->limit($limit);
	 		}	
	 		
	 		$this->db->order_by('created_at', 'desc');
	 		$result = $this->db->get();	
	 		return $result->result();	      
		}
		else
		{
			return FALSE;
		}
    }

    function get_content_new_count($module)
    {
 		$this->db->from('content')->where(array('module' => $module, 'viewed' => 'N', 'approval !=' => 'D'));
 		return $this->db->count_all_results();
    }

    function get_content_title_url($type, $title_url)
    {
 		$this->db->select('content.*, users.username, users.gravatar, users.name, users.image');
 		$this->db->from('content');
		$this->db->join('users', 'users.user_id = content.user_id');
 		$this->db->where('content.type', $type);
 		$this->db->where('content.title_url', $title_url);
 		$this->db->where('content.status !=', 'D');
 		$this->db->order_by('created_at', 'desc');
		$this->db->limit(1);	 		 
	 	$result = $this->db->get()->row();	
	 	return $result; 
    }       

    function get_content_user($content_id)
    {
 		$this->db->select('content.*, users.username, users.gravatar, users.name, users.image');
 		$this->db->from('content');  
  		$this->db->join('users', 'users.user_id = content.user_id');		  
 		$this->db->where('content_id', $content_id);
	 	$this->db->where('content.status !=', 'D');
		$this->db->limit(1);    
 		$result = $this->db->get()->row();	
 		return $result;      
    }

    
    function get_content_category_count($category_id)
    {
 		$this->db->select('category_id');
 		$this->db->from('content');
 		$this->db->where('category_id', $category_id);
	 	$this->db->where('content.status !=', 'D');
 		return $this->db->count_all_results();
    }
    
    function add_content($content_data)
    {
 		$content_data = array(
			'site_id' 	 		=> $content_data['site_id'],
			'parent_id'			=> $content_data['parent_id'],
			'category_id'		=> $content_data['category_id'],
			'module'			=> $content_data['module'],
			'type'				=> $content_data['type'],
			'source'			=> $content_data['source'],
			'order'				=> $content_data['order'],
			'user_id'			=> $content_data['user_id'],
			'title'				=> $content_data['title'],
			'title_url'			=> $content_data['title_url'],
			'content'			=> $content_data['content'],
			'details'			=> $content_data['details'],
			'access'			=> $content_data['access'],
			'comments_allow'  	=> $content_data['comments_allow'],
			'comments_count'  	=> 0,
			'geo_lat'			=> $content_data['geo_lat'],
			'geo_long'			=> $content_data['geo_long'],
			'viewed'			=> $content_data['viewed'],
			'approval'			=> $content_data['approval'],
			'status'			=> $content_data['status'],
			'created_at' 		=> unix_to_mysql(now()),
			'updated_at' 		=> unix_to_mysql(now())
		);
		
		$insert = $this->db->insert('content', $content_data);
		
		if ($content_id = $this->db->insert_id())
		{
			return $content_id;	
    	}
    	
    	return FALSE;
    }

    function update_content($content_data)
    {
 		$content_data['updated_at'] = unix_to_mysql(now());
 		
		$this->db->where('content_id', $content_data['content_id']);
		$this->db->update('content', $content_data);
		return $this->db->get_where('content', array('content_id' => $content_data['content_id']))->row();		
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
    
	/* The 'content_meta' Table */
    function get_meta($content_meta_id)
    {
 		$this->db->select('*');
 		$this->db->from('content_meta');  
 		$this->db->where('content_meta_id', $content_meta_id);
		$this->db->limit(1);
 		$result = $this->db->get()->row();	
 		return $result;
    }
    
    function get_meta_content($content_id)
    {    		
 		$this->db->select('*');
 		$this->db->from('content_meta');  
 		$this->db->where('content_id', $content_id);
 		$result = $this->db->get();
 		return $result->result();
    } 

    function get_meta_content_meta($content_id, $meta)
    {    		
 		$this->db->select('*');
 		$this->db->from('content_meta');  
 		$this->db->where(array('content_id' => $content_id, 'meta' => $meta));
 		$result = $this->db->get()->row();	
 		return $result;
    } 	
	
    function add_meta($site_id, $content_id, $meta_data)
    {
    	$content_meta_id = array();
    
    	foreach ($meta_data as $meta => $value)
    	{
	    	$insert_data = array(
	    		'site_id'		=> $site_id,
	    		'content_id'	=> $content_id,
	    		'meta'			=> $meta,
	    		'value'			=> $value,
		 		'created_at' 	=> unix_to_mysql(now()),
				'updated_at' 	=> unix_to_mysql(now())
	    	);
			
			$this->db->insert('content_meta', $insert_data);
			
			$content_meta_id[] = $this->db->insert_id();
		}	
		
		if ($content_meta_id)
		{
			return $content_meta_id;	
    	}
    	
    	return FALSE;
    }
    
    function update_meta($content_meta_id, $update_data)
    {
		$update_data['updated_at'] = unix_to_mysql(now());
    
		$this->db->where('content_meta_id', $content_meta_id);
		$this->db->update('content_meta', $update_data);
		return TRUE;
    }

    function delete_meta($content_meta_id)
    {
    	$this->db->where('content_meta_id', $content_meta_id);
    	$this->db->delete('content_meta'); 
		return TRUE;
    }
    
}