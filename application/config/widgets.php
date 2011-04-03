<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
* Name:			Widgets : Config
* Author: 		Brennan Novak
* 		  		contact@social-igniter.com
*         		@brennannovak
*          
* Created:		Brennan Novak
*
* Project:		http://social-igniter.com/
* Source: 		http://github.com/socialigniter/
*
* Description: 	widgets.php config file contains all the base site  for Social Igniter
*/

$config['core_widgets'][] = array(
	'regions'	=> array('sidebar', 'content'),
	'widget'	=> array(
		'module'	=> "users",					// Module that partials deals with
		'editor'    => "standard", 				// Route to editor template. is appended to 'home/widget_editor/standard'
		'name'		=> "Users Login",			// Title that is displayed in widget editor
		'method'	=> "view",					// Options: 'text' for simple blob, 'view' to load view, or 'run' for running a module controller route
		'path'		=> "partials/widget_login",	// Path to either partial or controller route
		'enabled'	=> "TRUE",					// If module is enabled or not
		'multiple'	=> "FALSE",
		'order'		=> "1",
		'content'	=> ""
	)
);

$config['core_widgets'][] = array(
	'regions'	=> array('sidebar', 'content', 'wide'),
	'widget'	=> array(
		'module'	=> 'text',
		'editor'    => 'text',
		'name'		=> 'Text',
		'method'	=> 'text',
		'path'		=> '',
		'enabled'	=> 'TRUE',
		'multiple'	=> "TRUE",
		'order'		=> '2',
		'content'	=> '<h2>Hello</h2><p>Thanks for stopping by, we absolutely love visitors. Take off your digital shoes and relax your eyes on our pretty pixels!</p>'
	)
);