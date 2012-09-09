<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/* load the MX_Router class */
require APPPATH."third_party/MX/Router.php";

class MY_Router extends MX_Router
{
/*
	$this->routes = NULL; //(!isset($route) OR ! is_array($route)) ? array() : $route;
	
	unset($route);
	
	//Get your custom routes:
	$your_routes = array(
		0 => array(
			'match'			=> '(:any)',
			'controller'	=> 'site',
			'action'		=> 'dogs'
		),
		1 => array(
			'match'			=> '(:any)',
			'controller'	=> 'site',
			'action'		=> 'cats'
		),
		2 => array(
			'match'			=> '(:any)',
			'controller'	=> 'blog',
			'action'		=> 'testers'
		)
	);

	foreach($your_routes as $custom_route)
	{
	    $this->routes[$custom_route['match']] = $custom_route['controller'].'/'.$custom_route['action'];
	}
*/

}