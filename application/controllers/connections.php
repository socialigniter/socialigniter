<?php
class Connections extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
	}	
	
	function index()
	{
		redirect(base_url());
	}
	
	function delete()
	{
		$connection = $this->social_auth->get_connection($this->uri->segment(3));
		
		if ($connection->user_id == $this->session->userdata('user_id'))
		{
			$this->social_auth->delete_connection($connection->connection_id);
			$this->social_auth->set_userdata_connections($this->session->userdata('user_id'));
			redirect('settings/connections', 'refresh');
		}
	
	}	

}