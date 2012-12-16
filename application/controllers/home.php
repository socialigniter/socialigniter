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
 		if ($this->session->userdata('user_level_id') > config_item('home_view_permission')) redirect(login_redirect());

 		$this->load->library('activity_igniter');

	 	$this->data['page_title']	= 'Home';
	 	$this->data['apps']			= '';
	 	
	 	foreach ($this->modules_scan as $app)
	 	{
	 		$app_path = APPPATH.'modules/'.$app.'/app.json';

	 		if (file_exists($app_path))
	 		{
				$this->data['apps'][] = json_decode(file_get_contents($app_path));			
			}
	 	}
	 	
	 	$this->data['activity'] = $this->social_igniter->get_activity_view('site_id', 1, 100);

		$this->render('dashboard_wide');
 	}

 	/* Manage - All modules base manage page when URL is 'home/blog/manage' this method shows all 'content' from specified module */
	function manage()
	{
		if ($this->session->userdata('user_level_id') > 3) redirect(base_url().'home');
	
		$content_module		= $this->social_igniter->get_content_view('module', $this->uri->segment(2), 'all', 150);
		$manage_view 		= NULL;
		$filter_categories	= array('none' => 'By Category');
		$filter_users		= array('none' => 'By User');
		$filter_details		= array('none' => 'By Details');


		// Title Stuff
		$this->data['page_title']	= ucwords($this->uri->segment(2));
		$this->data['sub_title']	= 'Manage';
		
		if (!empty($content_module))
		{		 
			foreach($content_module as $content)
			{
				$this->data['content']				= $content;
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
			
			
				// Build Filter Values
				$filter_categories[$content->category_id]	= $content->category_id;
				$filter_users[$content->user_id] 			= $content->name;
				$filter_details[$content->details]			= $content->details;
			}	
		}
	 	else
	 	{
	 		$manage_view = '<li>Nothing to manage from anyone!</li>';
 		}

		// Final Output
		$this->data['timeline_view'] 		= $manage_view;
		$this->data['module']				= ucwords($this->uri->segment(2));
		$this->data['all_categories']	 	= $this->social_tools->get_categories_view('module', $this->uri->segment(2));
		$this->data['filter_categories'] 	= $filter_categories;
		$this->data['filter_users']			= $filter_users;
		$this->data['filter_details']		= $filter_details;

		$this->render('dashboard_wide');
	}	
 	
	/* Error */
    function error()
	{
		$this->data['page_title'] = 'Oops, Page Not Found';	
		$this->render();	
	}

	/* Partials */	
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
	
	function category_manager()
	{
		// Is Edit or Create
		if ($this->uri->segment(3) != 'create')
		{
			$category = $this->social_tools->get_category($this->uri->segment(3));

			$this->data['category']			= $category->category;
			$this->data['category_url']		= $category->category_url;
			$this->data['description']		= $category->description;
			$this->data['access']			= $category->access;
		}
		else
		{
			$this->data['category']			= '';
			$this->data['category_url']		= '';
			$this->data['description']		= '';
			$this->data['access']			= '';
		}
		
		// Do Parents or Not
		if ($this->uri->segment(4) == 'parent')
		{
			$this->data['category_parents'] = $this->social_tools->make_categories_dropdown(array('categories.type' => $this->uri->segment(5)), $this->session->userdata('user_id'), $this->session->userdata('user_level_id'), '');
		}
		else
		{
			$this->data['category_parents'] = '';		
		}

		$this->load->view(config_item('dashboard_theme').'/partials/category_manager', $this->data);
	}
	
}