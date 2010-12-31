<?php
class Categories_model extends CI_Model {
    
    function __construct()
    {
        parent::__construct();
    }
    
    function get_categories()
    {
 		$this->db->select('*');
 		$this->db->from('categories');    
 		$this->db->order_by('created_at', 'desc'); 
 		$result = $this->db->get();	
 		return $result->result();	      
    }

    function get_categories_view($parameter, $value)
    {
    	if (in_array($parameter, array('parent_id','site_id','module','type','category_url')))
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

    function get_category_default_user($parameter, $value, $user_id)
    {
    	if (in_array($parameter, array('parent_id','site_id','module','type','category_url')))
    	{
	 		$this->db->select('*');
	 		$this->db->from('categories'); 
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
			'permission'	=> $category_data['permission'],
			'module'		=> $category_data['module'],
			'type'			=> $category_data['type'],
			'category'  	=> $category_data['category'],
			'category_url'  => $category_data['category_url'],
			'description'	=> $category_data['description'],
			'created_at' 	=> unix_to_mysql(now())
		);	
		$insert 		= $this->db->insert('categories', $data);
		$category_id 	= $this->db->insert_id();
		return $this->db->get_where('categories', array('category_id' => $category_id))->row();	
    }

}