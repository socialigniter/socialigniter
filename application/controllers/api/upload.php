<?php defined('BASEPATH') OR exit('No direct script access allowed');
/* 
 * Upload API : Core : Social-Igniter
 *
 */
class Content extends Oauth_Controller
{
    function __construct()
    {
        parent::__construct(); 
    
    	$this->form_validation->set_error_delimiters('', '');
	}
	
	

}