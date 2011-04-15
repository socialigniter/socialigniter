<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
* Name:			Social Igniter : Core : Widgets
* Author: 		Brennan Novak
* 		  		contact@social-igniter.com
*         		@brennannovak
*          
* Project:		http://social-igniter.com/
* Source: 		http://github.com/socialigniter/
*
* Description: 	Widgets in core install of Social Igniter
*/

$config['core_widgets'][] = array(
	'regions'	=> array('sidebar', 'content'),	// Regions in a layout for an App. Make sure the region exists in the Theme / Layout
	'widget'	=> array(
		'module'	=> 'users',					// Module that partials deals with
		'name'		=> 'Login',					// Title that is displayed in widget editor
		'method'	=> 'view',					// Options: 'text' for simple blob, 'view' to load view, or 'run' for running a module controller route
		'path'		=> 'login',					// Path to either partial or controller route
		'multiple'	=> 'FALSE',					// Can you install multiple instances of this widget in a given region
		'order'		=> '1',						// Order of widget in relation to other widgets
		'content'	=> ''						// Simple text blob if widget is set to display it
	)
);

$config['core_widgets'][] = array(
	'regions'	=> array('sidebar', 'content', 'wide'),
	'widget'	=> array(
		'module'	=> 'text',
		'name'		=> 'Text',
		'method'	=> 'text',
		'path'		=> '',
		'multiple'	=> 'TRUE',
		'order'		=> '2',
		'content'	=> '<h2>Hello</h2><p>Thanks for stopping by, we absolutely love visitors. Take off your digital shoes and relax your eyes on our pretty pixels!</p>'
	)
);