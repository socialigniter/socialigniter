<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
* Name:			Social Igniter : Controller : Ajax
* Author: 		Brennan Novak
* 		  		contact@social-igniter.com
*         		@brennannovak
*          
* Created:		Brennan Novak
* 
* Project:		http://social-igniter.com/
* Source: 		http://github.com/socialigniter/
* 
* Description: 	This funky fresh controller is for 'actions' that meet one of these criteria
*
* - Are only triggered by an AJAX request
* - Done from a public source where a user may be logged in or not
* - Never prints to a normal view- either returns AJAX value or redirects back to request page
*/

class Actions extends MY_Controller 
{
 
    function __construct() 
    {
        parent::__construct();
    }
 
 	function index()
 	{ 
		echo 'Ajax only in this hizzy, best be respectin son!';	
	}
		
	function images()
	{
	
		$this->load->view(config_item('dashboard_theme').'/ajax/images');
	
	}
	


}