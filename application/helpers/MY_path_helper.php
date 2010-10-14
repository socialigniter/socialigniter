<?php
// The helper function doesn't have access to $this, so we need to get a reference to the 
// CodeIgniter instance. We'll store that reference as $CI and use it instead of $this
function asset_images()
{
    $CI =& get_instance();    
    return base_url().$CI->config->item('asset_images');
}

function asset_profiles()
{
    $CI =& get_instance();    
    return base_url().$CI->config->item('asset_profiles');
}

function asset_external()
{
    $CI =& get_instance();    
    return base_url().$CI->config->item('asset_external');
}