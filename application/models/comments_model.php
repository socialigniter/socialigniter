<?php
class Comments_model extends CI_Model
{    
    function __construct()
    {
        parent::__construct();
    }
    
    function get_comment($comment_id)
    {
 		$this->db->select('comments.*, users.username, users.gravatar, users.name, users.image');
 		$this->db->from('comments');
 		$this->db->join('users', 'users.user_id = comments.user_id'); 
 		$this->db->where('comment_id', $comment_id);
 		$this->db->limit(1);				
 		$result = $this->db->get()->row();	
 		return $result;
    }

    function get_comments($site_id, $owner_id, $module)
    {
    	if ($module == 'all')
    	{
    		$where = array('comments.site_id' => $site_id, 'comments.owner_id' => $owner_id);
		}
		else
		{
		    $where = array('comments.site_id' => $site_id, 'comments.owner_id' => $owner_id, 'comments.module' => $module);
		}

 		$this->db->select('comments.*, content.title, content.title_url, users.username, users.gravatar, users.name, users.image');
 		$this->db->from('comments');
 		$this->db->join('content', 'content.content_id = comments.content_id');
 		$this->db->join('users', 'users.user_id = comments.user_id');
 		$this->db->where($where);
    	$this->db->where('comments.approval !=', 'D');
 		$this->db->order_by('created_at', 'desc');
 		$result = $this->db->get();	
 		return $result->result();
    }

    function get_comments_recent($site_id, $module, $limit)
    {
    	if ($module == 'all')
    	{
    		$where = array('comments.site_id' => $site_id, 'comments.approval' => 'Y');
		}
		else
		{
		    $where = array('comments.site_id' => $site_id, 'comments.module' => $module, 'comments.approval' => 'Y');
		}

 		$this->db->select('comments.*, content.title, content.title_url, users.username, users.gravatar, users.name, users.image');
 		$this->db->from('comments');
 		$this->db->join('content', 'content.content_id = comments.content_id');
 		$this->db->join('users', 'users.user_id = comments.user_id');
 		$this->db->where($where);
 		$this->db->limit($limit);
 		$this->db->order_by('created_at', 'desc');
 		$result = $this->db->get();	
 		return $result->result();
    }
    
    function get_comment_children($reply_to_id)
    {
 		$this->db->select('*');
 		$this->db->from('comments');
 		$this->db->where('reply_to_id', $reply_to_id);
 		$result = $this->db->get();	
 		return $result->result();
    }

    function get_comments_count($site_id)
    {    	
 		$this->db->from('comments')->where('site_id', $site_id);
 		return $this->db->count_all_results();
    }
    
    function get_comments_content($content_id, $approval='Y')
    {
    	$where = array('content_id' => $content_id, 'approval' => $approval);
    	
 		$this->db->select('comments.*, users.username, users.gravatar, users.name, users.image');
 		$this->db->from('comments');
 		$this->db->join('users', 'users.user_id = comments.user_id');
 		$this->db->where($where);
 		$this->db->order_by('created_at', 'asc'); 
 		$result = $this->db->get();	
 		return $result->result();
    }

    function get_comments_content_count($content_id, $approval)
    {
    	if ($approval == 'all')
    	{
			$where = array('content_id' => $content_id);
    	}
    	else
    	{
    		$where = array('content_id' => $content_id, 'approval' => 'Y');
    	}
    
 		$this->db->select('content_id, approval')->from('comments')->where($where);
 		return $this->db->count_all_results();
    }
    
    function get_comments_new_count($site_id, $owner_id)
    {    	
 		$this->db->from('comments')->where(array('site_id' => $site_id, 'owner_id' => $owner_id, 'viewed' => 'N', 'approval !=' => 'D'));
 		return $this->db->count_all_results();
    }

    function add_comment($site_id, $comment_data)
    {
    	// If Empty Fail
    	if ((!$comment_data['user_id']) || (!$comment_data['content_id']))
    	{
    		return FALSE;
    	}
    	
    	// Viewed Status
    	if ($comment_data['user_id'] ==  $comment_data['owner_id'])
    	{
    		$viewed = 'Y';
    	}
    	else
    	{
    		$viewed = 'N';
    	}
    
 		$data = array(
 			'site_id'			=> $site_id,
			'reply_to_id'		=> $comment_data['reply_to_id'],
			'content_id'		=> $comment_data['content_id'],
			'owner_id'			=> $comment_data['owner_id'],
			'module'			=> $comment_data['module'],			
			'type'				=> $comment_data['type'],			
			'user_id' 	 		=> $comment_data['user_id'],
			'comment'			=> $comment_data['comment'],
			'geo_lat'			=> $comment_data['geo_lat'],
			'geo_long'			=> $comment_data['geo_long'],
			'viewed'			=> $viewed,
			'approval'			=> $comment_data['approval'],
			'created_at' 		=> unix_to_mysql(now())
		);	
		
		$this->db->insert('comments', $data);
		return $this->db->insert_id();
    }
    
    function update_comment_viewed($comment_id)
    {
    	$this->db->where('comment_id', $comment_id);
		$this->db->update('comments', array('viewed' => 'Y')); 
    	return TRUE;
    }

    function update_comment_reply_to_id($comment_id, $reply_to_id)
    {
    	$this->db->where('comment_id', $comment_id);
		$this->db->update('comments', array('reply_to_id' => $reply_to_id)); 
		return TRUE;
    }
       
    function update_comment_approve($comment_id)
    {
    	$this->db->where('comment_id', $comment_id);
		$this->db->update('comments', array('approval' => 'Y')); 
		return TRUE;   
    }

    function delete_comment($comment_id)
    {
    	$this->db->where('comment_id', $comment_id);
		$this->db->update('comments', array('approval' => 'D')); 
		return TRUE;   
    }
}