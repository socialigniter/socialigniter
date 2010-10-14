<?php

class Pages_model extends CI_Model {
    
    function __construct()
    {
        parent::__construct();
    }

 	function get_index_page($site_id)
 	{
		$this->db->select('*');
		$this->db->where('site_id', $site_id);
		$this->db->where('type', 'index');
		$this->db->limit(1);
		return $this->db->get('content')->row();
 	}

 	function get_page($site_id, $title_url)
 	{
		$this->db->select('*');
		$this->db->where('site_id', $site_id);
		$this->db->where('title_url', $title_url);
		$this->db->limit(1);
		return $this->db->get('content')->row();
 	}    

 	function get_page_id($content_id)
 	{
		$this->db->select('*');
		$this->db->where('content_id', $content_id);
		$this->db->limit(1);
		return $this->db->get('content')->row();
 	}  
    
    function get_pages($site_id)
    {
 		$this->db->select('*');
 		$this->db->from('content');
 		$this->db->where('site_id', $site_id);
 		$this->db->where('module', 'pages');
 		$this->db->order_by('order', 'asc'); 
 		$result = $this->db->get();	
 		return $result->result();
    }


    function get_menu($site_id)
    {    
 		$this->db->select('parent_id, type, order, title, title_url');
 		$this->db->from('content');
 		$this->db->where('site_id', $site_id);
 		$this->db->where('status', 'P');
 		$this->db->where('module', 'pages');
 		$this->db->where('type !=', 'index');
 		$this->db->order_by('order', 'asc'); 
 		$result = $this->db->get();	
 		return $result->result();
    }

    function add_page($site_id, $page_data)
    {
 		$data = array(
			'site_id' 	 			=> $site_id,
			'parent_id'				=> $page_data['parent_id'],
			'category_id'			=> $page_data['category_id'],
			'module'				=> 'core',
			'type'					=> 'page',
			'order'					=> $page_data['order'],
			'status'				=> $page_data['status'],
			'created_at' 			=> unix_to_mysql(now()),
			'updated_at' 			=> '0000-00-00 00:00:00'

		);	
		$insert 	= $this->db->insert('content', $data);
		$content_id = $this->db->insert_id();
		return $this->db->get_where('content', array('content_id' => $content_id))->row();	
    }    
    

}