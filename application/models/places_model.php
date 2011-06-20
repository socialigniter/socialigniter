<?php
class Places_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function get_place($parameter, $value)
    {
 		$this->db->select('content.*, places.*, users.username, users.gravatar, users.name, users.image');    
 		$this->db->from('content');
  		$this->db->join('users', 'users.user_id = content.user_id');		  
 		$this->db->join('places', 'places.content_id = content.content_id');
 		$this->db->where('content.'.$parameter, $value);
		$this->db->limit(1);    
 		return $this->db->get()->row();	
    }
    
    function get_places_view($parameter, $value, $status, $limit)
    {
 		$this->db->select('content.*, places.*, users.username, users.gravatar, users.name, users.image');    
 		$this->db->from('content');
  		$this->db->join('users', 'users.user_id = content.user_id');		  
 		$this->db->join('places', 'places.content_id = content.content_id');
 		$this->db->where('content.'.$parameter, $value);

 		if ($status == 'all')
 		{
	 		$this->db->where('content.status !=', 'D');
			$this->db->limit($limit);	 		 		
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
 		elseif ($status == 'deleted')
 		{
			$this->db->where('content.status', 'D');
			$this->db->limit($limit);	 		 
 		}
 		elseif ($status == 'new')
 		{
			$this->db->where('content.viewed', 'N');
			$this->db->limit($limit);	 		 
 		}
 		else
 		{
	 		$this->db->where('content.status', 'P');
			$this->db->where('content.approval', 'Y');
			$this->db->limit($limit);
 		}	

 		$result = $this->db->get();	
 		return $result->result();
    }
    
    function get_places_near($lat, $long)
    {
		$sql = 'SELECT place_id, address, locality, region, postal, price, 
				((latitude - '.$lat.')*(latitude - '.$lat.')+(longitude - '.$long.')*(longitude - '.$long.')) distance
				FROM places
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
    
    function add_place($places_data)
    {
	    $insert = $this->db->insert('places', $places_data);
    
		if ($insert)
		{    	
    		$place_data['place_id'] = $this->db->insert_id();
    	
    		return $place_data;
    	}
    	else
    	{
    		return FALSE;
    	}
    }

    function update_place($place_data)
    {
		$this->db->where('content_id', $place_data['content_id']);
		$this->db->update('places', $place_data);        
    }
    
}