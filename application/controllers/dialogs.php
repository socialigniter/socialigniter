<?php
class Dialogs extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
	}

	/* Connections */
	function add_connections()
	{
		$this->data['social_connections']	= $this->social_igniter->get_settings_setting_value('social_connection', 'TRUE');
		$this->data['user_connections']		= $this->social_auth->get_connections_user($this->session->userdata('user_id'));

		$this->load->view(config_item('dashboard_theme').'/dialogs/add_connections', $this->data);
	}


	/* Settings */
	function mobile_phone_editor()
	{
		$this->load->view(config_item('dashboard_theme').'/dialogs/mobile_phone_editor');
	}

	function mobile_phone_verify()
	{
		$this->load->view(config_item('dashboard_theme').'/dialogs/mobile_phone_verify');
	}


	/* Widgets */
	function widget_editor()
	{
		$this->load->view(config_item('dashboard_theme').'/dialogs/widget_editor_simple');
	}

	function widget_add()
	{
		$this->load->view(config_item('dashboard_theme').'/dialogs/widget_add');		
	}

}