<?php
class Home extends Dashboard_Controller 
{
    function __construct() 
    {
        parent::__construct();
    }
 
 	/* Home Feed - All modules when URL is 'home/blog' this method shows all activity for specified module */
 	function index()
 	{
 		$timeline		= NULL;
		$timeline_view	= NULL;
		
 	   	// Load
		$this->data['home_greeting']	= random_element($this->lang->line('home_greeting'));
	 	$this->data['social_post'] 		= $this->social_igniter->get_social_post($this->session->userdata('user_id'), 'social_post_horizontal'); 		
 	
 		// Pick Type of Feed
		if ($this->uri->total_segments() == 1)
		{
	 	    $this->data['page_title'] 		= 'Home';
			$this->data['status_updater']	= $this->load->view(config_item('dashboard_theme').'/partials/status_updater', $this->data, true); 	    
 	    
			$timeline 						= $this->social_igniter->get_timeline(NULL, 10);
 	    }
 	    elseif ($this->uri->segment(2) == 'friends')
 	    {
	 	    $this->data['page_title'] 		= 'Friends';
			$this->data['status_updater']	= $this->load->view(config_item('dashboard_theme').'/partials/status_updater', $this->data, true);  	    	

			if ($friends = $this->social_tools->get_relationships_follows($this->session->userdata('user_id')))
			{
				$timeline = $this->social_igniter->get_timeline_friends($friends, 10);
			}
 	    }
 	    elseif ($this->uri->segment(2) == 'likes')
 	    {
	 	    $this->data['page_title'] 		= 'Likes';
			$this->data['status_updater']	= $this->load->view(config_item('dashboard_theme').'/partials/status_updater', $this->data, true);  	    	

			$likes							= $this->social_tools->get_ratings_likes_user($this->session->userdata('user_id'));
			$timeline 						= $this->social_igniter->get_timeline_likes($likes, 10); 
 	    }
 	    // Fix For MODULE Checking
 	    else
 	    {
	 	    $this->data['page_title'] 		= display_nice_file_name($this->uri->segment(2));
 			$this->data['sub_title']		= 'Recent';
 			$this->data['status_updater']	= '';

			$timeline 						= $this->social_igniter->get_timeline($this->uri->segment(2), 10); 
 	    }	    
 	     	     	    
		// Build Feed				 			
		if (!empty($timeline))
		{
			foreach ($timeline as $activity)
			{			
				// Item
				$this->data['item_id']				= $activity->activity_id;
				$this->data['item_type']			= item_type_class($activity->type);
				
				// Contributor
				$this->data['item_user_id']			= $activity->user_id;
				$this->data['item_avatar']			= $this->social_igniter->profile_image($activity->user_id, $activity->image, $activity->gravatar);
				$this->data['item_contributor']		= $activity->name;
				$this->data['item_profile']			= base_url().'profile/'.$activity->username;
				
				// Activity
				$this->data['item_content']			= $this->social_igniter->render_item($activity);
				$this->data['item_content_id']		= $activity->content_id;
				$this->data['item_date']			= format_datetime(config_item('home_date_style'), $activity->created_at);			

		 		// Actions
			 	$this->data['item_comment']			= base_url().'comment/item/'.$activity->activity_id;
			 	$this->data['item_comment_avatar']	= $this->data['logged_image'];
			 	
			 	$this->data['item_can_modify']		= $this->social_auth->has_access_to_modify('activity', $activity, $this->session->userdata('user_id'), $this->session->userdata('user_level_id'));
				$this->data['item_edit']			= base_url().'home/'.$activity->module.'/manage/'.$activity->content_id;
				$this->data['item_delete']			= base_url().'api/activity/destroy/id/'.$activity->activity_id;

				// View
				$timeline_view .= $this->load->view(config_item('dashboard_theme').'/partials/item_timeline.php', $this->data, true);
	 		}
	 	}
	 	else
	 	{
	 		$timeline_view = '<li><p>Nothing to show from anyone!</p></li>';
 		}	

		// Final Output
		$this->data['timeline_view'] 	= $timeline_view;
		$this->render();
 	}
	
 	/* Manage - All modules base manage page when URL is 'home/blog/manage' this method shows all 'content' from specified module */
	function manage()
	{
		if ($this->session->userdata('user_level_id') > 3) redirect(base_url().'home');
	
		$content_module		= $this->social_igniter->get_content_view('module', $this->uri->segment(2), 'all');
		$manage_view 		= NULL;

		// Title Stuff
		$this->data['page_title']	= ucwords($this->uri->segment(2));
		$this->data['sub_title']	= 'Manage';
		
		if (!empty($content_module))
		{		 
			foreach($content_module as $content)
			{
				$this->data['item_id'] 				= $content->content_id;
				$this->data['item_type']			= $content->type;
				$this->data['item_viewed']			= item_viewed('item_manage', $content->viewed);
	
				$this->data['title']				= item_title($content->title, $content->type);
				$this->data['title_link']			= base_url().$content->module.'/view/'.$content->content_id;
				$this->data['comments_count']		= manage_comments_count($content->comments_count);
				$this->data['publish_date']			= manage_published_date($content->created_at, $content->updated_at);
				
				// MAKE FOR CHECK RELVANT TO USER_LEVEL
				$this->data['item_status']			= display_content_status($content->status);
				$this->data['item_approval']		= $content->approval;
	
				// Alerts
				$this->data['item_alerts']			= item_alerts_content($content);			
				
				// Actions
				$this->data['item_approve']			= base_url().'api/content/approve/id/'.$content->content_id;
				$this->data['item_edit']			= base_url().'home/'.$content->module.'/manage/'.$content->content_id;
				$this->data['item_delete']			= base_url().'api/content/destroy/id/'.$content->content_id;
				
				// View
				$manage_view .= $this->load->view(config_item('dashboard_theme').'/partials/item_manage.php', $this->data, true);
			}	
		}
	 	else
	 	{
	 		$manage_view = '<li>Nothing to manage from anyone!</li>';
 		}

		// Final Output
		$this->data['timeline_view'] 	= $manage_view;				
		
		$this->render('dashboard_wide');
	}	

	/* Dashboard Comments */
 	function comments()
 	{
 	    $this->data['page_title'] 	= "Comments";
 	    $this->data['sub_title'] 	= "Recent";
 		$this->data['navigation']	= $this->load->view(config_item('dashboard_theme').'/partials/navigation_comments.php', $this->data, true);

		$comments 					= $this->social_tools->get_comments(config_item('site_id'), $this->session->userdata('user_id'), $this->uri->segment(3));		
		$comments_view 				= NULL;
		$this->data['feed_type']	= 'comments';
    	$this->data['item_verb']	= item_type($this->lang->line('object_types'), 'comment');
	
		if (empty($comments))
		{
			 $comments_view = '<li>No comments to show!</li>';
	 	}
	 	else
	 	{
			foreach ($comments as $comment)
			{
				// Item
				$this->data['item_id']				= $comment->comment_id;
				$this->data['item_type']			= item_type_class($comment->type);
				$this->data['item_viewed']			= item_viewed('item', $comment->viewed);
				
				// Contributor
				$this->data['item_avatar']			= $this->social_igniter->profile_image($comment->user_id, $comment->image, $comment->gravatar);
				$this->data['item_contributor']		= $comment->name;
				$this->data['item_profile']			= base_url().'profile/'.$comment->username;

				// Activity
				if ($comment->title)
				{
					$this->data['item_article']		= '';
					$this->data['item_object']		= $comment->title;
				}
				else
				{
					$this->data['item_article']		= item_type($this->lang->line('object_articles'), $comment->type);
					$this->data['item_object']		= $comment->type;
				}
				
				$this->data['item_text']			= item_linkify($comment->comment);
				$this->data['item_date']			= human_date('SIMPLE', mysql_to_unix($comment->created_at));
				$this->data['item_approval']		= $comment->approval;

				// Alerts
				$this->data['item_alerts']			= item_alerts_comment($comment);
		
		 		// Actions
				$this->data['item_view'] 			= base_url().$comment->module.'/view/'.$comment->content_id.'/'.$comment->comment_id;
				$this->data['item_reply'] 			= base_url().$comment->module.'/reply/id/'.$comment->content_id.'/'.$comment->comment_id;
				$this->data['item_approve']			= base_url().'api/comments/approve/id/'.$comment->comment_id;
				$this->data['item_delete']			= base_url().'api/comments/destroy/id/'.$comment->comment_id;
				
				// Load Partial For Items
				$comments_view 				   	   .= $this->load->view(config_item('dashboard_theme').'/partials/item_comments.php', $this->data, true);
	 		}
 		}
		
		$this->data['comments_view'] = $comments_view;	
				
		$this->render();
	}
	
	/* Exists for home status click??? */
	function view()
	{
		$this->render();
	}
	
	/* Places */
	function places_editor()
	{
		$this->load->config('locations');
					
		$this->data['page_title'] = 'Places';

		if (($this->uri->segment(3) == 'manage') && ($this->uri->segment(4)))
		{
			// Need is valid & access and such
			$place = $this->social_igniter->get_content($this->uri->segment(4));
			if (!$place) redirect(base_url().'home/error');
			$place_details = $this->social_tools->get_place('content_id', $place->content_id);
	
			// Non Form Fields
			$this->data['sub_title']		= $place->title;
			$this->data['form_url']			= base_url().'api/places/modify/id/'.$this->uri->segment(4);
			
			if ($place->geo_lat) $geo_lat = $place->geo_lat;
			else $geo_lat = '0.00';

			if ($place->geo_long) $geo_long = $place->geo_long;
			else $geo_long = '0.00';			
			
			// Form Fields
			$this->data['title'] 			= $place->title;
			$this->data['title_url'] 		= $place->title_url;
			$this->data['content']			= $place->content;
			$this->data['category_id']		= $place->category_id;
			$this->data['details'] 			= $place->details;
			$this->data['access']			= $place->access;
			$this->data['comments_allow']	= $place->comments_allow;
			$this->data['geo_lat']			= $geo_lat;
			$this->data['geo_long']			= $geo_long;
			$this->data['status']			= display_content_status($place->status, $place->approval);

			// Place
			$this->data['address']			= $place_details->address;
			$this->data['district']			= $place_details->district;
			$this->data['locality']			= $place_details->locality;
			$this->data['region']			= $place_details->region;
			$this->data['country']			= $place_details->country;
			$this->data['postal']			= $place_details->postal;
		}
		else
		{
			// Non Form Fields
			$this->data['sub_title']		= 'Create';
			$this->data['form_url']			= base_url().'api/places/create';

			// Form Fields
			$this->data['title'] 			= '';
			$this->data['title_url']		= '';
			$this->data['content']			= '';
			$this->data['category_id']		= '';
			$this->data['details'] 			= '';
			$this->data['access']			= 'E';
			$this->data['comments_allow']	= '';
			$this->data['geo_lat']			= '0.00';
			$this->data['geo_long']			= '0.00';
			$this->data['status']			= display_content_status('U');

			// Place
			$this->data['address']			= '';
			$this->data['district']			= '';
			$this->data['locality']			= '';
			$this->data['region']			= '';
			$this->data['country']			= 'US';
			$this->data['postal']			= '';			
		}

		$this->data['form_module']			= 'places';
		$this->data['form_type']			= 'place';
		$this->data['form_name']			= 'places_editor';
		$this->data['categories'] 			= $this->social_tools->make_categories_dropdown('module', 'places', $this->session->userdata('user_id'), $this->session->userdata('user_level_id'));	
	 	$this->data['content_publisher'] 	= $this->load->view(config_item('dashboard_theme').'/partials/content_publisher', $this->data, true);

 		$this->render('dashboard_wide');
	}	
	
	
	/* Pages */
	function pages_editor()
	{				
		if (($this->uri->segment(3) == 'manage') && ($this->uri->segment(4)))
		{
			// Need is valid & access and such
			$page = $this->social_igniter->get_content($this->uri->segment(4));
			if (!$page) redirect(base_url().'home/error');			
							
			// Non Form Fields
			$this_page_id					= $page->content_id;
			$this->data['sub_title']		= $page->title;
			$this->data['form_url']			= base_url().'api/content/modify/id/'.$this->uri->segment(4);
			
			// Form Fields
			$this->data['title'] 			= $page->title;
			$this->data['title_url'] 		= $page->title_url;
			
			if ($page->details == 'site')
			{
				$this->data['slug_pre']		= base_url().'pages/';
			}
			else
			{
				$this->data['slug_pre']		= base_url().'';
			}
			
			$this->data['wysiwyg_value']	= $page->content;
			$this->data['parent_id']		= $page->parent_id;
			$this->data['details'] 			= $page->details;
			$this->data['access']			= $page->access;
			$this->data['comments_allow']	= $page->comments_allow;
			$this->data['geo_lat']			= $page->geo_lat;
			$this->data['geo_long']			= $page->geo_long;			
			$this->data['status']			= display_content_status($page->status, $page->approval);
		}
		else
		{		
			// Non Form Fields
			$this_page_id					= 0;
			$this->data['sub_title']		= 'Create';
			$this->data['form_url']			= base_url().'api/content/create';
			
			// Form Fields
			$this->data['title'] 			= '';
			$this->data['title_url']		= '';
			$this->data['slug_pre']			= base_url().'pages';
			$this->data['layouts']			= '';
			$this->data['wysiwyg_value']	= $this->input->post('content');
			$this->data['parent_id']		= '';
			$this->data['details'] 			= 'site';			
			$this->data['access']			= 'E';
			$this->data['comments_allow']	= '';
			$this->data['geo_lat']			= '';
			$this->data['geo_long']			= '';			
			$this->data['status']			= display_content_status('U');
		}
	
		// WYSIWYG for 'pages'
		if ($this->data['details'] == 'site')
		{
			$this->data['wysiwyg_name']		= 'content';
			$this->data['wysiwyg_id']		= 'wysiwyg_content';
			$this->data['wysiwyg_class']	= 'wysiwyg_norm_full';
			$this->data['wysiwyg_width']	= 640;
			$this->data['wysiwyg_height']	= 300;
			$this->data['wysiwyg_resize']	= TRUE;
			$this->data['wysiwyg_media']	= TRUE;			
			$this->data['wysiwyg']	 		= $this->load->view($this->config->item('dashboard_theme').'/partials/wysiwyg', $this->data, true);
		}
		
		$this->data['form_module']			= 'pages';
		$this->data['form_type']			= 'page';		
		$this->data['form_name']			= 'pages_editor';		
		$this->data['parent_pages'] 		= $this->social_igniter->make_pages_dropdown($this_page_id);
	 	$this->data['content_publisher'] 	= $this->load->view(config_item('dashboard_theme').'/partials/content_publisher', $this->data, true);

 		$this->render('dashboard_wide');
	}
	
	/* Error */
    function error()
	{
		$this->data['page_title'] = 'Oops, Page Not Found';	
		$this->render();	
	}
	
	/* Partials */
	function item_timeline()
	{
		$this->data['item_id']				= '{ITEM_ID}';
		$this->data['item_type']			= '{ACTIVITY_TYPE}';
		
		// Contributor
		$this->data['item_user_id']			= '{ITEM_USER_ID}';
		$this->data['item_avatar']			= '{ITEM_AVATAR}';
		$this->data['item_contributor']		= '{ITEM_CONTRIBUTOR}';
		$this->data['item_profile']			= base_url().'profiles/{ITEM_PROFILE}';
		
		// Activity
		$this->data['item_content']			= '{ITEM_CONTENT}';
		$this->data['item_content_id']		= '{ITEM_CONTENT_ID}';
		$this->data['item_date']			= '{ITEM_DATE}';

 		// Actions
		$this->data['item_comment']			= base_url().'comment/item/{ACTIVITY_ID}';
		$this->data['item_comment_avatar']	= '{ITEM_COMMENT_AVATAR}';
		
	 	
	 	$this->data['item_can_modify']		= '{ITEM_CAN EDIT}';
		$this->data['item_edit']			= base_url().'home/{ACTIVITY_MODULE}/manage/{ITEM_CONTENT_ID}';
		$this->data['item_delete']			= base_url().'status/delete/{ACTIVITY_ID}';			
	
		$this->load->view(config_item('dashboard_theme').'/partials/item_timeline', $this->data);
	}
	
	function item_manage()
	{
		$this->data['item_id'] 				= '{ITEM_ID}';
		$this->data['comments_count']		= '{COMMENTS_COUNT}';
		$this->data['item_type']			= '{ACTIVITY_TYPE}';
		$this->data['title']				= '{ITEM_TITLE}';
		$this->data['title_link']			= base_url().'{MODULE}/view/{ITEM_ID}';
		$this->data['publish_date']			= '{PUBLISHED_DATE}';
		$this->data['status']				= '{ITEM_STATUS}';
		
		$this->data['item_approval']		= '{ITEM_APPROVAL}';
	
		// Actions
		$this->data['item_approve']			= base_url().'api/content/approve/id/{ITEM_ID}';
		$this->data['item_edit']			= base_url().'home/{MODULE}/manage/{ITEM_ID}';
		$this->data['item_delete']			= base_url().'home/{MODULE}/manage/{ITEM_ID}';	
	
		$this->load->view(config_item('dashboard_theme').'/partials/item_manage', $this->data);
	}
	
	/* Partials */
	function category_editor()
	{
		$this->load->view(config_item('dashboard_theme').'/partials/category_editor');
	}
	
	/* Widget Editor */
	function widget_editor()
	{
		if ($this->uri->segment(3)) $editor_type = $this->uri->segment(3);
		else $editor_type = 'standard';
	
		$this->load->view(config_item('dashboard_theme').'/partials/widget_editor_'.$editor_type);
	}
	
	function widget_add()
	{
		$this->load->view(config_item('dashboard_theme').'/partials/widget_editor_add');		
	}

}