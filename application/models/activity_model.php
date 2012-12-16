<?php

/**
 * Activity Model
 * 
 * A model for managing social-igniter Activities
 * 
 * @author Brennan Novak @brennannovak
 * @package Social Igniter\Models
 */
class Activity_model extends CI_Model
{    
    function __construct()
    {
        parent::__construct();
    }
    
    /**
     * Get Timeline
     * 
     * Gets an array of activity objects based on criteria in $where
     * 
     * $where can either be an array like array('column_name' => 'value to match') or a 
     * mysql WHERE clause, e.g. 'activity.site = 1'
     * 
     * @param string|array $where The where clause (see description)
     * @param int $limit The maximum number of results to return
     * @return array An array of activities matching the clauses
     */
    function get_timeline($where, $limit)
    {
 		$this->db->select('activity.*, content.canonical, sites.url, sites.title, sites.favicon, users.username, users.gravatar, users.name, users.image');
 		$this->db->from('activity');    
 		$this->db->join('sites', 'sites.site_id = activity.site_id');
 		$this->db->join('users', 'users.user_id = activity.user_id'); 				
 		$this->db->join('content', 'content.content_id = activity.content_id', 'left'); 				
    	$this->db->where($where);
 		$this->db->order_by('created_at', 'desc'); 
		$this->db->limit($limit);    
 		$result = $this->db->get();	
 		return $result->result();	      
    }
    
    /**
     * Get Activity
     * 
     * @param int $activity_id The id of the activity to fetch
     * @return object An object representing the fetched activity
     */
    function get_activity($activity_id)
    {
	 	$this->db->select('activity.*, content.canonical, sites.title, sites.favicon, users.username, users.gravatar, users.name, users.image');
 		$this->db->from('activity'); 
 		$this->db->join('sites', 'sites.site_id = activity.site_id');
 		$this->db->join('users', 'users.user_id = activity.user_id');
 		$this->db->join('content', 'content.content_id = activity.content_id', 'left'); 				 
 		$this->db->where('activity_id', $activity_id);
		$this->db->limit(1);    
 		$result = $this->db->get()->row();	
 		return $result; 
    }
	
	/**
     * Get Activity View
     * 
     * @todo Document properly
     */
    function get_activity_view($parameter, $value, $limit)
    {
    	if (in_array($parameter, array('site_id','user_id','verb', 'module','type','content_id')))
    	{    
	 		$this->db->select('activity.*, content.title, content.content, content.canonical, sites.title AS site_title, sites.favicon, users.username, users.gravatar, users.name, users.image');
	 		$this->db->from('activity');
	 		$this->db->join('sites', 'sites.site_id = activity.site_id');
	 		$this->db->join('users', 'users.user_id = activity.user_id');
	 		$this->db->join('content', 'content.content_id = activity.content_id', 'left');
	 		$this->db->where('activity.'.$parameter, $value);
	 		$this->db->order_by('activity.created_at', 'desc'); 
			$this->db->limit($limit);    
	 		$result = $this->db->get();	
	 		return $result->result();     
		}
		else
		{
			return FALSE;
		}		
    }    
    
    /**
     * Add Activity
     * 
     * Add a new activity into the database.
     * 
     * $activity_info should be an assoc. array where all the keys are activity table 
     * column names.
     * 
     * $activity_data is an array which will be json_encoded and stored in the mysql table
     * 
     * ## Example Parameters
     * 
     *     $activity_info['site_id'] = 1;
     *     $activity_info['user_id'] = 1;
     *     $activity_info['verb'] = 'post';
     *     $activity_info['module'] = 'home';
     *     $activity_info['type'] = 'status';
     *     
     *     $activity_data['title'] = false;
     *     $activity_data['content'] = 'Hello world, this is a status update';
     *     $activity_data['url'] = 'http://example.com/home/view/1';
     *     
     * @param array $activity_info An array containing activity metadata
     * @param array $activity_data The activity data
     * @return int|bool The id of the inserted activity, or false on failure
     */
    function add_activity($activity_info, $activity_data)
    {
		if (array_key_exists('content_id', $activity_info)) $content_id = $activity_info['content_id'];
		else $content_id = 0;

 		$insert_data = array(
			'site_id' 	 		=> $activity_info['site_id'],
			'user_id' 	 		=> $activity_info['user_id'],
			'verb'				=> $activity_info['verb'],
			'module'			=> $activity_info['module'],
			'type'				=> $activity_info['type'],
			'content_id' 	 	=> $content_id,
			'data'  	 		=> json_encode($activity_data),
			'status'			=> 'P',
			'created_at' 		=> unix_to_mysql(now())
		);
		
		$this->db->insert('activity', $insert_data);
		$activity_id = $this->db->insert_id();
		
		if ($activity_id)
		{
			return $activity_id;
		}
		
		return FALSE;
    }
    
    /**
     * Delete Activity
     * 
     * Note: This does not actually delete the activity, it just gives it a deleted status
     * 
     * @param int $activity_id The id of the activity to delete
     * @return bool Returns true all the time
     * @todo Make this return false if things didn’t go to plan
     */
    function delete_activity($activity_id)
    {
    	$this->db->where('activity_id', $activity_id);
		$this->db->update('activity', array('status' => 'D'));
		return TRUE;
    }
}