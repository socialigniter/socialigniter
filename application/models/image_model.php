<?php

/**
 * Image Model
 * 
 * A model for managing uploaded images
 * 
 * @author Brennan Novak @brennannovak
 * @package Social Igniter\Models
 */
class Image_model extends CI_Model 
{
    function __construct()
    {
        parent::__construct();

		$this->load->helper('file');
	    $this->load->library('image_lib');
    }

	/**
	 * Get Thumbnail
	 * 
	 * Checks if Thumbnail exists, if not generates it, falling back to the default "no image" placeholder
	 * 
	 * @param string $create_path The path to look for/create the thumbnail at
	 * @param string $image_name The name of the image
	 * @param string $module 
	 * @param string $thumb The thumbnail size
	 * @return string Either the path to the thumbnail or to a placeholder
	 */
	function get_thumbnail($create_path, $image_name, $module, $thumb)
	{
		$original	= $create_path.'/'.$image_name;
		$thumbnail	= $create_path.'/'.$thumb.'_'.$image_name;
		$no_image	= 'application/views/'.config_item('site_theme').'/assets/images/'.$thumb.'_'.config_item('no_photo');

		// If Thumbnail Exists
	    if (file_exists($thumbnail))
	    {
			return $thumbnail;
	    }
	    // Make Thumbnail
	    else
	    {
	    	// Original Image Exists
	    	if (file_exists($original))
			{
				// Make Thumbnail
				$thumbnail = $this->make_thumbnail($create_path.'/', $image_name, $module, $thumb);

		    	if ($thumbnail)
		    	{
		    		return $thumbnail;
		    	}
		    	else
		    	{
		    		return $no_image;
		    	}
	    	}
	
	    	return $no_image;	    	
	    }

	   	return $no_image;
	}
	
	/**
	 * Make Thumbnail
	 * 
	 * @param string $create_path The path to create the thumbnail at
	 * @param string $image_name The name of the image (under $create_path) to nailify
	 * @param string module
	 * @param string $thumb The size to create
	 * @return string The path to the newly generated thumbnail
	 */
	function make_thumbnail($create_path, $image_name, $module, $thumb)
	{
	    $raw_path			= $create_path.$image_name;
	    $thubmnail			= $create_path.$thumb.'_'.$image_name;
		$image_dimensions 	= getimagesize($create_path.$image_name);
		$image_file_size	= filesize($create_path.$image_name);

		// Increase Memory If Image is Larger than 2MB file
		if ($image_file_size >= 5120)
		{
			ini_set('memory_limit', '256M');
		}
		elseif ($image_file_size >= 2048)
		{
			ini_set('memory_limit', '128M');
		}

		// If upload width / heights differ from config
		// Generate Proper Crop
		if (($image_dimensions[0] != config_item($module.'_images_'.$thumb.'_width')) || ($image_dimensions[1] != config_item($module.'_images_'.$thumb.'_height')))
		{
			// Make Crop
			$thumbnail = $this->make_cropped($create_path, $image_name, $module, $thumb, $image_dimensions);
		}
		// Generate Non Cropped Image
		else
		{
			$thumbnail = $this->make_copy_existing($create_path, $image_name, $module, $thumb);
		}

		// Delete Original
		if (config_item($module.'_images_sizes_original') == 'no')
		{
			unlink($raw_path);
		}

	    return $thumbnail;
	}

	function make_cropped($create_path, $image_name, $module, $size, $image_dimensions=FALSE)
	{
		// If No Dimensions
		if (!$image_dimensions)
		{
			$image_dimensions = getimagesize($create_path.$image_name);
		}

		$image_width	= $image_dimensions[0];
		$image_height	= $image_dimensions[1];

	    $raw_path 			= $create_path.$image_name;
	    $original_width		= 0;
	    $original_height	= 0;
		$aspect 			= $image_width / $image_height;
		$set_master_dim 	= '';

	    // Raw width larger than allowed
		if ($image_width >= config_item($module.'_images_'.$size.'_width'))
		{
			$original_width = config_item($module.'_images_'.$size.'_width');
			$set_master_dim = 'width';
		}
		else
		{
			$original_width = $image_width;
		}

	    // Raw height larger than allowed
		if ($image_height >= config_item($module.'_images_'.$size.'_height'))
		{
			$original_height = config_item($module.'_images_'.$size.'_height');
			$set_master_dim = 'height';
		}
		else
		{
			$original_height = $image_height;
		}
			
		// In the case where both upload dimensions are larger than the specified size, let the smaller specified dimension govern  
		if ($image_width >= config_item($module.'_images_'.$size.'_height') && $image_height >= config_item($module.'_images_'.$size.'_height')) 
		{
			// Special case for square images
			if (config_item($module.'_images_'.$size.'_width')  == config_item($module.'_images_'.$size.'_height'))
			{
				if ($image_width > $image_height)
				{
					$set_master_dim = 'height';
				}
				else
				{
					$set_master_dim = 'width';
				}
			}
			else
			{
				if (config_item($module.'_images_'.$size.'_width') > config_item($module.'_images_'.$size.'_height'))
				{
					$set_master_dim = 'width';
				}
				else
				{
					$set_master_dim = 'height';
				}
			}
		}

	    // Horizontal or Vertical Picture using config sizes will enlarge small pictures to size 
	    if($set_master_dim == 'width')
	    {
	        $resize_width 	= $original_width; 
	        $resize_height 	= round($original_width / $aspect); 
	    }
	    else
	    {
	        $resize_width 	= round($original_height * $aspect);
	        $resize_height 	= $original_height;
	    }
	    
	    // Calculate Offset & Horizontal Crop
        if( $resize_width > config_item($module.'_images_'.$size.'_width')) 
        {
	        $diff = $resize_width - config_item($module.'_images_'.$size.'_width');
        	$x_axis = round($diff / 2);
		}
		else
		{
			$x_axis = 0;
		}
    	
    	// Vertical Crop
        if ($resize_height > config_item($module.'_images_'.$size.'_height')) 
        {
       	 	$diff = $resize_height - config_item($module.'_images_'.$size.'_height');
        	$y_axis = round($diff / 2);
		}
		else
		{
			$y_axis = 0;
		}
		
		// Define Paths	
		$img_output_path	= getcwd().'/'.$create_path.$size.'_'.$image_name;
		$raw_path			= getcwd().'/'.$raw_path;
		$working_path		= getcwd().'/'.$create_path.'/working_'.$image_name;

/*		Some Windows PHP Thing that may be needed?
		if (stristr(PHP_OS, 'WIN'))
		{
			$raw_path = str_replace('/', '\\', $raw_path);
			$working_path = str_replace('/', '\\', $working_path);
			$img_output_path = str_replace('/', '\\', $img_output_path);
		}
*/
		// Make a working copy
		copy($raw_path, $working_path);

	    // Make Largest Possible Cropped Image
	    $crop_config['quality']				= '100%';
		$crop_config['image_library']		= 'gd2';
	    $crop_config['source_image'] 		= $working_path;
	    $crop_config['maintain_ratio']		= FALSE;
	    if ($x_axis) $crop_config['x_axis']	= $x_axis;
	    if ($y_axis) $crop_config['y_axis'] = $y_axis;
	    $crop_config['width'] 				= $resize_width;
	    $crop_config['height'] 				= $resize_height;

	    $this->image_lib->initialize($crop_config);
	    
		if (!$this->image_lib->resize())
		{
        	log_message('debug', 'error resizing 1');
        	log_message('debug', $this->image_lib->display_errors());
        	return FALSE;
		}

		copy($working_path, $img_output_path);
		unlink($working_path);

		$crop_config['source_image'] 	= $img_output_path;
	    $crop_config['width'] 			= $resize_width - $x_axis;
 	    $crop_config['height'] 			= $resize_height - $y_axis;
				    
  	  	$this->image_lib->clear();
		$this->image_lib->initialize($crop_config);

		if ($x_axis || $y_axis)
		{
			if (!$this->image_lib->crop())
			{
				log_message('debug', $this->image_lib->display_errors());
			    return FALSE;
			}	    
		}	

		$crop_config['rotation_angle'] = 180;

	    $this->image_lib->initialize($crop_config);
 			
		if (!$this->image_lib->rotate())
		{
			log_message('debug', 'error rotation 1');
			log_message('debug', $this->image_lib->display_errors());
			return FALSE;
		}

		$this->image_lib->clear();
		$crop_config['width'] = $crop_config['width'] - $x_axis;
		$crop_config['height'] = $crop_config['height'] - $y_axis;
		unset($crop_config['rotation_angle']);
	    $this->image_lib->initialize($crop_config);

		if (!$this->image_lib->crop())
		{
			log_message('debug', 'error crop 2');
			log_message('debug', $this->image_lib->display_errors());
		}	
  	  
		$this->image_lib->clear();
	    $crop_config['rotation_angle'] = 180;
		$this->image_lib->initialize($crop_config);

		if (!$this->image_lib->rotate())
		{
			log_message('debug', 'error rotation 1');
			log_message('debug', $this->image_lib->display_errors());
			return FALSE;
		}

  		$this->image_lib->clear();

  		// Return Image Exists
 	    $thumbnail = $create_path.$size.'_'.$image_name;

  		if (file_exists($thumbnail))
	    {
		    return $thumbnail;
		}

		return FALSE;
	}


	function make_copy_existing($create_path, $image_name, $module, $size, $image_dimensions=FALSE)
	{
		log_message('debug', 'IMG-SHIT about to copy existing image');

		$raw_image 	= read_file($create_path.$image_name);
		$thumbnail	= $create_path.$size.'_'.$image_name;
		
		// Make Thumbnail Copy
		write_file($thumbnail, $raw_image);

		if (file_exists($thumbnail))
	    {
		    return $thumbnail;
		}

		return FALSE;
	}


	// Saves Remote Image
	function get_external_image($image_full, $image_save)
	{	
	    $ch = curl_init ($image_full);
	    curl_setopt($ch, CURLOPT_HEADER, 0);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
	    $rawdata = curl_exec($ch);
	    curl_close ($ch);
	    
	    if (file_exists($image_save))
	    {
	        unlink($image_save);
	    }
	    
	    $fp = fopen($image_save,'x');

	    fwrite($fp, $rawdata);
	    fclose($fp);
	}
}