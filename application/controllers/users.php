<?php
class Users extends Dashboard_Controller {
 
    function __construct() 
    {
        parent::__construct();
        
		// If not Super redirect
		if ($this->data['logged_user_level_id'] > 2) redirect('home');	        
         
 	    $this->data['page_title'] = "Users";
    }
 
 	function index()
 	{   		
		// Get Users
		$users 			= $this->social_auth->get_users();
		$users_view 	= NULL;

//		print_r($users);

		// Title Stuff
		$this->data['page_title']	= 'Users';
		$this->data['sub_title']	= 'Manage';

		 
		foreach($users as $user):
		
			$this->data['user_id'] 			= $user->user_id;
			$this->data['name']				= $user->name;
			$this->data['avatar']			= $this->social_igniter->profile_image($user->user_id, $user->image, $user->email);
			$this->data['profile']			= base_url().'profile/'.$user->username;
			$this->data['created_on']		= format_datetime('SIMPLE_ABBR', $user->created_on);
			$this->data['last_login']		= format_datetime('SIMPLE_TIME_ABBR', $user->last_login);

			// Alerts
//			$this->data['item_alerts']		= item_alerts_content($content);			
			
			// Actions
			$this->data['user_state']		= item_user_state($user->active);
			$this->data['user_edit']		= base_url().'users/manage/'.$user->user_id;
			$this->data['user_delete']		= base_url().'api/users/destroy/id/'.$user->user_id;
			
			// View
			$users_view .= $this->load->view(config_item('dashboard_theme').'/partials/item_users.php', $this->data, true);			

		endforeach;	


		// Final Output
		$this->data['users_view'] 	= $users_view;	
        $this->data['message'] 		= (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
        
	    $this->render('dashboard_wide');
 	}
 	
  	// Create a new user
	function create() 
	{
        $this->data['sub_title'] = "Create";				
		$this->data['users_levels'] = $this->social_auth->get_users_levels();		
				
        //validate form input
    	$this->form_validation->set_rules('level', 'Level', 'required');
    	$this->form_validation->set_rules('name', 'Name', 'required');
    	$this->form_validation->set_rules('email', 'Email Address', 'required|valid_email');
    	$this->form_validation->set_rules('phone', 'Phone', 'xss_clean|min_length[3]|max_length[16]');
    	$this->form_validation->set_rules('company', 'Company Name');
    	$this->form_validation->set_rules('password', 'Password', 'required|min_length['.$this->config->item('min_password_length').']|max_length['.$this->config->item('max_password_length').']|matches[password_confirm]');
    	$this->form_validation->set_rules('password_confirm', 'Password Confirmation', 'required');

        if ($this->form_validation->run() == true) { //check to see if we are creating the user
			
			if ($this->input->post('username') == "")
			{
				$username	= url_title($this->input->post('name'), 'none', true);
			}
			else
			{
				$username	= url_title($this->input->post('username'), 'none', true);
			}	
        	$email		= $this->input->post('email');
        	$password	= $this->input->post('password');
        	$level		= $this->input->post('level');
        	
        	$additional_data = array('name' 	=> $this->input->post('name'),
        							 'company'  => $this->input->post('company'),
        							 'phone'    => $this->input->post('phone'),
        							 'detail_1' => $this->input->post('detail_1'),
        							 'detail_2' => $this->input->post('detail_2'),
        							 'detail_3' => $this->input->post('detail_3'),
        							 'detail_4' => $this->input->post('detail_4')
        							);
        	
        	//register the user
        	$this->social_auth->register($username, $password, $email, $additional_data, $level);
        	
        	//redirect them back to the admin page
        	$this->session->set_flashdata('message', "User Created");
        	
       		redirect(base_url()."users", 'refresh');
		} 
		else { //display the create user form
	        //set the flash data error message if there is one
	        $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			
			$this->data['name']		          = "";
			$this->data['username']		      = "";
            $this->data['email']              = "";
		    $this->data['password']           = "";
            $this->data['password_confirm']   = "";
            $this->data['phone']              = "";           
            $this->data['company']            = "";
            $this->data['detail_1']           = "";
            $this->data['detail_2']           = "";
            $this->data['detail_3']           = "";
            $this->data['detail_4']           = "";
	
		}
		
		
		$this->render('dashboard_wide');


    }
    
    function edit()
    {



		$this->render();
		
    }
    
    function delete()
    {

		$this->render();

    }
    

	//activate the user
	function activate($id, $code=false) 
	{        
		$activation = $this->social_auth->activate($id, $code);
		
        if ($activation) {
			//redirect them to the auth page
	        $this->session->set_flashdata('message', "Account Activated");
	        redirect("login", 'refresh');
        }
        else {
			//redirect them to the forgot password page
	        $this->session->set_flashdata('message', "Unable to Activate");
	        redirect("login/forgot_password", 'refresh');
        }
    }
    
    //deactivate the user
	function deactivate($id) 
	{        
		if ($this->social_auth->logged_in() && $this->social_auth->is_admin()) {
	        //de-activate the user
	        $this->social_auth->deactivate($id);
		} 
        //redirect them back to the auth page
        redirect("login", 'refresh');
    }
    
    function levels()
    {
    
    	$this->render('dashboard_wide');
    }    
 	
  
}
