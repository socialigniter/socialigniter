<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Connections API
 * @package Social Igniter\API
 * @see https://social-igniter.com/api
 */
class Connections extends Oauth_Controller
{
    function __construct()
    {
        parent::__construct();        
	}
	
    function user_get()
    {
        $comments = $this->social_tools->get_comments_recent('all', 10);
        
        if($comments)
        {
            $this->response($comments, 200);
        }
        else
        {
            $this->response(array('status' => 'error', 'message' => 'Could not find any comments'), 200);
        }
    }	
	
	function destroy_authd_get()
	{
		$connection = $this->social_auth->get_connection($this->uri->segment(3));
		
		if ($connection->user_id == $this->session->userdata('user_id'))
		{
			$this->social_auth->delete_connection($connection->connection_id);
			redirect('settings/connections', 'refresh');
		}
	
	}	
	
}