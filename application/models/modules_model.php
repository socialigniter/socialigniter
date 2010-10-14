<?php

class Modules_model extends CI_Model {
    
    function __construct()
    {
        parent::__construct();
    }
 
	function get_modules_views()
	{
	
        $modules_scan = directory_map('./application/modules/', TRUE);
        
        foreach ($modules_scan as $module):

        	$module_header 		= '/modules/'.$module.'/views/partials/header.php';
        	$module_navigation	= '/modules/'.$module.'/views/partials/navigation.php';
        	$module_content 	= '/modules/'.$module.'/views/partials/content.php';
        	$module_sidebar 	= '/modules/'.$module.'/views/partials/sidebar.php';
        	$module_footer 		= '/modules/'.$module.'/views/partials/footer.php';

            if (file_exists(APPPATH.$module_header))
            {
            	$this->data['modules_header'] 		.= $this->load->view('..'.$module_header, $this->data, true);
            }
            if (file_exists(APPPATH.$module_navigation))
            {
            	$this->data['modules_navigation'] 	.= $this->load->view('..'.$module_navigation, $this->data, true);
            }
            if (file_exists(APPPATH.$module_content))
            {
            	$this->data['modules_content'] 		.= $this->load->view('..'.$module_content, $this->data, true);
            }
            if (file_exists(APPPATH.$module_sidebar))
            {
            	$this->data['modules_sidebar'] 		.= $this->load->view('..'.$module_sidebar, $this->data, true);
            }
            if (file_exists(APPPATH.$module_footer))
            {
            	$this->data['modules_footer'] 		.= $this->load->view('..'.$module_footer, $this->data, true);        	
        	}
        	
        endforeach;  	
	
	}
	
	function get_module_settings($module=NULL)
	{
	
 		$this->db->select('*');
 		$this->db->from('settings');
 		$this->db->where('');
 		$result = $this->db->get();
 		return $result->result();

	}	
    
}