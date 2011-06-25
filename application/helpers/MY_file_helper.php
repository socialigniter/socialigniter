<?php
/**
* View Helper
*
* @package		Social Igniter
* @subpackage	File Helper
* @author		Brennan Novak
* @link			http://brennannovak.com
*
* @access	public
* @param	string
* @return	boolean
*
* Offers more file & folder options than the normal File Helper
*/
function make_folder($folder_name)
{
	if(!is_dir($folder_name))
	{
		mkdir($folder_name, 0777);
		chmod($folder_name, 0777);	
	}
	
	return FALSE;
}

function recursive_chmod($path, $filePerm=0644, $dirPerm=0755)
{
	// Check if the path exists
	if(!file_exists($path))
	{
		return(FALSE);
	}
	
	// See whether this is a file
	if(is_file($path))
	{
		// Chmod the file with our given filepermissions
		chmod($path, $filePerm);
		// If this is a directory...
	} 
	elseif(is_dir($path)) 
	{
		// Then get an array of the contents
		$foldersAndFiles = scandir($path);
		// Remove "." and ".." from the list
		$entries = array_slice($foldersAndFiles, 2);
		// Parse every result...
		foreach($entries as $entry)
		{
		// And call this function again recursively, with the same permissions
		recursive_chmod($path."/".$entry, $filePerm, $dirPerm);
		}
		// When we are done with the contents of the directory, we chmod the directory itself
		chmod($path, $dirPerm);
	}
	// Everything seemed to work out well, return TRUE
	return(TRUE);
}

function recursive_rmdir($dir)
{ 
	if (is_dir($dir))
	{
		$objects = scandir($dir); 
		foreach ($objects as $object)
		{ 
			if ($object != "." && $object != "..")
			{ 
				if (filetype($dir."/".$object) == "dir") rrmdir($dir."/".$object); else unlink($dir."/".$object); 
			}
		}
		
		reset($objects); 
		rmdir($dir); 
	} 
}