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
		'type'					=> 'VARCHAR',
		'constraint'			=> 255,
		'null'					=> TRUE
	),
	'details' => array(
		'type'					=> 'VARCHAR',
		'constraint'			=> 255,
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

$config['database_comments_table'] = array(
	'comment_id' => array(
		'type' 					=> 'INT',
		'constraint' 			=> 32,
		'unsigned' 				=> TRUE,
		'auto_increment'		=> TRUE
	),
	'site_id' => array(
		'type'					=> 'INT',
		'constraint'			=> 6,
		'null'					=> TRUE
	),
	'reply_to_id' => array(
		'type'					=> 'INT',
		'constraint'			=> 11,
		'null'					=> TRUE
	),
	'content_id' => array(
		'type'					=> 'INT',
		'constraint'			=> 11,
		'null'					=> TRUE
	),
	'owner_id' => array(
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
	'user_id' => array(
		'type'					=> 'INT',
		'constraint'			=> 11,
		'null'					=> TRUE
	),
	'comment' => array(
		'type'					=> 'TEXT',
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

$config['database_places_table'] = array(
	'place_id' => array(
		'type' 				=> 'INT',
		'constraint' 		=> 32,
		'unsigned' 			=> TRUE,
		'auto_increment'	=> TRUE
	),
	'content_id' => array(
		'type'				=> 'INT',
		'constraint'		=> 11,
		'null'				=> TRUE
	),
	'address' => array(
		'type'				=> 'VARCHAR',
		'constraint'		=> 128,
		'null'				=> TRUE
	),
	'district' => array(
		'type'				=> 'VARCHAR',
		'constraint'		=> 128,
		'null'				=> TRUE
	),
	'locality' => array(
		'type'				=> 'VARCHAR',
		'constraint'		=> 128,
		'null'				=> TRUE
	),
	'region' => array(
		'type'				=> 'VARCHAR',
		'constraint'		=> 128,
		'null'				=> TRUE
	),
	'country' => array(
		'type'				=> 'VARCHAR',
		'constraint'		=> 64,
		'null'				=> TRUE
	),	
	'postal' => array(
		'type'				=> 'VARCHAR',
		'constraint'		=> 63,
		'null'				=> TRUE
	)														
);

$config['database_oauth_server_nonce_table'] = array(
	'osn_id' => array(
		'type' 					=> 'INT',
		'constraint' 			=> 11,
		'auto_increment'		=> TRUE
	),
	'osn_consumer_key' => array(
		'type'					=> 'VARCHAR',
		'constraint'			=> 64,
		'null'					=> FALSE,
		'unique'				=> TRUE
	),
	'osn_token' => array(
		'type'					=> 'VARCHAR',
		'constraint'			=> 64,
		'null'					=> FALSE,
		'unique'				=> TRUE				
	),
	'osn_timestamp' => array(
		'type'					=> 'BIGINT',
		'constraint'			=> 20,
		'null'					=> FALSE,
		'unique'				=> TRUE				
	),
	'osn_nonce' => array(
		'type'					=> 'VARCHAR',
		'constraint'			=> 80,
		'null'					=> FALSE,
		'unique'				=> TRUE				
	)			
);

$config['database_oauth_server_registry_table'] = array(
	'osr_id' => array(
		'type' 					=> 'INT',
		'constraint' 			=> 11,
		'unsigned' 				=> TRUE,
		'auto_increment'		=> TRUE
	),
	'osr_usa_id_ref' => array(
		'type'					=> 'INT',
		'constraint'			=> 11,
		'null'					=> TRUE
	),
	'osr_consumer_key' => array(
		'type'					=> 'VARCHAR',
		'constraint'			=> 64,
		'null'					=> FALSE
	),
	'osr_consumer_secret' => array(
		'type'					=> 'VARCHAR',
		'constraint'			=> 64,
		'null'					=> FALSE
	),
	'osr_enabled' => array(
		'type'					=> 'TINYINT',
		'constraint'			=> 1,
		'null'					=> FALSE
	),
	'osr_status' => array(
		'type'					=> 'VARCHAR',
		'constraint'			=> 16,
		'null'					=> FALSE
	),	
	'osr_requester_name' => array(
		'type'					=> 'VARCHAR',
		'constraint'			=> 64,
		'null'					=> FALSE
	),				
	'osr_requester_email' => array(
		'type'					=> 'VARCHAR',
		'constraint'			=> 64,
		'null'					=> FALSE
	),
	'osr_callback_uri' => array(
		'type'					=> 'VARCHAR',
		'constraint'			=> 255,
		'null'					=> FALSE
	),
	'osr_application_uri' => array(
		'type'					=> 'VARCHAR',
		'constraint'			=> 255,
		'null'					=> FALSE
	),
	'osr_application_title' => array(
		'type'					=> 'VARCHAR',
		'constraint'			=> 255,
		'null'					=> FALSE
	),
	'osr_application_descr' => array(
		'type'					=> 'TEXT',
		'null'					=> FALSE
	),
	'osr_application_notes' => array(
		'type'					=> 'TEXT',
		'null'					=> FALSE
	),									
	'osr_application_type' => array(
		'type'					=> 'VARCHAR',
		'constraint'			=> 20,
		'null'					=> FALSE
	),
	'osr_application_commerical' => array(
		'type'					=> 'TINYINT',
		'constraint'			=> 1,
		'null'					=> FALSE,
		'default'				=> 0
	),
	'osr_issue_date' => array(
		'type'					=> 'DATETIME',
		'null'					=> FALSE,
	),
	'osr_timestamp' => array(
		'type'					=> "TIMESTAMP DEFAULT CURRENT_TIMESTAMP"
	)
);

$config['database_oauth_server_token_table'] = array(
	'ost_id' => array(
		'type' 					=> 'INT',
		'constraint' 			=> 32,
		'unsigned' 				=> TRUE,
		'auto_increment'		=> TRUE
	),
	'ost_osr_id_ref' => array(
		'type'					=> 'INT',
		'constraint'			=> 11,
		'null'					=> FALSE
	),
	'ost_usa_id_ref' => array(
		'type'					=> 'INT',
		'constraint'			=> 11,
		'null'					=> FALSE
	),
	'ost_token' => array(
		'type'					=> 'VARCHAR',
		'constraint'			=> 64,
		'null'					=> TRUE
	),
	'ost_token_secret' => array(
		'type'					=> 'VARCHAR',
		'constraint'			=> 64,
		'null'					=> TRUE
	),
	'ost_token_type' => array(
		'type'					=> "enum('request','access')",
		'null'					=> TRUE
	),
	'ost_authorized' => array(
		'type'					=> 'TINYINT',
		'constraint'			=> 1,
		'null'					=> FALSE
	),
	'ost_referrer_host' => array(
		'type'					=> 'VARCHAR',
		'constraint'			=> 128,
		'null'					=> TRUE
	),
	'ost_token_ttl' => array(
		'type'					=> 'DATETIME',
		'null'					=> FALSE,
		'default'				=> '9999-12-31 00:00:00'
	),			
	'ost_timestamp' => array(
		'type'					=> "TIMESTAMP DEFAULT CURRENT_TIMESTAMP"
	),
	'ost_verifier' => array(
		'type'					=> 'CHAR',
		'constraint'			=> 10,
		'null'					=> TRUE
	),				
	'ost_callback_url' => array(
		'type'					=> 'VARCHAR',
		'constraint'			=> 512,
		'null'					=> TRUE
	)
);

$config['database_ratings_table'] = array(
	'rating_id' => array(
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
	'user_id' => array(
		'type'					=> 'INT',
		'constraint'			=> 11,
		'null'					=> TRUE
	),
	'content_id' => array(
		'type'					=> 'INT',
		'constraint'			=> 11,
		'null'					=> TRUE
	),
	'type' => array(
		'type'					=> 'VARCHAR',
		'constraint'			=> 32,
		'null'					=> TRUE
	),
	'rating' => array(
		'type'					=> 'CHAR',
		'constraint'			=> 6,
		'null'					=> TRUE
	),
	'created_at' => array(
		'type'					=> 'DATETIME',
		'null'					=> TRUE
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

$config['database_tags_table'] = array(
	'tag_id' => array(
		'type' 					=> 'INT',
		'constraint' 			=> 32,
		'unsigned' 				=> TRUE,
		'auto_increment'		=> TRUE
	),
	'tag' => array(
		'type'					=> 'VARCHAR',
		'constraint'			=> 128,
		'null'					=> TRUE
	),
	'tag_url' => array(
		'type'					=> 'VARCHAR',
		'constraint'			=> 128,
		'null'					=> TRUE
	),
	'created_at' => array(
		'type'					=> 'DATETIME',
		'null'					=> TRUE
	)
);

$config['database_tags_link_table'] = array(
	'tag_link_id' => array(
		'type' 					=> 'INT',
		'constraint' 			=> 32,
		'unsigned' 				=> TRUE,
		'auto_increment'		=> TRUE
	),
	'tag_id' => array(
		'type'					=> 'INT',
		'constraint'			=> 32,
		'null'					=> TRUE
	),
	'content_id' => array(
		'type'					=> 'INT',
		'constraint'			=> 32,
		'null'					=> TRUE
	),
	'created_at' => array(
		'type'					=> 'DATETIME',
		'null'					=> FALSE
	)					
);

$config['database_taxnomy_table'] = array(
	'taxonomy_id' => array(
		'type' 					=> 'INT',
		'constraint' 			=> 32,
		'unsigned' 				=> TRUE,
		'auto_increment'		=> TRUE
	),
	'object_id' => array(
		'type'					=> 'INT',
		'constraint'			=> 32,
		'null'					=> TRUE
	),
	'taxonomy' => array(
		'type'					=> 'VARCHAR',
		'constraint'			=> 32,
		'null'					=> TRUE
	),
	'count' => array(
		'type'					=> 'INT',
		'constraint'			=> 16,
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
		'type'					=> 'INT',
		'constraint'			=> 1,
		'null'					=> TRUE
	),
	'language' => array(
		'type'					=> 'VARCHAR',
		'constraint'			=> 8,
		'null'					=> TRUE
	),
	'geo_enabled' => array(
		'type'					=> 'INT',
		'constraint'			=> 1,
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

/* Data */
$config['settings'] = array('site', 'title', 'Awesome Website');
$config['settings'] = array('site', 'tagline', 'Where I Post All My Awesome Things');
$config['settings'] = array('site', 'keywords', 'awesome, things, pictures, videos, poems, watermelons, cats, ninjas');
$config['settings'] = array('site', 'description', 'This is my awesome website where I post awesome stuff. Some of my favorite things are ninjas, watermelons, and cats');
$config['settings'] = array('site', 'url', 'http://domainname.com');
$config['settings'] = array('site', 'admin_email', 'you@email.com');
$config['settings'] = array('site', 'languages_default', 'en');
$config['settings'] = array('site', 'images_sizes_large', 'yes');
$config['settings'] = array('site', 'images_sizes_medium', 'yes');
$config['settings'] = array('site', 'images_sizes_small', 'yes');
$config['settings'] = array('site', 'images_large_width', '425');
$config['settings'] = array('site', 'images_large_height', '235');
$config['settings'] = array('site', 'images_medium_width', '375');
$config['settings'] = array('site', 'images_medium_height', '235');
$config['settings'] = array('site', 'images_small_width', '125');
$config['settings'] = array('site', 'images_small_height', '125');
$config['settings'] = array('site', 'images_sizes_original', 'yes');
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
$config['settings'] = array('comments', 'email_signup', 'TRUE');
$config['settings'] = array('comments', 'email_replies', 'TRUE');
$config['settings'] = array('comments', 'akismet', 'TRUE');
$config['settings'] = array('comments', 'recaptcha', 'TRUE');
$config['settings'] = array('comments', 'date_style', 'ELAPSED');
$config['settings'] = array('places', 'enabled', 'TRUE');
$config['settings'] = array('places', 'crud_permission', '2');
$config['settings'] = array('places', 'ratings_allow', 'no');
$config['settings'] = array('places', 'comments_per_page', '5');
$config['settings'] = array('places', 'comments_allow', 'no');
$config['settings'] = array('places', 'tags_display', 'no');
$config['settings'] = array('ratings', 'enabled', 'TRUE');
$config['settings'] = array('ratings', 'rate_type', 'TRUE');
$config['settings'] = array('home', 'public_timeline', 'TRUE');
$config['settings'] = array('home', 'date_style', 'ELAPSED');
$config['settings'] = array('home', 'status_length', '140');
$config['settings'] = array('home', 'description_length', '110');
$config['settings'] = array('home', 'share', 'TRUE');
$config['settings'] = array('home', 'like', 'TRUE');
$config['settings'] = array('home', 'comments_allow', 'TRUE');
$config['settings'] = array('home', 'comments_per_page', '2');
$config['settings'] = array('users', 'crud_permission', '2');
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

$config['sites'] 		= array('http://social-igniter.com', 'default', 'Social-Igniter', 'A Really Simple Open Source Social Web Application Template', 'Social-Igniter is a really simple open source social web application template', 'social, web application, open source, codeigniter, php');

$config['users_level']	= array('superadmin', 'Super Admin', 'Super Admins are the head honchos who have power to do anything they want on your install of Social Igniter');
$config['users_level']	= array('admin', 'Admin', 'Admins can do most things, not all, but most things needed on a site');
$config['users_level'] 	= array('superuser', 'Super User', 'Supers Users help keep the ship on course, they do some things, but not all');
$config['users_level'] 	= array('user', 'User', 'Users are just regular Joes or Joesephines. They use your application as it is intended for the general public');
