<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * API Sandbox
 *
 * Displays API calls for core and includes documentation from modules
 * @package Social Igniter\API
 * @see https://social-igniter.com/api
 */
class Index extends MY_Controller 
{ 
    function __construct() 
    {
        parent::__construct();
                
        $this->data['page_title'] = 'API';
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

		// View
    	$this->data['content'] = $this->load->view('api/core', $this->data, true);
    
       	$this->load->view('api/index', $this->data);
    }
       
}