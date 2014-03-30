<?php

/**
 * Content Model
 * 
 * A model for managing social-igniter Content
 * 
 * @author Brennan Novak @brennannovak
 * @package Social Igniter\Models
 */
class Content_model extends CI_Model
{    
    function __construct()
    {
        parent::__construct();
    }

	/* Content */
	
	/**
	 * Check Content Duplicate
	 * 
	 * Performs a WHERE lookup on the content table for WHERE $parameter=$value
	 * 
	 * If $user_id is supplied, also performs WHERE user_id=$user_id
	 * 
	 * @param string $parameter The column name to check for
	 * @param string $value The value to check for
	 * @param int $user_id The user id to check for (optional)
	 * @return array|null Content matching the parameters
	 */
    function check_content_duplicate($parameter, $value, $user_id=null)
    {
 		$this->db->select('*');
 		$this->db->from('content');  
 		$this->db->where($parameter, $value);

		if (!empty($user_id))
		{
	 		$this->db->where('user_id', $user_id);	
		}

 		$result = $this->db->get()->row();	
 		return $result; 
    }
	
	/**
	 * Check Content Multiple
	 * 
	 * @param array $value_array An assoc. array of column name => value to match
	 * @param int $user_id The id of a user to filter by (optional)
	 * @return array An array of matching rows
	 */
    function check_content_multiple($value_array, $user_id)
    {
 		$this->db->select('*');
 		$this->db->from('content');  
 		$this->db->where($value_array);

		if ($user_id)
		{
	 		$this->db->where('user_id', $user_id);	
		}

 		$result = $this->db->get()->row();	
 		return $result; 
    }
    
    /**
	 * Get Content
	 * 
	 * @param int $content_id
	 * @return object The content fetched
	 */
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
	
	/**
	 * Get Content Multiple
	 * 
	 * @todo Document properly
	 * @return array An array of content objects
	 */
    function get_content_multiple($parameter, $value_array)
    {
 		$this->db->select('content.*, users.username, users.gravatar, users.name, users.image');
 		$this->db->from('content');
  		$this->db->join('users', 'users.user_id = content.user_id');
 		$this->db->or_where_in($parameter, $value_array);	 	
	 	$this->db->where('content.status !=', 'D');
 		$result = $this->db->get();	
 		return $result->result();
    }
	
	/**
	 * Get Content Recent
	 * 
	 * @param int $site_id The id of a site to filter by
	 * @param string $type The name of a content type or 'all'
	 * @param int $limit The maximum number of rows to fetch
	 * @return array An array of content objects
	 */
    function get_content_recent($site_id, $type, $limit)
    {    		
 		  $this->db->select('content.*, users.username, users.gravatar, users.name, users.image');
 		  $this->db->from('content');  
  		$this->db->join('users', 'users.user_id = content.user_id');		  
 		  $this->db->where('site_id', $site_id);

    	if ($type == 'all'):
 			  $this->db->where('type !=', 'status');
		  else:
			  $this->db->where('type', $type);
		  endif;

	 	  $this->db->where('content.access', 'E');	 	
	 	  $this->db->where('content.status', 'P');
		  $this->db->limit($limit);
		  $this->db->order_by('content.created_at', 'desc');
 		  $result = $this->db->get();	
 		  return $result->result();
    }
    
    /**
	 * Get Content View
	 * 
	 * @param string $parameter A column name to match with $value
	 * @param string $value The valute to match
	 * @param string $status Fetch content with this status
	 * @param int $limit The maximim number of records to fetch
	 * @return array|false An array of content objects or false
	 */
    function get_content_view($parameter, $value, $status, $limit)
    {
 		if (in_array($parameter, array('site_id','parent_id','category_id', 'module','type','user_id', 'details')))
    	{
	 		$this->db->select('content.*, users.username, users.gravatar, users.name, users.image');
	 		$this->db->from('content');
 			$this->db->join('users', 'users.user_id = content.user_id');
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
	 		
	 		$this->db->order_by('content.created_at', 'desc');
	 		$result = $this->db->get();	
	 		return $result->result();	      
		}
		else
		{
			return FALSE;
		}
    }
	
	/**
	 * Get Content View Multiple
	 * 
	 * @param array $where An assoc. array of columnname => value to match
	 * @param string $status Limit returned content to this status
	 * @param int $limit The maximim number of records to return
	 * @return array An array of content objects matching the criteria
	 */
    function get_content_view_multiple($where, $status, $limit)
    {
 		$this->db->select('content.*, users.username, users.gravatar, users.name, users.image');
 		$this->db->from('content');
		$this->db->join('users', 'users.user_id = content.user_id');
 		$this->db->where($where);
 		
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

 		$this->db->order_by('content.created_at', 'desc');
 		$result = $this->db->get();
 		return $result->result();
    }
	
	/**
	 * Get Content New Count
	 * 
	 * Given a module name, returns the number of new pieces of content within that module
	 * 
	 * @param string $module The module to get a count from
	 * @return int The number of new pieces of content for that module
	 */
    function get_content_new_count($module)
    {
 		$this->db->from('content')->where(array('module' => $module, 'viewed' => 'N', 'approval !=' => 'D'));
 		return $this->db->count_all_results();
    }
	
	/**
	 * Get Content Title URL
	 * 
	 * Fetch a piece of content based on it’s title_url
	 * 
	 * @param string $type The type of content to get
	 * @param string $title_url The URL of the content to fetch
	 * @return object|null The fetched piece of content
	 */
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
	
	/**
	 * Get Content User
	 * 
	 * Get content and user information about a piece of content
	 * 
	 * @param int $content_id The content to get info about
	 * @return object A content+user object
	 */
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
    
    /**
	 * Get Content Category Count
	 * 
	 * @param int $category_id The category id to count content within
	 * @return int The number of pieces of content within the given category
	 */
    function get_content_category_count($category_id)
    {
 		$this->db->select('category_id');
 		$this->db->from('content');
 		$this->db->where('category_id', $category_id);
	 	$this->db->where('content.status !=', 'D');
 		return $this->db->count_all_results();
    }
	
	/**
	 * Get Content Multiple Count
	 * 
	 * @param array $where An assoc CI where array to filter content by
	 * @return int The number of pieces of content matching $where
	 */
    function get_content_multiple_count($where)
    {
 		$this->db->select('content_id');
 		$this->db->from('content');
 		$this->db->where($where);
	 	$this->db->where('content.status !=', 'D');
 		return $this->db->count_all_results();
    }
    
    /**
	 * Add Content
	 * 
	 * @param array $content_data An assoc. array of data to be inserted
	 * @return int|false The ID of the inserted content or false
	 * @todo Refactor this to remove unnecessary duplication
	 */
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
			'canonical'			=> $content_data['canonical'],
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
	
	/**
	 * Update Content
	 * 
	 * @param array $content_data A column=>valuye assoc. array of data to update
	 * @return object The updated content
	 */
    function update_content($content_data)
    {
 		$content_data['updated_at'] = unix_to_mysql(now());
		$this->db->where('content_id', $content_data['content_id']);
		$this->db->update('content', $content_data);
		return $this->db->get_where('content', array('content_id' => $content_data['content_id']))->row();		
    }
    
    /**
	 * Delete Content
	 * 
	 * @param int $content_id The id of the content to delete
	 * @return bool Always returns True
	 * @todo Make this return more meaningful data
	 */
    function delete_content($content_id)
    {
    	$this->db->where('content_id', $content_id);
    	$this->db->delete('content'); 
		return TRUE;
    }
  
	/* !Content Meta */
	
	/**
	 * Get Metadata
	 * 
	 * @param int $content_meta_id The content meta ID to fetch
	 * @return object The fetched metadata
	 */
    function get_meta($content_meta_id)
    {
 		$this->db->select('content_meta.*, content.user_id');
 		$this->db->from('content_meta');
  		$this->db->join('content', 'content.content_id = content_meta.content_id');		  
 		$this->db->where('content_meta.content_meta_id', $content_meta_id);
		$this->db->limit(1);
 		$result = $this->db->get()->row();	
 		return $result;
    }
    
    /**
	 * Get Meta Content
	 * 
	 * @param id $content_id The content_id to get metadata for
	 * @return object The fetched content metadata
	 */
    function get_meta_content($content_id)
    {
 		$this->db->select('content_meta.*, content.user_id');
 		$this->db->from('content_meta');
  		$this->db->join('content', 'content.content_id = content_meta.content_id');
 		$this->db->where('content_meta.content_id', $content_id);
 		$result = $this->db->get();
 		return $result->result();
    }
	
	/**
	 * Get Meta Content Meta
	 * 
	 * @todo Document. These names are confusing
	 */
    function get_meta_content_meta($content_id, $meta)
    {    		
 		$this->db->select('content_meta.*, content.user_id');
 		$this->db->from('content_meta');
  		$this->db->join('content', 'content.content_id = content_meta.content_id'); 		
 		$this->db->where(array('content_meta.content_id' => $content_id, 'content_meta.meta' => $meta));
 		$result = $this->db->get()->row();	
 		return $result;
    }
	
	/**
	 * Get Meta Multiples
	 * 
	 * @todo Document properly. Confusing name.
	 * 
	 */
    function get_meta_multiples($content_id_array)
    {
 		$this->db->select('content_meta.*, content.user_id');
 		$this->db->from('content_meta');
  		$this->db->join('content', 'content.content_id = content_meta.content_id');
 		$this->db->or_where_in('content_meta.content_id', $content_id_array);
 		$result = $this->db->get();
 		return $result->result();
    }
	
	/**
	 * Add Metadata
	 * 
	 * @param array $meta_data The data to be inserted
	 * @return int The id of the inserted data
	 */
    function add_meta($meta_data)
    {
    	$meta_data['created_at'] = unix_to_mysql(now());
		$meta_data['updated_at'] = unix_to_mysql(now());
		$this->db->insert('content_meta', $meta_data);
		$content_meta_id = $this->db->insert_id();

    	return $content_meta_id;
    }
	
	/**
	 * Update Metadata
	 * 
	 * @param int $content_meta_id The id of the metadata to update
	 * @param array $update_data An assoc. array of data to update (column => newval)
	 * @return bool Always returns true
	 * @todo Make this return more meaningful data
	 */
    function update_meta($content_meta_id, $update_data)
    {
		$update_data['updated_at'] = unix_to_mysql(now());
    
		$this->db->where('content_meta_id', $content_meta_id);
		$this->db->update('content_meta', $update_data);
		return TRUE;
    }
	
	/**
	 * Delete Metadata
	 * 
	 * @param int $content_meta_id The id of the metadata to delete
	 * @return bool Always returns true
	 * @todo Make this return more meaningful data
	 */
    function delete_meta($content_meta_id)
    {
    	$this->db->where('content_meta_id', $content_meta_id);
    	$this->db->delete('content_meta'); 
		return TRUE;
    }
    
}