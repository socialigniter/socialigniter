<?php

/**
 * Categories Model
 * 
 * A model for managing social-igniter Categories
 * 
 * @author Brennan Novak @brennannovak
 * @package Social Igniter\Models
 */
class Categories_model extends CI_Model
{    
    function __construct()
    {
        parent::__construct();
    }
    
    /**
     * Get Categories
     * 
     * @return array An array of all the categories in the DB
     */
    function get_categories()
    {
 		$this->db->select('categories.*, users.username, users.gravatar, users.name, users.image');
 		$this->db->from('categories');    
 		$this->db->join('users', 'users.user_id = categories.user_id'); 
 	 	$this->db->where('categories.status !=', 'D');
 		$this->db->order_by('categories.created_at', 'desc'); 
 		$result = $this->db->get();	
 		return $result->result();
    }
	
	/**
     * Get Category
     * 
     * @param int $category_id The ID of the category to get
     * @return array An array representing the category
     */
    function get_category($category_id)
    {
 		$this->db->select('categories.*, users.username, users.gravatar, users.name, users.image');
 		$this->db->from('categories'); 
 		$this->db->join('users', 'users.user_id = categories.user_id'); 
  		$this->db->where('category_id', $category_id);
	 	$this->db->where('categories.status !=', 'D');
 		$result = $this->db->get()->row();
 		return $result;
    }
	
	/**
     * Get Category Parent
     * 
     * @todo Document properly (exactly what does this do?)
     */
    function get_category_parent($parent_id)
    {
 		$this->db->select('categories.*, users.username, users.gravatar, users.name, users.image');
 		$this->db->from('categories'); 
 		$this->db->join('users', 'users.user_id = categories.user_id'); 
  		$this->db->where('parent_id', $parent_id);
	 	$this->db->where('categories.status !=', 'D');
 		$result = $this->db->get()->row();
 		return $result;
    }
	
	/**
     * Get Categories View
     * 
     * Get categories where column $parameter == $value
     * 
     * @param string $parameter A column name from categories to search by
     * @param string $value The value to filter by
     * @return array An array of categories which matched the query
     */
    function get_categories_view($parameter, $value)
    {
    	if (in_array($parameter, array('category_id','parent_id','site_id','module','type','category_url')))
    	{
	 		$this->db->select('categories.*, users.username, users.gravatar, users.name, users.image');
	 		$this->db->from('categories');
	 		$this->db->join('users', 'users.user_id = categories.user_id');
	 		$this->db->where($parameter, $value);
	 		$this->db->where('categories.status !=', 'D');
	 		$this->db->order_by('parent_id', 'desc');	 		
	 		$this->db->order_by('contents_count', 'desc');
	 		$result = $this->db->get();
	 		return $result->result();
		}
		else
		{
			return FALSE;
		}
    }
	
	/**
     * Get Categories View Multiple
     * 
     * Execute arbitrary WHERE clauses on the categories table
     * 
     * @param string $where The where clause to filter by (without WHERE)
     */
    function get_categories_view_multiple($where)
    { 
 		$this->db->select('categories.*, users.username, users.gravatar, users.name, users.image');
 		$this->db->from('categories');
 		$this->db->join('users', 'users.user_id = categories.user_id');
 		$this->db->where($where);
	 	$this->db->where('categories.status !=', 'D');
 		$this->db->order_by('parent_id', 'desc');	
	 	$this->db->order_by('contents_count', 'desc'); 		
 		$result = $this->db->get();
 		return $result->result();
    }
	
	/**
     * Get Category from Title and URL
     * 
	 * @param string $type The title of the category
	 * @param string $category_url The URL of the category to find
	 * @return array|bool An array representing the matched category or false
     */
    function get_category_title_url($type, $category_url)
    {
 		$this->db->select('categories.*, users.username, users.gravatar, users.name, users.image');
 		$this->db->from('categories'); 
 		$this->db->join('users', 'users.user_id = categories.user_id'); 
  		$this->db->where('categories.type', $type);
  		$this->db->where('categories.category_url', $category_url);
	 	$this->db->where('categories.status !=', 'D');
 		$result = $this->db->get()->row();
 		return $result;
    }
	
	/**
     * Get Category Default User
     * 
     * @todo Document — exactly what does this do?
     */
    function get_category_default_user($parameter, $value, $user_id)
    {
    	if (in_array($parameter, array('parent_id','site_id','module','type','category_url')))
    	{
	 		$this->db->select('*');
	 		$this->db->from('categories'); 
	 		$this->db->join('users', 'users.user_id = categories.user_id');	 		
	 		$this->db->where($parameter, $value);
	 		$this->db->where('user_id', $user_id);
	 		$this->db->where('categories.status !=', 'D');	 		
	 		$this->db->order_by('created_at', 'desc'); 
	 		$result = $this->db->get();	
	 		return $result->result();	      
		}
		else
		{
			return FALSE;
		}
    }
    	
    /**
     * Add Category
     * 
     * Creates a new category from $category_data, which is an assoc. array with keys named
     * after column names in the category table.
     * 
     * ## Example Params:
     * 
     *     $category_data['parent_id'] = 1;
     *     $category_data['content_id'] = 5;
     *     category_data['site_id'] = 1;
	 *     $category_data['user_id'] = 1;
	 *     $category_data['access'] = 1;
	 *     $category_data['module'] = 'home';
	 *     $category_data['type'] = '';
	 *     $category_data['category'] = 'Category Name';
	 *     $category_data['category_url'] = '';
	 *     $category_data['description'] = 'This is some demo category';
	 *     $category_data['details'] = '';
     *     
     * @param array $category_data The data to add to the DB
     * @return array The inserted data
     * @todo Improve documentation example
     */
    function add_category($category_data)
    {
 		$data = array(
 			'parent_id'		=> $category_data['parent_id'],
 			'content_id'	=> $category_data['content_id'],
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
			'status'		=> 'P',
			'created_at' 	=> unix_to_mysql(now()),
			'updated_at' 	=> unix_to_mysql(now())
		);	
		$insert 		= $this->db->insert('categories', $data);
		$category_id 	= $this->db->insert_id();
		return $this->db->get_where('categories', array('category_id' => $category_id))->row();	
    }
    
    /**
     * Update Category
     * 
     * @param int $category_id The id of the category to update
     * @param array $category_data An assoc array (column => newval) of columns to update
     * @return bool Always returns true
     * @todo Make this return a more meaningful value
     */
    function update_category($category_id, $category_data)
    {
 		$category_data['updated_at'] = unix_to_mysql(now());

		$this->db->where('category_id', $category_id);
		$this->db->update('categories', $category_data);
		
		return TRUE;
    }
    
    /**
     * Update Category Contents Count
     * 
     * @param int $category_id The id of the category to update
     * @param int $contents_count The new value to set categories.contents_count to
     * @return bool Always returns true
     * @todo Make this return more meaningful values
     */
    function update_category_contents_count($category_id, $contents_count)
    {
		$this->db->where('category_id', $category_id);
		$this->db->update('categories', array('contents_count' => $contents_count));
		return TRUE;
    }
    
    /**
     * Update Category Details
     * 
     * @param int $category_id The id of the category to update
     * @param int $contents_details The new value to set categories.details to
     * @return bool Always returns true
     * @todo Make this return more meaningful values
     */
    function update_category_details($category_id, $details)
    {
		$this->db->where('category_id', $category_id);
		$this->db->update('categories', array('details' => $details));
		return TRUE;
    } 
    
    /**
     * Delete Category
     * 
     * Note that, as with all SI-delete methods, this does not permanently delete the data, merely 
     * marking it as deleted internally.
     * 
     * @param int $category_id The id of the category to delete
     * @return bool Always returns true
     * @todo Make this return more meaningful values
     */
    function delete_category($category_id)
    {
    	$this->db->where('category_id', $category_id);
		$this->db->update('categories', array('contents_count' => 0, 'status' => 'D')); 
		return TRUE;   
    }              

}