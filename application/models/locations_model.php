<?php
class Locations_model extends CI_Model
{
    
    function __construct()
    {
        parent::__construct();
    }
    
    function get_location($key, $value)
    {
 		$this->db->select('*');
 		$this->db->from('locations');
 		$this->db->where($key, $value);
		$this->db->limit(1);    
 		return $this->db->get()->row();	
    }
    
    function get_locations($key, $value)
    {
 		$this->db->select('*');
 		$this->db->from('locations');
 		$this->db->where($key, $value);
 		$this->db->order_by('name', 'asc'); 
 		$result = $this->db->get();	
 		return $result->result();
    }
    
    function get_locations_near($lat, $long, $day)
    {
		$sql = 'SELECT location_id, address, locality, region, postal, price, 
				((latitude - '.$lat.')*(latitude - '.$lat.')+(longitude - '.$long.')*(longitude - '.$long.')) distance
				FROM locations
				WHERE available = 1
				AND is_'.$day.' = 1
				AND occupied = 0
				ORDER BY distance ASC
				LIMIT 0,10';

		$query = $this->db->query($sql);	
				
		if($query->num_rows() > 0)
		{
			foreach ($query->result() as $row)
			{					
				$result[] = $row;
			}
			
			return $result;
		}     
    
    }
    
    function add_location($location_data, $site_id)
    {
 		$data = array(
 			'site_id'		=> $site_id,
			'content_id'	=> $location_data['content_id'],
			'address'	  	=> $location_data['address'],
			'district'  	=> $location_data['district'],
			'locality'  	=> $location_data['locality'],
			'region'  	 	=> $location_data['region'],
			'country'  	 	=> $location_data['country'],
			'postal'  	 	=> $location_data['postal'],
			'latitude'		=> $location_data['latitude'],
			'longitude'		=> $location_data['longitude'],
       		'details'		=> $location_data['details']
		);
		$insert 		= $this->db->insert('locations', $data);
		$location_id 	= $this->db->insert_id();
		return $this->db->get_where('locations', array('location_id' => $location_id))->row();	
    }   

    function update_location($location_id, $data)
    {
		$this->db->where('location_id', $location_id);
		$this->db->update('locations', array('data' => $data));        
    }
    
}