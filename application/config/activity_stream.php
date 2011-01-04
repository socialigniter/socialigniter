<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
* Name:			Social Igniter : Activity Stream
* Author: 		Brennan Novak
* 		  		contact@social-igniter.com
*         		@brennannovak
*          
* Created:		Brennan Novak
*
* Project:		http://social-igniter.com/
* Source: 		http://github.com/socialigniter/core
*
* Description: 	activity_stream.php config file is for settings displaying activity stream
*/

// Verbs
$config['verbs']['favorite'] 			= 'http://activitystrea.ms/schema/1.0/favorite';
$config['verbs']['follow'] 				= 'http://activitystrea.ms/schema/1.0/follow';
$config['verbs']['like'] 				= 'http://activitystrea.ms/schema/1.0/like';
$config['verbs']['make-friend'] 		= 'http://activitystrea.ms/schema/1.0/make-friend';
$config['verbs']['join'] 				= 'http://activitystrea.ms/schema/1.0/join';
$config['verbs']['play'] 				= 'http://activitystrea.ms/schema/1.0/play';
$config['verbs']['post'] 				= 'http://activitystrea.ms/schema/1.0/post';
$config['verbs']['save'] 				= 'http://activitystrea.ms/schema/1.0/save';
$config['verbs']['share'] 				= 'http://activitystrea.ms/schema/1.0/share';
$config['verbs']['tag'] 				= 'http://activitystrea.ms/schema/1.0/tag';
$config['verbs']['update'] 				= 'http://activitystrea.ms/schema/1.0/update';
$config['verbs']['rsvp-yes'] 			= 'http://activitystrea.ms/schema/1.0/rsvp-yes';
$config['verbs']['rsvp-maybe'] 			= 'http://activitystrea.ms/schema/1.0/rsvp-maybe';
$config['verbs']['rsvp-no'] 			= 'http://activitystrea.ms/schema/1.0/rsvp-no';

// Object Types
$config['object_types']['article']		= 'http://activitystrea.ms/schema/1.0/article';
$config['object_types']['audio']		= 'http://activitystrea.ms/schema/1.0/audio';
$config['object_types']['bookmark']		= 'http://activitystrea.ms/schema/1.0/bookmark';
$config['object_types']['comment']		= 'http://activitystrea.ms/schema/1.0/comment';
$config['object_types']['file']			= 'http://activitystrea.ms/schema/1.0/file';
$config['object_types']['folder']		= 'http://activitystrea.ms/schema/1.0/folder';
$config['object_types']['group']		= 'http://activitystrea.ms/schema/1.0/group';
$config['object_types']['list']			= 'http://activitystrea.ms/schema/1.0/list';
$config['object_types']['note']			= 'http://activitystrea.ms/schema/1.0/note';
$config['object_types']['person']		= 'http://activitystrea.ms/schema/1.0/person';
$config['object_types']['photo']		= 'http://activitystrea.ms/schema/1.0/photo';
$config['object_types']['photo-album']	= 'http://activitystrea.ms/schema/1.0/photo-album';
$config['object_types']['place']		= 'http://activitystrea.ms/schema/1.0/place';
$config['object_types']['playlist']		= 'http://activitystrea.ms/schema/1.0/playlist';
$config['object_types']['product']		= 'http://activitystrea.ms/schema/1.0/product';
$config['object_types']['review']		= 'http://activitystrea.ms/schema/1.0/review';
$config['object_types']['service']		= 'http://activitystrea.ms/schema/1.0/service';
$config['object_types']['status']		= 'http://activitystrea.ms/schema/1.0/status';
$config['object_types']['video']		= 'http://activitystrea.ms/schema/1.0/video';
$config['object_types']['event']		= 'http://activitystrea.ms/schema/1.0/event';

// Social Igniter Object Types
$config['object_types']['page'] 		= '';
$config['object_types']['class'] 		= '';
$config['object_types']['image']		= '';
$config['object_types']['image-album']	= '';