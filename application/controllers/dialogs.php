<?php
class Dialogs extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
	}

	function add_connections()
	{
		
	
		$this->load->view(config_item('dashboard_theme').'/dialogs/add_connections', $this->data);
	}

}