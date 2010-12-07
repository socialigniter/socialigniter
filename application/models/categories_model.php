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

    function get_categories_by($parameter, $value)
    {
 		$this->db->select('*');
 		$this->db->from('categories'); 
 		$this->db->where($parameter, $value);
 		$this->db->order_by('created_at', 'desc'); 
 		$result = $this->db->get();	
 		return $result->result();	      
    }
    
    function add_category($category_data)
    {
 		$data = array(
 			'parent_id'		=> $category_data['parent_id'],
			'site_id' 	 	=> $category_data['site_id'],
			'module'		=> $category_data['module'],
			'type'			=> $category_data['type'],
			'category'  	=> $category_data['category'],
			'category_url'  => $category_data['category_url'],
			'created_at' 	=> unix_to_mysql(now())
		);	
		$insert 		= $this->db->insert('status', $data);
		$category_id 	= $this->db->insert_id();
		return $this->db->get_where('categories', array('category_id' => $category_id))->row();	
    }

}