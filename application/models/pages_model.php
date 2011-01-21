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
 		$this->db->where('module', 'pages');		
		$this->db->where('details', 'index');
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
 		$this->db->select('parent_id, type, order, title, title_url, details');
 		$this->db->from('content');
 		$this->db->where('site_id', $site_id);
 		$this->db->where('status', 'P');
 		$this->db->where('module', 'pages');
 		$this->db->where('details !=', 'index');
 		$this->db->order_by('order', 'asc'); 
 		$result = $this->db->get();	
 		return $result->result();
    }

}