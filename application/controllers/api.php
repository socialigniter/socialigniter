<?php
class Api extends Public_Controller 
{ 
    function __construct() 
    {
        parent::__construct();
        
        $this->data['page_title'] = 'Api';
    }
    
    
    function index()
    {
	    $this->data['modules_apis'] = NULL;

		$modules_scan = $this->social_igniter->scan_modules();
		
		foreach ($modules_scan as $module):
		
			$module_api = '/modules/'.$module.'/views/api/index.php';
		
		    if (file_exists(APPPATH.$module_api))
		    {
		    	$this->data['modules_apis'] .= $this->load->view('..'.$module_api, $this->data, true);
		    }		    
			
		endforeach;
    		
   
    	$this->render();
    
    }
    
}

?>