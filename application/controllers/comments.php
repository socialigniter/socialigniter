<?php
class Comments extends Dashboard_Controller 
{
    function __construct() 
    {
        parent::__construct(); 

		$this->load->library('social_tools');  
		
		$this->data['page_title'] = 'Comments';
    }
 
 	function index()
 	{
 		if ($this->uri->segment(2)) $comment_module = $this->uri->segment(2);
 		else						$comment_module = 'all';
 	
		$comments 					= $this->social_tools->get_comments($comment_module);		
		$comments_view 				= NULL;
		$this->data['feed_type']	= 'comments';
    	$this->data['item_verb']	= item_type($this->lang->line('object_types'), 'comment');
	
		
		if (empty($comments))
		{
			 $comments_view = '<li>No comments to show!</li>';
	 	}
	 	else
	 	{
			foreach ($comments as $item)
			{
				// Item
				$this->data['item_id']				= $item->comment_id;
				$this->data['item_type']			= item_type_class($item->type);
				$this->data['item_viewed']			= item_viewed($item->viewed);
				
				// Contributor
				$this->data['item_avatar']			= $this->social_igniter->profile_image($item->user_id, $item->image, $item->email);
				$this->data['item_contributor']		= $item->name;
				$this->data['item_profile']			= base_url().'profile/'.$item->username;

				// Activity
				$this->data['item_object']			= $item->title;
				$this->data['item_text']			= $item->comment;
				$this->data['item_date']			= human_date('SIMPLE', mysql_to_unix($item->created_at));
				$this->data['item_approval']		= $item->approval;
		
		 		// Actions
				$this->data['item_view'] 			= base_url().'comments/'.$item->type.'/view/'.$item->content_id.'/'.$item->comment_id;
				$this->data['item_reply'] 			= base_url().'comments/'.$item->type.'/reply/'.$item->content_id.'/'.$item->comment_id;
				$this->data['item_approve']			= base_url().'comments/approve/'.$item->comment_id;
				$this->data['item_delete']			= base_url().'comments/delete/'.$item->comment_id;

				// Alerts
				$this->data['item_alerts']			= item_alerts($item->comment_id, $item->viewed, $item->approval, $this->data['item_approve']);

				// Load Partial For Items
				$comments_view 				   	   .= $this->load->view($this->config->item('dashboard_theme').'/partials/feed_comments.php', $this->data, true);
	 		}
 		}
		
		$this->data['comments_view'] = $comments_view;	
				
		$this->render();
	}
	
	function viewed()
	{
		if (IS_AJAX)
		{
			$this->social_tools->update_comment_viewed($this->uri->segment(3));
			echo 'viewed';
		}
		else
		{	
			echo 'Whoa there cowboy!';
		}
	}

	function approve()
	{
		// Make sure user has access to do this func
//		$this->social_tools->does_user_have_access($this->session->userdata('user_id'), $this->uri->segment(3));
		
		$this->social_tools->update_comment_approve($this->uri->segment(3));	

		if (IS_AJAX)	echo 'approved';
		else			redirect($this->session->userdata('previous_page'));
	}
	

	function delete()
	{
		// Make sure user has access to do this func
//		$this->social_tools->does_user_have_access($this->session->userdata('user_id'), $this->uri->segment(3));

		$comment = $this->social_tools->get_comment($this->uri->segment(3));
		
		// Delete
		$this->social_tools->delete_comment($comment->comment_id);
		
		// Reset comments with this reply_to_id
		$this->social_tools->update_comment_orphaned_children($comment->comment_id);
		
		// Update Content
		$this->social_igniter->update_content_comments_count($comment->content_id);

		if (IS_AJAX)	echo 'deleted';
		else			redirect($this->session->userdata('previous_page'));
	}	

	
	/* AJAX Only */
	function count_new()
	{
		if (IS_AJAX) echo $this->social_tools->get_comments_new_count();	
	}
	

}