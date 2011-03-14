<?php

class Image_model extends CI_Model 
{
    function __construct()
    {
        parent::__construct();

		$this->load->helper('file');
	    $this->load->library('image_lib');
    }

	function make_images($file_data, $module, $image_sizes, $create_path, $delete_files)
	{
		make_folder($create_path);
		
		if ($delete_files)
		{
			delete_files($create_path);
 		}
 
	    $raw_path = config_item('uploads_folder').$file_data['file_name'];
		
		// Loop through sizes...
		foreach ($image_sizes as $size)
		{
			// If upload width / heights differ from config
			if (($file_data['image_width'] != config_item($module.'_images_'.$size.'_width')) || ($file_data['image_height'] != config_item($module.'_images_'.$size.'_height')))
			{ 
				$this->make_cropped($file_data, $module, $create_path, $size);
			}
		}			
	    
	    return true;	    
	}
	
	function make_cropped($file_data, $module, $create_path, $size)
	{
	    $raw_path 			= config_item('uploads_folder').$file_data['file_name'];
	    $original_width		= 0;
	    $original_height	= 0;

			$aspect = $file_data['image_width'] / $file_data['image_height'];
			$set_master_dim = '';

	    // Raw width larger than allowed
		if ($file_data['image_width'] >= config_item($module.'_images_'.$size.'_width'))
		{
			$original_width = config_item($module.'_images_'.$size.'_width');
			$set_master_dim = 'width';
		}
		else
		{
			$original_width = $file_data['image_width'];
		}

	    // Raw height larger than allowed
		if ($file_data['image_height'] >= config_item($module.'_images_'.$size.'_height'))
		{
			$original_height = config_item($module.'_images_'.$size.'_height');
			$set_master_dim = 'height';
		}
		else
		{
			$original_height = $file_data['image_height'];
		}
			
		// in the case where both upload dimensions are larger than the specified size, let the smaller specified dimension govern  
		if ($file_data['image_width'] >= config_item($module.'_images_'.$size.'_height') && $file_data['image_height'] >= config_item($module.'_images_'.$size.'_height') ) {
			//special case for square images
			if (config_item($module.'_images_'.$size.'_width')  == config_item($module.'_images_'.$size.'_height') ) {
				if ($file_data['image_width'] > $file_data['image_height']) {
					$set_master_dim = 'height';
				} else {
					$set_master_dim = 'width';
				}
			} else {
				if (config_item($module.'_images_'.$size.'_width')  > config_item($module.'_images_'.$size.'_height') ) {
					$set_master_dim = 'width';
				} else {
					$set_master_dim = 'height';
				}
			}
		} 

   	       
	    // Horizontal or Vertical Picture	   
		// using config sizes will enlarge small pictures to size 
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
	    
	    // Calculate Offset
    	// Horizontal crop
        if( $resize_width > config_item($module.'_images_'.$size.'_width')) {
	        $diff = $resize_width - config_item($module.'_images_'.$size.'_width');
        	$x_axis = round($diff / 2);
				} else {
					$x_axis = 0;
				}
    	// Vertical crop
        if ($resize_height > config_item($module.'_images_'.$size.'_height')) {
       	  $diff = $resize_height - config_item($module.'_images_'.$size.'_height');
        	$y_axis = round($diff / 2);
				} else {
					$y_axis = 0;
				}
		
			// define paths	
			$img_output_path =  getcwd() . '/' . $create_path.$size."_".$file_data['file_name'];
			$raw_path = getcwd() . '/' . $raw_path;
			$working_path =  getcwd() . '/' . $create_path."/working_".$file_data['file_name'];
/*
			if (stristr(PHP_OS, 'WIN')) {
				$raw_path = str_replace('/', '\\', $raw_path);
				$working_path = str_replace('/', '\\', $working_path);
				$img_output_path = str_replace('/', '\\', $img_output_path);
			}
*/			
			// make a working copy
			copy($raw_path, $working_path); 	    	   
 
	    // Largest Possible Cropped Image	 
			$crop_config['image_library']	= 'gd2';
	    $crop_config['source_image'] 	= $working_path;
	    $crop_config['maintain_ratio']	= FALSE;
	    if ($x_axis) $crop_config['x_axis']		 	= $x_axis;
	    if ($y_axis) $crop_config['y_axis'] 			= $y_axis;
	    $crop_config['width'] 			= $resize_width;
	    $crop_config['height'] 			= $resize_height;
	    
		//echo "x_axis: $x_axis - y_axis: $y_axis -- resize_width: $resize_width - resize_height: $resize_height - upload_width - $file_data['image_width'] - upload_height - $file_data['image_height'] -- constrained width - " . config_item($module.'_images_'.$size.'_width') .  " constrained_height - " . config_item($module.'_images_'.$size.'_height') . " master_dim - $set_master_dim<br><br>";
    
	    $this->image_lib->initialize($crop_config);
	    
			if (!$this->image_lib->resize()) {
	        echo "error resizing 1";
	        echo $this->image_lib->display_errors();
			}

			copy($working_path, $img_output_path);
			unlink($working_path);
	    
			$crop_config['source_image'] 	= $img_output_path;

	    $crop_config['width'] 			= $resize_width - $x_axis;
 	    $crop_config['height'] 			= $resize_height - $y_axis;
				    
  	  $this->image_lib->clear();
			$this->image_lib->initialize($crop_config);

			if ($x_axis || $y_axis) {
		    if (!$this->image_lib->crop())
		    {
		        echo $this->image_lib->display_errors();
		        return false;
		    }	    
			}	

			$crop_config['rotation_angle'] = 180;

	    $this->image_lib->initialize($crop_config);
 			
			if (!$this->image_lib->rotate()) {
				echo "error rotation 1";
				echo $this->image_lib->display_errors();
				return false;
			}

			$this->image_lib->clear();
			$crop_config['width'] = $crop_config['width'] - $x_axis;
			$crop_config['height'] = $crop_config['height'] - $y_axis;
			unset($crop_config['rotation_angle']);
	    $this->image_lib->initialize($crop_config);

			if (!$this->image_lib->crop()) {
				echo "error crop 2";
				echo $this->image_lib->display_errors();
			}	
  	  
			$this->image_lib->clear();
	    $crop_config['rotation_angle'] = 180;
			$this->image_lib->initialize($crop_config);

 			if (!$this->image_lib->rotate()) {
				echo "error rotation 1";
				echo $this->image_lib->display_errors();
				return false;
			}
			
  	   $this->image_lib->clear(); 		
	}
		
	// Makes Image that does not need cropping
	function make_resized($file_data, $module, $create_path, $size)
	{	
	    $thumb_config['image_library'] 		= 'gd2';
	    $thumb_config['source_image'] 		= $create_path."/cropped_".$size."_".$file_data['file_name'];;
	    $thumb_config['maintain_ratio'] 	= TRUE;
	    $thumb_config['new_image']			= $create_path.$size."_".$file_data['file_name'];
	    $thumb_config['width'] 				= config_item($module.'_images_'.$size.'_width');
	    $thumb_config['height'] 			= config_item($module.'_images_'.$size.'_height');
	    
	    $this->image_lib->initialize($thumb_config);
	    
	    if (!$this->image_lib->resize())
	    {
	        echo "error resize cropping 3";
	        echo $this->image_lib->display_errors();
	        return false;
	    }

  	    $this->image_lib->clear();		
	}
	
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