<?php

class Taxonomy_model extends CI_Model {
    
    function __construct()
    {
        parent::__construct();
    }
    
    // Taxonomy Table
    function get_taxonomy($object_id, $taxonomy)
    {
 		$this->db->select('*');
 		$this->db->from('taxonomy');    
 		$this->db->where('object_id', $object_id); 				
 		$this->db->where('taxonomy', $taxonomy); 
 		$result = $this->db->get()->row();	
 		return $result;    	
    }
    
    function add_taxonomy($object_id, $taxonomy, $count)
    {
 		$data = array(
			'object_id'		=> $object_id,
			'taxonomy'  	=> $taxonomy,
			'count' 		=> $count
		);	
		$insert = $this->db->insert('taxonomy', $data);
		return $insert;    
    }
    
    function update_taxonomy($taxonomy_id, $count)
    {
		$this->db->where('taxonomy_id', $taxonomy_id);
		$this->db->update('taxonomy', array('count' => $count));        
    }
  
}