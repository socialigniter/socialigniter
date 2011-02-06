<?php

class Image_model extends CI_Model 
{
    function __construct()
    {
        parent::__construct();

		$this->load->helper('file');
	    $this->load->library('image_lib');
    }

	function make_images($upload_file, $upload_width, $upload_height, $user_id)
	{
		make_folder(config_item('users_images_folder').$user_id);		
		delete_files(config_item('users_images_folder').$user_id."/");
 
	    $raw_path 		= config_item('uploads_folder').$upload_file;
		$image_sizes	= array('full', 'large', 'medium', 'small');
		
		// Loop through sizes... make sure config_items exist for both
		foreach ($image_sizes as $size)
		{
			// If upload width / heights differ from config
			if (($upload_width != config_item('users_images_'.$size.'_width')) || ($upload_height != config_item('users_images_'.$size.'_height')))
			{ 
				$this->make_cropped($upload_file, $upload_width, $upload_height, $user_id, $size);
			}
		}			
	    
	    return true;	    
	}
	
	function make_cropped($upload_file, $upload_width, $upload_height, $user_id, $size)
	{
	    $raw_path 			= config_item('uploads_folder').$upload_file;
	    $original_width		= 0;
	    $original_height	= 0;

			$aspect = $upload_width / $upload_height;
			$set_master_dim = '';

	    // Raw width larger than allowed
		if ($upload_width >= config_item('users_images_'.$size.'_width'))
		{
			$original_width = config_item('users_images_'.$size.'_width');
			$set_master_dim = 'width';
		}
		else
		{
			$original_width = $upload_width;
		}

	    // Raw height larger than allowed
		if ($upload_height >= config_item('users_images_'.$size.'_height'))
		{
			$original_height = config_item('users_images_'.$size.'_height');
			$set_master_dim = 'height';
		}
		else
		{
			$original_height = $upload_height;
		}
			
		// in the case where both upload dimensions are larger than the specified size, let the smaller specified dimension govern  
		if ($upload_width >= config_item('users_images_'.$size.'_height') && $upload_height >= config_item('users_images_'.$size.'_height') ) {
			//special case for square images
			if (config_item('users_images_'.$size.'_width')  == config_item('users_images_'.$size.'_height') ) {
				if ($upload_width > $upload_height) {
					$set_master_dim = 'height';
				} else {
					$set_master_dim = 'width';
				}
			} else {
				if (config_item('users_images_'.$size.'_width')  > config_item('users_images_'.$size.'_height') ) {
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
	        //$resize_width 	= config_item('users_images_'.$size.'_width'); 
	        $resize_height 	= round($original_width / $aspect); 
	        //$resize_height 	= round(config_item('users_images_'.$size.'_width') / $aspect); 
	    }
	    else
	    {
	        $resize_width 	= round($original_height * $aspect);
	        //$resize_width 	= round(config_item('users_images_'.$size.'_height') * $aspect);
	        $resize_height 	= $original_height;
	        //$resize_height 	= config_item('users_images_'.$size.'_height');
	    }
	    
	    // Calculate Offset
	    	// Horizontal crop
	        if( $resize_width > config_item('users_images_'.$size.'_width')) {
		        $diff = $resize_width - config_item('users_images_'.$size.'_width');
	        	$x_axis = round($diff / 2);
					} else {
						$x_axis = 0;
					}
	    	// Vertical crop
	        if ($resize_height > config_item('users_images_'.$size.'_height')) {
	       	  $diff = $resize_height - config_item('users_images_'.$size.'_height');
	        	$y_axis = round($diff / 2);
					} else {
						$y_axis = 0;
					}
		
			// define paths	
			$img_output_path =  getcwd() . '/' . config_item('users_images_folder').$user_id."/".$size."_".$upload_file;
			$raw_path = getcwd() . '/' . $raw_path;
			$working_path =  getcwd() . '/' . config_item('users_images_folder').$user_id."/working_".$upload_file;

			if (stristr(PHP_OS, 'WIN')) {
				$raw_path = str_replace('/', '\\', $raw_path);
				$working_path = str_replace('/', '\\', $working_path);
				$img_output_path = str_replace('/', '\\', $img_output_path);
			}
			
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
	    
			//echo "x_axis: $x_axis - y_axis: $y_axis -- resize_width: $resize_width - resize_height: $resize_height - upload_width - $upload_width - upload_height - $upload_height -- constrained width - " . config_item('users_images_'.$size.'_width') .  " constrained_height - " . config_item('users_images_'.$size.'_height') . " master_dim - $set_master_dim<br><br>";
    
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
 /* 	    
		// Makes Cropped Version ???
		// Old Version from Brennans intial resizing lib
 	    $thumb_config['image_library'] 		= 'gd2';
	    $thumb_config['source_image'] 		= config_item('users_images_folder').$user_id."/cropped_".$size."_".$upload_file;;
	    $thumb_config['maintain_ratio'] 	= TRUE;
	    $thumb_config['new_image']			= config_item('users_images_folder').$user_id."/".$size."_".$upload_file;
	    $thumb_config['width'] 				= config_item('users_images_'.$size.'_width');
	    $thumb_config['height'] 			= config_item('users_images_'.$size.'_height');	
    
	    $this->image_lib->initialize($thumb_config);
	    
	    if (!$this->image_lib->resize())
	    {
	        echo "error resize cropping 2";
	        echo $this->image_lib->display_errors();
	        return false;
	    }

  	    $this->image_lib->clear();
  		unlink(config_item('users_images_folder').$user_id."/cropped_".$size."_".$upload_file);
*/  		
  		
	}
	
		
	// Makes Image that does not need cropping
	function make_resized($upload_file, $user_id, $size)
	{	
	    $thumb_config['image_library'] 		= 'gd2';
	    $thumb_config['source_image'] 		= config_item('users_images_folder').$user_id."/cropped_".$size."_".$upload_file;;
	    $thumb_config['maintain_ratio'] 	= TRUE;
	    $thumb_config['new_image']			= config_item('users_images_folder').$user_id."/".$size."_".$upload_file;
	    $thumb_config['width'] 				= config_item('users_images_'.$size.'_width');
	    $thumb_config['height'] 			= config_item('users_images_'.$size.'_height');
	    
	    $this->image_lib->initialize($thumb_config);
	    
	    if (!$this->image_lib->resize())
	    {
	        echo "error resize cropping 3";
	        echo $this->image_lib->display_errors();
	        return false;
	    }

  	    $this->image_lib->clear();		
	}
	
}
