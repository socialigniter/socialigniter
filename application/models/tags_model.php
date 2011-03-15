<?php

class Tags_model extends CI_Model {
    
    function __construct()
    {
        parent::__construct();
    }

	// Tags Table
    function get_tag($tag)
    {
 		$this->db->select('*');
 		$this->db->from('tags');    
 		$this->db->where('tag', $tag); 				
 		$result = $this->db->get()->row();	
 		return $result;	      
    }
    
    function get_tags()
    {
 		$this->db->select('tags.*, taxonomy.count');
 		$this->db->from('tags');
 		$this->db->join('taxonomy', 'taxonomy.object_id = tags.tag_id'); 		  		
 		$this->db->order_by('taxonomy.count', 'desc'); 
 		$this->db->order_by('tag', 'asc'); 
 		$result = $this->db->get();	
 		return $result->result();	      
    }    
    
 	function get_tag_total($tag)
	{
 		$this->db->select('*');
 		$this->db->from('tags_link');    
 		$this->db->join('tags', 'tags.tag_id = tags_link.tag_id');
 		$this->db->where('tags.tag', $tag); 				
 		return $this->db->count_all_results();	
	}
    
    function get_tags_content($content_id)
    {
 		$this->db->select('*');
 		$this->db->from('tags_link');    
 		$this->db->join('tags', 'tags.tag_id = tags_link.tag_id');
 		$this->db->where('tags_link.content_id', $content_id); 				
 		$this->db->order_by('tags_link.tag_link_id', 'desc'); 
 		$result = $this->db->get();	
 		return $result->result();	      
    }
    
    function add_tag($tag, $tag_url)
    {
 		$data = array(
			'tag'			=> $tag,
			'tag_url'  	 	=> $tag_url,
			'created_at' 	=> unix_to_mysql(now())			
		);	
		
		$insert = $this->db->insert('tags', $data);
		return $this->db->insert_id();
    } 

	// Tags Link Table
	function get_tags_link_content($tag_id, $content_id)
	{
 		$this->db->select('*');
 		$this->db->from('tags_link');    
 		$this->db->join('tags', 'tags.tag_id = tags_link.tag_id');
 		$this->db->where('tags.tag_id', $tag_id);
 		$this->db->where('tags_link.content_id', $content_id); 				
 		$result = $this->db->get();	
 		return $result->result();	
 	}
	
    function add_tags_link($tag_id, $content_id)
    {
 		$data = array(
			'tag_id'		=> $tag_id,
			'content_id'  	=> $content_id,
			'created_at' 	=> unix_to_mysql(now())
		);	
		$insert 		= $this->db->insert('tags_link', $data);
		$tag_link_id 	= $this->db->insert_id();
		return $this->db->get_where('tags_link', array('tag_link_id' => $tag_link_id))->row();	
    } 
           
    
}