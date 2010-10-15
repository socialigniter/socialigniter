<?php
class Status extends MY_Controller {
 
    function __construct() 
    {
        parent::__construct();
	    if (!$this->social_auth->logged_in()) redirect('login', 'refresh');
    }
 
 	function index()
 	{
	    redirect('home', 'refresh');
 	} 
 	
 	function add()
 	{
     	$this->form_validation->set_rules('status_update', 'Status', 'required|min_length[1]|max_length['.config_item('status_length').']');	
 	
        if ($this->form_validation->run() == true) 
        {        	            			
			$status_data = array(
				'parent_id'			=> 0,
				'category_id'		=> 0,
				'module'			=> 'core',
				'type'				=> 'status',
				'source'			=> '',
				'order'				=> 0,
				'user_id'			=> $this->session->userdata('user_id'),
				'title'				=> '',
				'title_url'			=> '',
				'content'			=> $this->input->post('status_update'),
				'details'			=> '',
				'access'			=> $this->input->post('access'),
				'comments_allow'	=> 'Y',
				'geo_lat'			=> $this->input->post('geo_lat'),
				'geo_long'			=> $this->input->post('geo_long'),
				'geo_accuracy'		=> $this->input->post('geo_accuracy'),
				'status'			=> 'P'
			);
			
			$status = $this->social_igniter->add_content($status_data);
			
	        if (!$status)
	        {
				echo 'error';
	    	}
	    	else
			{
				$info = array(
					'site_id'		=> config_item('site_id'),
					'user_id'		=> $this->session->userdata('user_id'),
					'verb'			=> 'post',
					'module'		=> 'core',
					'type'			=> 'status'
				);
				
				$data = array(
					'content_id' 	=> $status->content_id
				);
			
				$activity = $this->social_igniter->add_activity($info, $data);
				
				if (IS_AJAX)
				{
					$this->data['item_id']				= $activity->activity_id;
					
					// Contributor
					$this->data['item_user_id']			= $this->data['logged_user_id'];
					$this->data['item_avatar']			= $this->data['profile_image'];
					$this->data['item_contributor']		= $this->data['profile_name'];
					$this->data['item_profile']			= $this->data['link_profile'];

					// Status
					$this->data['item_type']			= 'status';
					$this->data['item_content']			= $this->social_igniter->render_item('post', 'status', $activity->data);

					$this->data['item_date']			= human_date('SIMPLE', mysql_to_unix($activity->created_at));
	
			 		// Actions
			 		$this->data['item_comment']			= base_url().'comment/item/'.$activity->activity_id;
					$this->data['item_delete']			= base_url().'status/delete/'.$activity->activity_id;
				
					// View
					echo $this->load->view($this->config->item('dashboard_theme').'/partials/feed_timeline.php', $this->data, true);
				}
				else
				{
					redirect('home#status-'.$status->content_id);
				}
			}
		}
		else 
		{ 
			if (IS_AJAX)
			{
				echo 'error';
			}
			else
			{		
       			redirect("home", 'refresh');		
			}
		} 	
 	} 
 	
 	
 	function delete()
 	{ 	
 		$delete = $this->social_igniter->delete_activity($this->uri->segment(3));
 	 	
		if ($delete)
		{
			if (IS_AJAX)	echo 'deleted';
			else			redirect($this->session->userdata('previous_page'));
		}
		else
		{
			if (IS_AJAX)	echo 'error';
			else			redirect($this->session->userdata('previous_page'));
		}
		
 	}  	
    
}