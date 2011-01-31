<?php

class Image_model3 extends CI_Model 
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
	    
	    $raw_path 			= config_item('uploads_folder').$upload_file;
	    	    
		$image_sizes		= array('full', 'large', 'medium', 'small');
		
		// Loop through sizes... make sure config_items exist for both
		foreach ($image_sizes as $size)
		{
			// If upload width / heights differ from config
			if (($upload_width != config_item('users_images_'.$size.'_width')) || ($upload_height != config_item('users_images_'.$size.'_height')))
			{ 
				$this->make_cropped($upload_file, $upload_width, $upload_height, $user_id, $size);

				//$this->make_resized($upload_file, $user_id, $size);
			}
			
			// NEED TO HANDLE ELSE FOR COPYING SAME SIZE
		
		}			
	    
	    return true;	    
	}
	
	
	function make_cropped($upload_file, $upload_width, $upload_height, $user_id, $size)
	{
	
	    $raw_path = config_item('uploads_folder').$upload_file;	
	
	    $original_width		= 0;
	    $original_height	= 0;
	    
	    // Raw width larger than allowed
		if ($upload_width >= config_item('users_images_'.$size.'_width'))
		{
			$original_width = config_item('users_images_'.$size.'_width');
		}
		else
		{
			$original_width = $upload_width;
		}

	    // Raw height larger than allowed
		if ($upload_height >= config_item('users_images_'.$size.'_height'))
		{
			$original_height = config_item('users_images_'.$size.'_height');
		}
		else
		{
			$original_height = $upload_height;
		}
	       	       
	    // Horizontal or Vertical Picture	    
	    if($upload_width > $upload_height)
	    {
	        $resize_width 	= $original_width; 
	        $resize_height 	= $original_height; 
	        $set_master_dim = 'width';
	    }
	    else
	    {
	        $resize_width 	= $original_width;
	        $resize_height 	= $original_height;
	        $set_master_dim = 'height';
	    }
	    
	    // Calculate Offset
	    if($upload_width > $upload_height)
	    {
	    	// Horizontal picture
	        $diff = $upload_width - $upload_height;
	        $cropsize = $upload_height - 1;
	        $x_axis = round($diff / 2);
	        $y_axis = 0;
	    }
	    else
	    {
	    	// Vertical picture
	        $cropsize = $upload_width - 1;
	        $diff = $upload_height - $upload_width;
	        $x_axis = 0;
	        $y_axis = round($diff / 2);
	    }
	    	    
	    // Largest Possible Cropped Image	 
		$crop_config['image_library']	= 'gd2';
	    $crop_config['source_image'] 	= $raw_path;
	    $crop_config['maintain_ratio']	= FALSE;
	    $crop_config['new_image'] 		= config_item('users_images_folder').$user_id."/".$size."_".$upload_file; 
	    $crop_config['x_axis']		 	= $x_axis;
	    $crop_config['y_axis'] 			= $y_axis;
	    $crop_config['master_dim'] 			= $set_master_dim;
	    $crop_config['width'] 			= $resize_width;
	    $crop_config['height'] 			= $resize_height;
	        
	    $this->image_lib->initialize($crop_config);
	
	    if (!$this->image_lib->resize())
	    {
	        echo "error cropping";
	        echo $this->image_lib->display_errors();
	        return false;
	    }	    
  
  	    $this->image_lib->clear();
  	    
  	    
  	    
 	    $thumb_config['image_library'] 		= 'gd2';
	    $thumb_config['source_image'] 		= config_item('users_images_folder').$user_id."/cropped_".$size."_".$upload_file;;
	    $thumb_config['maintain_ratio'] 	= TRUE;
	    $thumb_config['new_image']			= config_item('users_images_folder').$user_id."/".$size."_".$upload_file;
	    $thumb_config['width'] 				= config_item('users_images_'.$size.'_width');
	    $thumb_config['height'] 			= config_item('users_images_'.$size.'_height');
			
    
	    $this->image_lib->initialize($thumb_config);
	    
	    if (!$this->image_lib->resize())
	    {
	        echo "error resize cropping";
	        echo $this->image_lib->display_errors();
	        return false;
	    }

  	    $this->image_lib->clear();
			unlink(config_item('users_images_folder').$user_id."/cropped_".$size."_".$upload_file);	 	     
	}
	
		
	// Makes an image that does not need cropping
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
	        echo "error resize cropping";
	        echo $this->image_lib->display_errors();
	        return false;
	    }

  	    $this->image_lib->clear();		
	
	}
	
	
}
