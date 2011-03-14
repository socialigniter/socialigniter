<?php
class Categories_model extends CI_Model
{    
    function __construct()
    {
        parent::__construct();
    }
    
    function get_categories()
    {
 		$this->db->select('categories.*, users.username, users.gravatar, users.name, users.image');
 		$this->db->from('categories');    
 		$this->db->order_by('created_at', 'desc'); 
 		$result = $this->db->get();	
 		return $result->result();	      
    }

    function get_category($category_id)
    {
 		$this->db->select('categories.*, users.username, users.gravatar, users.name, users.image');
 		$this->db->from('categories'); 
 		$this->db->join('users', 'users.user_id = categories.user_id'); 
  		$this->db->where('category_id', $category_id);
 		$result = $this->db->get()->row();
 		return $result;
    }

    function get_categories_view($parameter, $value)
    {
    	if (in_array($parameter, array('category_id','parent_id','site_id','module','type','category_url')))
    	{
	 		$this->db->select('categories.*, users.username, users.gravatar, users.name, users.image');
	 		$this->db->from('categories');
	 		$this->db->join('users', 'users.user_id = categories.user_id');
	 		$this->db->where($parameter, $value);
	 		$this->db->order_by('parent_id', 'asc');
	 		$result = $this->db->get();	
	 		return $result->result();
		}
		else
		{
			return FALSE;
		}
    }

    function get_category_title_url($type, $category_url)
    {
 		$this->db->select('categories.*, users.username, users.gravatar, users.name, users.image');
 		$this->db->from('categories'); 
 		$this->db->join('users', 'users.user_id = categories.user_id'); 
  		$this->db->where('categories.type', $type);
  		$this->db->where('categories.category_url', $category_url);
 		$result = $this->db->get()->row();
 		return $result;
    }

    function get_category_default_user($parameter, $value, $user_id)
    {
    	if (in_array($parameter, array('parent_id','site_id','module','type','category_url')))
    	{
	 		$this->db->select('*');
	 		$this->db->from('categories'); 
	 		$this->db->join('users', 'users.user_id = categories.user_id');	 		
	 		$this->db->where($parameter, $value);
	 		$this->db->where('user_id', $user_id);
	 		$this->db->order_by('created_at', 'desc'); 
	 		$result = $this->db->get();	
	 		return $result->result();	      
		}
		else
		{
			return FALSE;
		}
    }
    
    function add_category($category_data)
    {
 		$data = array(
 			'parent_id'		=> $category_data['parent_id'],
			'site_id' 	 	=> $category_data['site_id'],
			'user_id' 	 	=> $category_data['user_id'],
			'access'		=> $category_data['access'],
			'module'		=> $category_data['module'],
			'type'			=> $category_data['type'],
			'category'  	=> $category_data['category'],
			'category_url'  => $category_data['category_url'],
			'description'	=> $category_data['description'],
			'details'		=> $category_data['details'],
			'contents_count'=> 0,
			'created_at' 	=> unix_to_mysql(now()),
			'updated_at' 	=> unix_to_mysql(now())
		);	
		$insert 		= $this->db->insert('categories', $data);
		$category_id 	= $this->db->insert_id();
		return $this->db->get_where('categories', array('category_id' => $category_id))->row();	
    }
    
    function update_category($category_id, $category_data)
    {
 		$category_data['updated_at'] = unix_to_mysql(now());

		$this->db->where('category_id', $category_id);
		$this->db->update('categories', $category_data);
		
		return TRUE;
    }
    
    function update_category_contents_count($category_id, $contents_count)
    {
		$this->db->where('category_id', $category_id);
		$this->db->update('categories', array('contents_count' => $contents_count));
		return TRUE;
    }
    
    function update_category_details($category_id, $details)
    {
		$this->db->where('category_id', $category_id);
		$this->db->update('categories', array('details' => $details));
		return TRUE;
    }           

}