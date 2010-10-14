<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
* Form Validation Library
*
* @package		Social Igniter
* @subpackage	Form Validation Library
* @author		Brennan Novak
* @link		http://brennannovak.com
*
* @access	public
* @param	string
* @return	string
*
* Adds form validation for American telephone number validation
*/

class Image_generator
{


	function image_medium($image)
	{

		$config['image_library'] 	= 'gd2';
		$config['source_image']		= './uploads/'.$image;
		$config['dynamic_output']	= false;
		$config['quality']			= '100%';
		$config['new_image']		= './uploads/'.$image.'_medium.png';
		$config['width']	 		= 250;
		$config['height']			= 200;
		$config['create_thumb'] 	= FALSE;
		$config['thumb_marker']		= '';
		$config['maintain_ratio'] 	= TRUE;
		$config['master_dim']		= 'auto';
		
		$this->load->library('image_lib', $config); 		
	
		$this->image_lib->resize($config);

	}


	function image_small($image)
	{

		$config['image_library'] 	= 'gd2';
		$config['source_image']		= './uploads/'.$image;
		$config['dynamic_output']	= false;
		$config['quality']			= '100%';
		$config['new_image']		= './uploads/'.$image.'_small.png';
		$config['width']	 		= 75;
		$config['height']			= 50;
		$config['create_thumb'] 	= FALSE;
		$config['thumb_marker']		= '';
		$config['maintain_ratio'] 	= TRUE;
		$config['master_dim']		= 'auto';
		
		$this->load->library('image_lib', $config); 		
	
		$this->image_lib->resize($config);

	}
		
}	