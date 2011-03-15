<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
* Name:			Social Igniter : Install : Config
* Author: 		Brennan Novak
* 		  		contact@social-igniter.com
*         		@brennannovak
*          
* Created: 		Brennan Novak
*
* Project:		http://social-igniter.com/
* Source: 		http://github.com/socialigniter/
*
* Description: 	various values that get installed to the database on installing Social Igniter 
*/
$config['content'] = array('pages', 'page', 'install', 0, 1, 'Welcome', 'welcome', 'Welcome to my website. Word word!', 'index');
$config['content'] = array('pages', 'page', 'install', 1, 1, 'About', 'about', 'Write what your website is about here!', 'site');
$config['content'] = array('pages', 'page', 'install', 2, 1, 'Contact', 'contact', 'Please contact us', 'site');

$config['settings'] = array('site', 'title', 'Awesome Website');
$config['settings'] = array('site', 'tagline', 'Where I Post All My Awesome Things');
$config['settings'] = array('site', 'keywords', 'awesome, things, pictures, videos, poems, watermelons, cats, ninjas');
$config['settings'] = array('site', 'description', 'This is my awesome website where I post awesome stuff. Some of my favorite things are ninjas, watermelons, and cats');
$config['settings'] = array('site', 'url', 'http://domainname.com');
$config['settings'] = array('site', 'admin_email', 'you@email.com');
$config['settings'] = array('site', 'languages_default', 'en');
$config['settings'] = array('themes', 'site_theme', 'site_default');
$config['settings'] = array('themes', 'dashboard_theme', 'dashboard_default');
$config['settings'] = array('themes', 'mobile_theme', 'mobile_default');
$config['settings'] = array('widgets', 'sidebar', '{"module":"users","name":"Login","method":"view","path":"partials/widget_login","enabled":"TRUE","order":"1"}');
$config['settings'] = array('widgets', 'sidebar', '{"module":"widgets","name":"Text","method":"text","path":"","enabled":"TRUE","order":"2","content":"<h2>Hello</h2><p>Thanks for stopping by. We absolutely love visitors. Take off your digital shoes, relax, and feast your eyes on our pretty pixels!</p>"}');
$config['settings'] = array('services', 'email_protocol', 'mail');
$config['settings'] = array('services', 'smtp_host', '');
$config['settings'] = array('services', 'smtp_user', '');
$config['settings'] = array('services', 'smtp_pass', '');
$config['settings'] = array('services', 'smtp_post', '');
$config['settings'] = array('services', 'mobile_enabled', 'TRUE');
$config['settings'] = array('services', 'mobile_module', '');
$config['settings'] = array('services', 'google_webmaster', '');
$config['settings'] = array('services', 'google_analytics', '');
$config['settings'] = array('services', 'bing_webmaster', '');
$config['settings'] = array('services', 'gravatar_enabled', 'TRUE');
$config['settings'] = array('services', 'bitly_enabled', 'TRUE');
$config['settings'] = array('services', 'bitly_login', '');
$config['settings'] = array('services', 'bitly_api_key', '');
$config['settings'] = array('services', 'bitly_domain', 'bit.ly');
$config['settings'] = array('services', 'akismet_key', 'dc0465ba152f');
$config['settings'] = array('services', 'recaptcha_public', '6Lch7LwSAAAAACP2t2e1qpIQ9Cz7AsvXRfJf1yW_');
$config['settings'] = array('services', 'recaptcha_private', '6Lch7LwSAAAAAJvERNehdFPPPZ5TQjd1DgjJRTmK');
$config['settings'] = array('services', 'recaptcha_theme', 'white');
$config['settings'] = array('comments', 'enabled', 'TRUE');
$config['settings'] = array('comments', 'reply', 'TRUE');
$config['settings'] = array('comments', 'reply_level', '2');
$config['settings'] = array('comments', 'comments_date_style', 'SIMPLE_TIME');
$config['settings'] = array('comments', 'email_signup', 'TRUE');
$config['settings'] = array('comments', 'email_replies', 'TRUE');
$config['settings'] = array('comments', 'akismet', 'TRUE');
$config['settings'] = array('comments', 'recaptcha', 'TRUE');
$config['settings'] = array('comments', 'date_style', 'ELAPSED');
$config['settings'] = array('ratings', 'enabled', 'TRUE');
$config['settings'] = array('ratings', 'rate_type', 'TRUE');
$config['settings'] = array('pages', 'enabled', 'TRUE');
$config['settings'] = array('pages', 'ratings_allow', 'TRUE');
$config['settings'] = array('pages', 'tags_display', 'TRUE');
$config['settings'] = array('pages', 'comments_allow', 'TRUE');
$config['settings'] = array('pages', 'comments_per_page', '10');
$config['settings'] = array('home', 'public_timeline', 'TRUE');
$config['settings'] = array('home', 'date_style', 'ELAPSED');
$config['settings'] = array('home', 'status_length', '140');
$config['settings'] = array('home', 'description_length', '110');
$config['settings'] = array('home', 'share', 'TRUE');
$config['settings'] = array('home', 'like', 'TRUE');
$config['settings'] = array('home', 'comments_allow', 'TRUE');
$config['settings'] = array('home', 'comments_per_page', '2');
$config['settings'] = array('users', 'signup', 'TRUE');
$config['settings'] = array('users', 'signup_recaptcha', 'TRUE');
$config['settings'] = array('users', 'login', 'TRUE');
$config['settings'] = array('users', 'login_recaptcha', 'TRUE');
$config['settings'] = array('users', 'profile', 'TRUE');
$config['settings'] = array('users', 'profile_activity', 'TRUE');
$config['settings'] = array('users', 'profile_relationships', 'TRUE');
$config['settings'] = array('users', 'profile_content', 'TRUE');
$config['settings'] = array('users', 'message_allow', 'TRUE');
$config['settings'] = array('users', 'message_recaptcha', '5');
$config['settings'] = array('users', 'comments_allow', 'TRUE');
$config['settings'] = array('users', 'comments_per_page', '10');
$config['settings'] = array('users', 'images_sizes_large', 'yes');
$config['settings'] = array('users', 'images_sizes_medium', 'yes');
$config['settings'] = array('users', 'images_sizes_small', 'yes');
$config['settings'] = array('users', 'images_large_width', '275');
$config['settings'] = array('users', 'images_large_height', '175');
$config['settings'] = array('users', 'images_medium_width', '48');
$config['settings'] = array('users', 'images_medium_height', '48');
$config['settings'] = array('users', 'images_small_width', '45');
$config['settings'] = array('users', 'images_small_height', '25');
$config['settings'] = array('users', 'images_formats', 'gif|jpg|jpeg|png');
$config['settings'] = array('users', 'images_max_size', '25600');
$config['settings'] = array('users', 'images_full_width', '750');
$config['settings'] = array('users', 'images_full_height', '750');
$config['settings'] = array('users', 'images_sizes_full', 'yes');
$config['settings'] = array('users', 'images_folder', 'uploads/profiles/');
$config['settings'] = array('users', 'images_max_dimensions', '3000');
$config['settings'] = array('users', 'images_sizes_original', 'yes');

$config['sites'] = array('http://social-igniter.com', 'default', 'Social-Igniter', 'A Really Simple Open Source Social Web Application Template', 'Social-Igniter is a really simple open source social web application template', 'social, web application, open source, codeigniter, php');

$config['users_level'] = array('superadmin', 'Super Admin', 'Super Admins are the head honchos who have power to do anything they want on your install of Social Igniter');
$config['users_level'] = array('admin', 'Admin', 'Admins can do most things, not all, but most things needed on a site');
$config['users_level'] = array('superuser', 'Super User', 'Supers Users help keep the ship on course, they do some things, but not all');
$config['users_level'] = array('user', 'User', 'Users are just regular Joes or Joesephines. They use your application as it is intended for the general public');
