<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// ------------------------------------------------------------------------

/**
 * MarcoMonteiro THUMB Helper
 *
 * @package		CodeIgniter
 * @subpackage	Helpers
 * @category	Helpers
 * @author		Marco Monteiro
 * @link		www.marcomonteiro.net
 * @version 	1.0.1
 */

// ------------------------------------------------------------------------

/**
 * Create THUMB
 *
 * @access	public
 * @param	array || string		array of data for the THUMB (just send the image data from upload) || pass the path with filename
 * @param	string				width px => required
 * @param	string				heigh px => optional (if set maintain_ratio = FALSE)
 * @param 	string 				destination path
 * @return	string  			Filename
 */
 
if ( ! function_exists('create_thumb'))
{
	function create_thumb( $data = array() , $thumb_width = '', $thumb_height = '', $destination = '' )
	{

		if( is_array($data) )
		{	
			if ( $data['file_name'] == '' OR $data['full_path'] == '' OR $data['is_image'] == FALSE OR $thumb_width == '' )
			{
				return FALSE;
			}
			if ( ! @is_dir($data['file_path']) )
			{
				return FALSE;
			}
			if ( ! is_writable($data['file_path']) )
			{
				return FALSE;
			}
		}
		else
		{
			if( ! is_file($data) )
			{
				return FALSE;
			}
			else
			{
				$filename = $data;
				unset( $data );
				$data['full_path'] = $filename;
				$data['raw_name'] = pathinfo($filename, PATHINFO_FILENAME);
				$data['file_ext'] = pathinfo($filename, PATHINFO_EXTENSION);
			}
		}

		$CI =& get_instance();
		$CI->load->library('image_lib');

		$config = array(
			'source_image' => $data['full_path'],
			'create_thumb' => TRUE
		);

		if ( $thumb_height == '' )
		{
			$config['maintain_ratio'] = TRUE;
			$config['thumb_marker'] = '_'.$thumb_width.'x'.$thumb_width;
			$config['width'] = $thumb_width;
			$config['height'] = $thumb_width;
		}
		else
		{
			$config['maintain_ratio'] = FALSE;
			$config['thumb_marker'] = '_'.$thumb_width.'x'.$thumb_height;
			$config['width'] = $thumb_width;
			$config['height'] = $thumb_height;
		}
		if ( $destination != '' )
		{
			$config['new_image'] = $destination; 
		}

		$CI->image_lib->initialize( $config );

		if ( ! $CI->image_lib->resize() )
		{
			return FALSE;
		} 

		$result = array(
			'path' => $data['full_path'],
			'thumb_marker' => $config['thumb_marker'],
			'thumb_name' => $data['raw_name'].'_'.$config['thumb_marker'].$data['file_ext']
		);

		$CI->image_lib->clear();

		return $result;
	}
}
