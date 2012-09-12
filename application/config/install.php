<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @name Social Igniter : Install : Config
 * @author Brennan Novak
 * @contact contact@social-igniter.com
 * @projectURL http://social-igniter.com/
 * @source http://github.com/socialigniter/
 * @description various values that get installed to the database on installing Social Igniter 
 **/

/* Database Tables */

$config['database_activity_table'] = array(
	'activity_id' => array(
		'type' 					=> 'INT',
		'constraint' 			=> 32,
		'unsigned' 				=> TRUE,
		'auto_increment'		=> TRUE
	),
	'site_id' => array(
		'type' 					=> 'INT',
		'constraint' 			=> '6',
		'null'					=> TRUE
	),
	'user_id' => array(
		'type' 					=> 'INT',
		'constraint'			=> 11,
		'null' 					=> TRUE
	),
	'verb' => array(
		'type'					=> 'CHAR',
		'constraint'			=> 32,
		'null'					=> TRUE
	),
	'module' => array(
		'type'					=> 'VARCHAR',
		'constraint'			=> 64,
		'null'					=> TRUE
	),
	'type' => array(
		'type'					=> 'VARCHAR',
		'constraint'			=> 32,
		'null'					=> TRUE
	),
	'content_id' => array(
		'type'					=> 'INT',
		'constraint'			=> 11,
		'null'					=> TRUE
	),
	'data' => array(
		'type'					=> 'TEXT',
		'null'					=> TRUE
	),
	'status' => array(
		'type'					=> 'CHAR',
		'constraint'			=> 1,
		'null'					=> TRUE
	),
	'created_at' => array(
		'type'					=> 'DATETIME',
		'null'					=> TRUE
	)						
);

$config['database_categories_table'] = array(
	'category_id' => array(
		'type' 					=> 'INT',
		'constraint' 			=> 11,
		'unsigned' 				=> TRUE,
		'auto_increment'		=> TRUE
	),
	'parent_id' => array(
		'type'					=> 'INT',
		'constraint'			=> 11,
		'null'					=> TRUE
	),
	'content_id' => array(
		'type'					=> 'INT',
		'constraint'			=> 11,
		'null'					=> TRUE
	),
	'site_id' => array(
		'type'					=> 'INT',
		'constraint'			=> 6,
		'null'					=> TRUE
	),
	'user_id' => array(
		'type'					=> 'INT',
		'constraint'			=> 11,
		'null'					=> TRUE
	),
	'access' => array(
		'type'					=> 'CHAR',
		'constraint'			=> 1,
		'null'					=> TRUE
	),
	'module' => array(
		'type'					=> 'VARCHAR',
		'constraint'			=> 32,
		'null'					=> TRUE
	),
	'type' => array(
		'type'					=> 'VARCHAR',
		'constraint'			=> 32,
		'null'					=> TRUE
	),
	'category' => array(
		'type'					=> 'VARCHAR',
		'constraint'			=> 128,
		'null'					=> TRUE
	),
	'category_url' => array(
		'type'					=> 'VARCHAR',
		'constraint'			=> 128,
		'null'					=> TRUE
	),
	'description' => array(
		'type'					=> 'TEXT',
		'null'					=> TRUE
	),
	'details' => array(
		'type'					=> 'VARCHAR',
		'constraint'			=> 512,
		'null'					=> TRUE
	),
	'contents_count' => array(
		'type'					=> 'INT',
		'constraint'			=> 6,
		'null'					=> TRUE
	),
	'created_at' => array(
		'type'					=> 'DATETIME',
		'default'				=> '9999-12-31 00:00:00' 
	),
	'updated_at' => array(
		'type'					=> 'DATETIME',
		'default'				=> '9999-12-31 00:00:00' 
	)
);

$config['database_connections_table'] = array(
	'connection_id' => array(
		'type' 					=> 'INT',
		'constraint' 			=> 16,
		'unsigned' 				=> TRUE,
		'auto_increment'		=> TRUE
	),
	'site_id' => array(
		'type'					=> 'INT',
		'constraint'			=> 6,
		'null'					=> TRUE
	),
	'user_id' => array(
		'type'					=> 'INT',
		'constraint'			=> 11,
		'null'					=> TRUE
	),
	'module' => array(
		'type'					=> 'VARCHAR',
		'constraint'			=> 32,
		'null'					=> TRUE
	),
	'type' => array(
		'type'					=> 'VARCHAR',
		'constraint'			=> 32,
		'null'					=> TRUE
	),
	'connection_user_id' => array(
		'type'					=> 'VARCHAR',
		'constraint'			=> 255,
		'null'					=> TRUE
	),
	'connection_username' => array(
		'type'					=> 'VARCHAR',
		'constraint'			=> 255,
		'null'					=> TRUE
	),
	'auth_one' => array(
		'type'					=> 'VARCHAR',
		'constraint'			=> 255,
		'null'					=> TRUE
	),
	'auth_two' => array(
		'type'					=> 'VARCHAR',
		'constraint'			=> 255,
		'null'					=> TRUE
	),
	'created_at' => array(
		'type'					=> 'DATETIME',
		'null'					=> TRUE,
		'default'				=> '9999-12-31 00:00:00'				
	),
	'updated_at' => array(
		'type'					=> 'DATETIME',
		'null'					=> TRUE,
		'default'				=> '9999-12-31 00:00:00'				
	)																							
);

$config['database_content_table'] = array(
	'content_id' => array(
		'type' 					=> 'INT',
		'constraint' 			=> 11,
		'unsigned' 				=> TRUE,
		'auto_increment'		=> TRUE
	),
	'site_id' => array(
		'type'					=> 'INT',
		'constraint'			=> 6,
		'null'					=> TRUE
	),
	'parent_id' => array(
		'type'					=> 'INT',
		'constraint'			=> 11,
		'null'					=> TRUE
	),
	'category_id' => array(
		'type'					=> 'INT',
		'constraint'			=> 16,
		'null'					=> TRUE
	),
	'module' => array(
		'type'					=> 'VARCHAR',
		'constraint'			=> 32,
		'null'					=> TRUE
	),
	'type' => array(
		'type'					=> 'VARCHAR',
		'constraint'			=> 32,
		'null'					=> TRUE
	),
	'source' => array(
		'type'					=> 'CHAR',
		'constraint'			=> 16,
		'null'					=> TRUE
	),
	'order' => array(
		'type'					=> 'INT',
		'constraint'			=> 8,
		'null'					=> TRUE
	),
	'user_id' => array(
		'type'					=> 'INT',
		'constraint'			=> 11,
		'null'					=> TRUE
	),
	'title' => array(
		'type'					=> 'VARCHAR',
		'constraint'			=> 255,
		'null'					=> TRUE
	),
	'title_url' => array(
		'type'					=> 'VARCHAR',
		'constraint'			=> 255,
		'null'					=> TRUE
	),
	'content' => array(
		'type'					=> 'TEXT',
		'null'					=> TRUE
	),									
	'details' => array(
		'type'					=> 'VARCHAR',
		'constraint'			=> 512,
		'null'					=> TRUE
	),
	'canonical' => array(
		'type'					=> 'VARCHAR',
		'constraint'			=> 512,
		'null'					=> TRUE
	),
	'access' => array(
		'type'					=> 'CHAR',
		'constraint'			=> 1,
		'null'					=> TRUE
	),			
	'comments_allow' => array(
		'type'					=> 'CHAR',
		'constraint'			=> 1,
		'null'					=> TRUE
	),
	'comments_count' => array(
		'type'					=> 'INT',
		'constraint'			=> 8,
		'null'					=> TRUE
	),				
	'geo_lat' => array(
		'type'					=> 'VARCHAR',
		'constraint'			=> 16,
		'null'					=> TRUE
	),
	'geo_long' => array(
		'type'					=> 'VARCHAR',
		'constraint'			=> 16,
		'null'					=> TRUE
	),
	'viewed' => array(
		'type'					=> 'CHAR',
		'constraint'			=> 1,
		'null'					=> TRUE
	),
	'approval' => array(
		'type'					=> 'CHAR',
		'constraint'			=> 1,
		'null'					=> TRUE
	),
	'status' => array(
		'type'					=> 'CHAR',
		'constraint'			=> 1,
		'null'					=> TRUE
	),												
	'created_at' => array(
		'type'					=> 'DATETIME',
		'null'					=> TRUE,
		'default'				=> '9999-12-31 00:00:00'				
	),
	'updated_at' => array(
		'type'					=> 'DATETIME',
		'null'					=> TRUE,
		'default'				=> '9999-12-31 00:00:00'				
	)																				
);

$config['database_content_meta_table'] = array(
	'content_meta_id' => array(
		'type' 				=> 'INT',
		'constraint' 		=> 32,
		'unsigned' 			=> TRUE,
		'auto_increment'	=> TRUE
	),
	'site_id' => array(
		'type'				=> 'INT',
		'constraint'		=> 6,
		'null'				=> TRUE
	),
	'content_id' => array(
		'type'				=> 'INT',
		'constraint'		=> 11,
		'null'				=> TRUE
	),
	'meta' => array(
		'type'				=> 'VARCHAR',
		'constraint'		=> 128,
		'null'				=> TRUE
	),
	'value' => array(
		'type'				=> 'TEXT',
		'null'				=> TRUE
	),						
	'created_at' => array(
		'type'				=> 'DATETIME',
		'null'				=> TRUE,
		'default'			=> '9999-12-31 00:00:00'				
	),
	'updated_at' => array(
		'type'				=> 'DATETIME',
		'null'				=> TRUE,
		'default'			=> '9999-12-31 00:00:00'				
	)			
);

$config['database_relationships_table'] = array(
	'relationship_id' => array(
		'type' 					=> 'INT',
		'constraint' 			=> 32,
		'unsigned' 				=> TRUE,
		'auto_increment'		=> TRUE
	),
	'site_id' => array(
		'type'					=> 'INT',
		'constraint'			=> 8,
		'null'					=> TRUE
	),
	'owner_id' => array(
		'type'					=> 'INT',
		'constraint'			=> 11,
		'null'					=> TRUE
	),
	'user_id' => array(
		'type'					=> 'INT',
		'constraint'			=> 11,
		'null'					=> TRUE
	),
	'module' => array(
		'type'					=> 'VARCHAR',
		'constraint'			=> 32,
		'null'					=> TRUE
	),
	'type' => array(
		'type'					=> 'VARCHAR',
		'constraint'			=> 32,
		'null'					=> TRUE
	),
	'status' => array(
		'type'					=> 'CHAR',
		'constraint'			=> 1,
		'null'					=> TRUE
	),
	'created_at' => array(
		'type'					=> 'DATETIME',
		'null'					=> TRUE
	),
	'updated_at' => array(
		'type'					=> 'DATETIME',
		'null'					=> TRUE
	)
);

$config['database_settings_table'] = array(
	'settings_id' => array(
		'type' 					=> 'INT',
		'constraint' 			=> 32,
		'unsigned' 				=> TRUE,
		'auto_increment'		=> TRUE
	),
	'site_id' => array(
		'type'					=> 'INT',
		'constraint'			=> 8,
		'null'					=> TRUE
	),
	'module' => array(
		'type'					=> 'VARCHAR',
		'constraint'			=> 32,
		'null'					=> TRUE
	),
	'setting' => array(
		'type'					=> 'VARCHAR',
		'constraint'			=> 255,
		'null'					=> TRUE
	),
	'value' => array(
		'type'					=> 'TEXT',
		'null'					=> TRUE
	)									
);

$config['database_sites_table'] = array(
	'site_id' => array(
		'type' 					=> 'INT',
		'constraint' 			=> 8,
		'unsigned' 				=> TRUE,
		'auto_increment'		=> TRUE
	),
	'url' => array(
		'type'					=> 'VARCHAR',
		'constraint'			=> 255,
		'null'					=> TRUE
	),
	'module' => array(
		'type'					=> 'VARCHAR',
		'constraint'			=> 255,
		'null'					=> TRUE
	),
	'type' => array(
		'type'					=> 'VARCHAR',
		'constraint'			=> 255,
		'null'					=> TRUE
	),
	'title' => array(
		'type'					=> 'VARCHAR',
		'constraint'			=> 255,
		'null'					=> TRUE
	),
	'favicon' => array(
		'type'					=> 'VARCHAR',
		'constraint'			=> 255,
		'null'					=> TRUE
	)									
);

$config['database_uploads_table'] = array(
	'upload_id' => array(
		'type' 					=> 'INT',
		'constraint' 			=> 32,
		'unsigned' 				=> TRUE,
		'auto_increment'		=> TRUE
	),
	'user_id' => array(
		'type'					=> 'INT',
		'constraint'			=> 11,
		'null'					=> TRUE
	),
	'consumer_key' => array(
		'type'					=> 'VARCHAR',
		'constraint'			=> 48,
		'null'					=> TRUE
	),
	'file_hash' => array(
		'type'					=> 'VARCHAR',
		'constraint'			=> 48,
		'null'					=> TRUE
	),
	'status' => array(
		'type'					=> 'CHAR',
		'constraint'			=> 1,
		'null'					=> TRUE
	),
	'uploaded_at' => array(
		'type'					=> 'DATETIME',
		'null'					=> FALSE
	)
);

$config['database_users_table'] = array(
	'user_id' => array(
		'type' 					=> 'INT',
		'constraint' 			=> 11,
		'unsigned' 				=> TRUE,
		'auto_increment'		=> TRUE
	),
	'user_level_id' => array(
		'type'					=> 'INT',
		'constraint'			=> 4,
		'null'					=> TRUE
	),
	'ip_address' => array(
		'type'					=> 'CHAR',
		'constraint'			=> 16,
		'null'					=> TRUE
	),
	'username' => array(
		'type'					=> 'VARCHAR',
		'constraint'			=> 64,
		'null'					=> TRUE
	),
	'password' => array(
		'type'					=> 'VARCHAR',
		'constraint'			=> 48,
		'null'					=> TRUE
	),
	'salt' => array(
		'type'					=> 'VARCHAR',
		'constraint'			=> 40,
		'null'					=> TRUE
	),
	'email' => array(
		'type'					=> 'VARCHAR',
		'constraint'			=> 48,
		'null'					=> TRUE
	),
	'gravatar' => array(
		'type'					=> 'VARCHAR',
		'constraint'			=> 48,
		'null'					=> TRUE
	),
	'phone_number' => array( 
		'type'					=> 'VARCHAR',
		'constraint'			=> 64,
		'null'					=> TRUE
	),
	'name' => array(
		'type'					=> 'VARCHAR',
		'constraint'			=> 128,
		'null'					=> TRUE
	),
	'image' => array(
		'type'					=> 'VARCHAR',
		'constraint'			=> 128,
		'null'					=> TRUE
	),
	'time_zone' => array(
		'type'					=> 'VARCHAR',
		'constraint'			=> 8,
		'null'					=> TRUE
	),
	'privacy' => array(
		'type'					=> 'CHAR',
		'constraint'			=> 8,
		'null'					=> TRUE
	),
	'language' => array(
		'type'					=> 'VARCHAR',
		'constraint'			=> 8,
		'null'					=> TRUE
	),
	'geo_enabled' => array(
		'type'					=> 'CHAR',
		'constraint'			=> 8,
		'null'					=> TRUE
	),
	'consumer_key' => array(
		'type'					=> 'VARCHAR',
		'constraint'			=> 48,
		'null'					=> TRUE
	),
	'consumer_secret' => array(
		'type'					=> 'VARCHAR',
		'constraint'			=> 48,
		'null'					=> TRUE
	),				
	'token' => array(
		'type'					=> 'VARCHAR',
		'constraint'			=> 48,
		'null'					=> TRUE
	),
	'token_secret' => array(
		'type'					=> 'VARCHAR',
		'constraint'			=> 48,
		'null'					=> TRUE
	),
	'activation_code' => array(
		'type'					=> 'VARCHAR',
		'constraint'			=> 40,
		'null'					=> TRUE
	),
	'forgotten_password_code' => array(
		'type'					=> 'VARCHAR',
		'constraint'			=> 40,
		'null'					=> TRUE
	),
	'active' => array(
		'type'					=> 'INT',
		'constraint'			=> 1,
		'unsigned' 				=> TRUE				
	),
	'remember_code' => array(
		'type'					=> 'VARCHAR',
		'constraint'			=> 40,
		'null'					=> TRUE
	),
	'created_on' => array(
		'type'					=> 'INT',
		'constraint'			=> 16,
		'null'					=> TRUE,
		'unsigned' 				=> TRUE
	),
	'last_login' => array(
		'type'					=> 'INT',
		'constraint'			=> 16,
		'null'						=> TRUE,
		'unsigned'				=> TRUE
	)
);

$config['database_users_level_table'] = array(
	'user_level_id' => array(
		'type' 					=> 'TINYINT',
		'constraint' 			=> 4,
		'unsigned' 				=> TRUE,
		'auto_increment'		=> TRUE
	),
	'level' => array(
		'type'					=> 'VARCHAR',
		'constraint'			=> 32
	),
	'level_name' => array(
		'type'					=> 'VARCHAR',
		'constraint'			=> 32
	),
	'description' => array(
		'type'					=> 'VARCHAR',
		'constraint'			=> 512
	)
);

$config['database_users_meta_table'] = array(
	'user_meta_id' => array(
		'type' 					=> 'INT',
		'constraint' 			=> 32,
		'unsigned' 				=> TRUE,
		'auto_increment'		=> TRUE
	),
	'user_id' => array(
		'type'					=> 'INT',
		'constraint'			=> 11
	),
	'site_id' => array(
		'type'					=> 'CHAR',
		'constraint'			=> 6
	),
	'module' => array(
		'type'					=> 'VARCHAR',
		'constraint'			=> 32,
		'null'					=> TRUE
	),
	'meta' => array(
		'type'					=> 'VARCHAR',
		'constraint'			=> 64,
		'null'					=> TRUE
	),
	'value' => array(
		'type'					=> 'TEXT',
		'null'					=> TRUE
	),
	'created_at' => array(
		'type'					=> 'DATETIME',
		'default'				=> '0000-00-00 00:00:00'
	),
	'updated_at' => array(
		'type'					=> 'DATETIME',
		'default'				=> '0000-00-00 00:00:00'
	)
); 

$config['database_users_sessions_table'] = array(
	'session_id' => array(
		'type' 					=> 'INT',
		'constraint' 			=> 40,
		'unsigned' 				=> TRUE,
	),
	'ip_address' => array(
		'type'					=> 'VARCHAR',
		'constraint'			=> 16,
		'null'					=> FALSE,
		'default'				=> 0
	),
	'user_agent' => array(
		'type'					=> 'VARCHAR',
		'constraint'			=> 120,
		'null'					=> TRUE
	),
	'last_activity' => array(
		'type'					=> 'INT',
		'constraint'			=> 10,
		'null'					=> FALSE,
		'default'				=> 0,
		'unsigned'				=> TRUE
	),
	'user_data' => array(
		'type'					=> 'TEXT',
		'null'					=> FALSE
	)
);

/* Settings */
$config['site_settings']['images_sizes_large'] = 'yes';
$config['site_settings']['images_sizes_medium'] = 'yes';
$config['site_settings']['images_sizes_small'] = 'yes';
$config['site_settings']['images_large_width'] = '425';
$config['site_settings']['images_large_height'] = '235';
$config['site_settings']['images_medium_width'] = '375';
$config['site_settings']['images_medium_height'] = '235';
$config['site_settings']['images_small_width'] = '125';
$config['site_settings']['images_small_height'] = '125';
$config['site_settings']['images_sizes_original'] = 'yes';
$config['site_settings']['languages_default'] = 'en';
$config['design_settings']['site_logo'] = '';
$config['design_settings']['font_size'] = '14px';
$config['design_settings']['font_family'] = "'Trebuchet MS'";
$config['design_settings']['font_color'] = '000000';
$config['design_settings']['header_image'] = '';
$config['design_settings']['header_position'] = 'left top';
$config['design_settings']['header_repeat'] = 'repeat-x';
$config['design_settings']['header_color'] = 'ffffff';
$config['design_settings']['background_image'] = '';
$config['design_settings']['background_position'] = 'left top';
$config['design_settings']['background_repeat'] = 'repeat-x';
$config['design_settings']['background_color'] = 'ffffff';
$config['design_settings']['link_color_normal'] = '0066CC';
$config['design_settings']['link_color_visited'] = '0066CC';
$config['design_settings']['link_color_hover'] = 'ff3300';
$config['design_settings']['header_link_color_normal'] = '0066CC';
$config['design_settings']['header_link_color_visited'] = '0066CC';
$config['design_settings']['header_link_color_hover'] = 'ff3300';
$config['themes_settings']['site_theme'] = 'site_default';
$config['themes_settings']['dashboard_theme']	= 'dashboard_default';
$config['themes_settings']['mobile_theme'] = 'mobile_default';
$config['services_settings']['email_protocol'] = 'mail';
$config['services_settings']['smtp_host'] = '';
$config['services_settings']['smtp_user'] = '';
$config['services_settings']['smtp_pass'] = '';
$config['services_settings']['smtp_port'] = '';
$config['services_settings']['mobile_enabled'] = 'FALSE';
$config['services_settings']['mobile_module'] = '--select--';
$config['services_settings']['google_webmaster'] = '';
$config['services_settings']['google_analytics'] = '';
$config['services_settings']['bing_webmaster'] = '';
$config['services_settings']['gravatar_enabled'] = 'TRUE';
$config['services_settings']['bitly_enabled'] = 'TRUE';
$config['services_settings']['bitly_login'] = '';
$config['services_settings']['bitly_api_key'] = '';
$config['services_settings']['bitly_domain'] = 'bit.ly';
$config['services_settings']['akismet_key'] = '';
$config['services_settings']['recaptcha_public'] = '';
$config['services_settings']['recaptcha_private'] = '';
$config['services_settings']['recaptcha_theme']	= 'white';
$config['home_settings']['view_permission'] = '4';
$config['home_settings']['create_permission'] = '4';
$config['home_settings']['view_redirect'] = '';
$config['home_settings']['public_timeline'] = 'TRUE';
$config['home_settings']['status_length'] = '140';
$config['home_settings']['date_style'] = 'ELAPSED';
$config['home_settings']['description_length'] = '110';
$config['home_settings']['share']	= 'TRUE';
$config['home_settings']['like'] = 'TRUE';
$config['home_settings']['comments_allow'] = 'TRUE';
$config['home_settings']['comments_per_page'] = '2';
$config['users_settings']['images_sizes_large'] = 'yes';
$config['users_settings']['images_sizes_medium'] = 'yes';
$config['users_settings']['images_sizes_small'] = 'yes';
$config['users_settings']['images_large_width'] = '275';
$config['users_settings']['images_large_height'] = '175';
$config['users_settings']['images_medium_width'] = '48';
$config['users_settings']['images_medium_height'] = '48';
$config['users_settings']['images_small_width'] = '45';
$config['users_settings']['images_small_height'] = '25';
$config['users_settings']['images_formats'] = 'gif|jpg|jpeg|png';
$config['users_settings']['images_max_size'] = '5120';
$config['users_settings']['images_full_width'] = '750';
$config['users_settings']['images_full_height'] = '750';
$config['users_settings']['images_sizes_full'] = 'yes';
$config['users_settings']['images_folder'] = 'uploads/profiles/';
$config['users_settings']['images_max_dimensions'] = '3000';
$config['users_settings']['images_sizes_original'] = 'yes';
$config['users_settings']['settings_level'] = '4';
$config['users_settings']['settings_redirect'] = 'settings/profile';
$config['users_settings']['signup'] = 'TRUE';
$config['users_settings']['signup_recaptcha'] = 'TRUE';
$config['users_settings']['login'] = 'TRUE';
$config['users_settings']['login_recaptcha'] = 'TRUE';

/* End install.php */