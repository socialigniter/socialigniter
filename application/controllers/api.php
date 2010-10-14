<?php
require(APPPATH.'/libraries/REST_Controller.php');

class Api extends REST_Controller
{

	function user_get()
    {
        if(!$this->get('id'))
        {
            $this->response(array('error' => 'Bad Request'), 400);
			return;
        }

		$user = $this->api_model->get_user($this->get('id'));
    	
        if($user)
        {
            $this->response($user, 200); // 200 being the HTTP response code
        }

        else
        {
            $this->response(array('error' => 'User could not be found'), 404);
        }
    }
    
    function user_post()
    {
        //$this->some_model->updateUser( $this->get('id') );
        $message = array('id' => $this->get('id'), 
        				 'name' => $this->post('name'), 
        				 'email' => $this->post('email'), 
        				 'message' => 'ADDED!'
        				);
        
        $this->response($message, 200); // 200 being the HTTP response code
    }
    
    function user_delete()
    {
    	//$this->some_model->deletesomething( $this->get('id') );
        $message = array('id' => $this->get('id'), 'message' => 'DELETED!');
        
        $this->response($message, 200); // 200 being the HTTP response code
    }
    
    function users_get()
    {
    
       $users = $this->api_model->get_users();
      /* 
        $users = array(
			array('id' => 1, 'name' => 'Some Guy', 'email' => 'example1@example.com'),
			array('id' => 2, 'name' => 'Person Face', 'email' => 'example2@example.com'),
			array('id' => 3, 'name' => 'Scotty', 'email' => 'example3@example.com'),
		);
      */  
        if($users)
        {
            $this->response($users, 200); // 200 being the HTTP response code
        }

        else
        {
            $this->response(array('error' => 'Couldn\'t find any users!'), 404);
        }
    }

	function location_post()
	{
        if(!$this->get('key'))
        {
            $this->response(array('error' => 'Bad Request'), 400);
			return;
        }
		
		$this->load->library('location');

		$points = $this->_decode_json_post();
		$uuids = array();
		foreach($points as $point)
		{
			$response = $this->location->add_location($this->get('key'), $point);
			if($response == false)
			{
				$this->response(array('response'=>'Device key not found'), 404);
				return;
			}
		}

		$this->response(array('response'=>'ok'), 200);
	}

	function location_get()
	{
        if(!$this->get('key'))
        {
            $this->response(array('error' => 'Bad Request'), 400);
			return;
        }
		
		$this->load->library('location');
		$location = $this->location->get_location($this->get('key'));

		$this->response($location, 200);
	}

	function location_history_get()
	{
		$this->load->library('location');
		
		$points = $this->location->get_history($this->get('key'), array(
			'points' => $this->get('points'), 
			'since' => $this->get('since')
		));
		if($points === false)
		{
			$this->response(array('response'=>'Device key not found'), 404);
			return;
		}

		// Send the points out in sequential order
		$newPoints = array();
		foreach($points as $p)
		{
			// Include this since Javascript can't parse the iso8601 date directly
			$p['timestamp'] = strtotime($p['date']);
			$newPoints[] = $p;
		}
		$points = array_reverse($newPoints);
		
		$this->response(array('points'=>$points), 200);
	}
	
	/**
	 * JSON post data is sent in the request without being wrapped with any standard POST encoding.
	 * It does not show up in the $_POST array since it is not formatted this way. It can only be
	 * read using php://input, so this function reads it and json_decodes it.
	 * 
	 * @return object
	 */
	protected function _decode_json_post()
	{
		$data = file_get_contents("php://input");
		return json_decode($data);
	}
}

?>