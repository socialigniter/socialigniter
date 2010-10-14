<?php
class Mobile extends Public_Controller {

    function __construct() 
    {
        parent::__construct();

    }	

	function index()
	{

		redirect(base_url());
	
	}

	function voice()
	{

						
	
	}

	function sms()
	{

		// Checks is number in database and verified		
		
		// Check is number in database not verified
				
		// Is New User Signup	

		// include the PHP TwilioRest library
		//require("application/libraries/Twilio.php");
		$this->load->library('twilio');
	
		// twilio REST API version
	    
		// instantiate a new Twilio Rest Client
//		$client = new TwilioRestClient($this->config->item('twilio_account_sid'), $this->config->item('twilio_auth_token'));
	
//		$this->twilio->request();
		
		// make an associative array of people we know, indexed by phone number
		$people = array("3104023675"=>"Brennan",);
			
			
		// iterate over all our friends
		foreach ($people as $number => $name) {
	
			// Send a new outgoinging SMS by POST'ing to the SMS resource */
			$response = $this->twilio->request($this->config->item('twilio_api_version')."/Accounts/".$this->config->item('twilio_account_sid')."/SMS/Messages", 
				"POST", array(
				"To" => $number,
				"From" => "360-262-6062",
				"Body" => "Bring some banans for the monkey dorko $name!"
			));
			if($response->IsError)
				echo "Error: {$response->ErrorMessage}";
			else
				echo "Sent message to $name";
	
	    }	

		/*	
	    $this->output->set_header('Content-type: text/xml');

		echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
		echo "<Response>\n";
		echo	"<Sms>Hey ".$TxtName.", thanks for registering to be an Eco-Hero! In the next 20 days we will email you instructions on how to save the world</Sms>\n";
		echo "</Response>\n";
		*/	
	
	}

	
}