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

$config['widgets']['sidebar'][] = array(
	'module'	=> "users",					// Module that partials deals with
	'editor'    => "standard", 				// Route to editor template. is appended to 'home/widget_editor/standard'
	'name'		=> "Users Login",			// Title that is displayed in widget editor
	'method'	=> "view",					// Options: 'text' for simple blob, 'view' to load view, or 'run' for running a module controller route
	'path'		=> "partials/widget_login",	// Path to either partial or controller route
	'enabled'	=> "TRUE",					// If module is enabled or not
	'multiple'	=> "FALSE",
	'order'		=> "1",
	'content'	=> ""
);

$config['widgets']['sidebar'][] = array(
	'module'	=> 'text',
	'editor'    => 'text',
	'delete'	=> 'TRUE',
	'name'		=> 'Text',
	'method'	=> 'text',
	'path'		=> '',
	'enabled'	=> 'TRUE',
	'order'		=> '2',
	'title'		=> 'Hello',
	'content'	=> 'Thanks for stopping by, we absolutely love visitors. Take off your digital shoes and relax your eyes on our pretty pixels!'
);