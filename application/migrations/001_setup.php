<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Setup extends CI_Migration
{
	public function up()
	{
		// Activity
		$this->dbforge->add_key('activity_id', TRUE);
		$this->dbforge->add_field(config_item('database_activity_table'));
		$this->dbforge->create_table('activity');

		// Categories
		$this->dbforge->add_key('category_id', TRUE);
		$this->dbforge->add_field(config_item('database_categories_table'));
		$this->dbforge->create_table('categories');
		
		// Connections
		$this->dbforge->add_key('connection_id', TRUE);
		$this->dbforge->add_field(config_item('database_connections_table'));
		$this->dbforge->create_table('connections');
		
		// Content
		$this->dbforge->add_key('content_id', TRUE);
		$this->dbforge->add_field(config_item('database_content_table'));
		$this->dbforge->create_table('content');

		// Content Meta
		$this->dbforge->add_key('content_meta_id', TRUE);
		$this->dbforge->add_field(config_item('database_content_meta_table'));
		$this->dbforge->create_table('content_meta');

		// OAuth Tables
	    $this->db->query("CREATE TABLE `oauth_server_nonce` (`osn_id` int(11) NOT NULL AUTO_INCREMENT, `osn_consumer_key` varchar(64) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL, `osn_token` varchar(64) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL, `osn_timestamp` bigint(20) NOT NULL, `osn_nonce` varchar(80) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL, PRIMARY KEY (`osn_id`), UNIQUE KEY `osn_consumer_key` (`osn_consumer_key`,`osn_token`,`osn_timestamp`,`osn_nonce`)) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;");
	    $this->db->query("CREATE TABLE `oauth_server_registry` (`osr_id` int(11) NOT NULL AUTO_INCREMENT, `osr_usa_id_ref` int(11) DEFAULT NULL, `osr_consumer_key` varchar(64) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL, `osr_consumer_secret` varchar(64) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL, `osr_enabled` tinyint(1) NOT NULL DEFAULT '1', `osr_status` varchar(16) NOT NULL, `osr_requester_name` varchar(64) NOT NULL, `osr_requester_email` varchar(64) NOT NULL, `osr_callback_uri` varchar(255) NOT NULL, `osr_application_uri` varchar(255) NOT NULL, `osr_application_title` varchar(80) NOT NULL, `osr_application_descr` text NOT NULL, `osr_application_notes` text NOT NULL, `osr_application_type` varchar(20) NOT NULL, `osr_application_commercial` tinyint(1) NOT NULL DEFAULT '0', `osr_issue_date` datetime NOT NULL, `osr_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP, PRIMARY KEY (`osr_id`), UNIQUE KEY `osr_consumer_key` (`osr_consumer_key`), KEY `osr_usa_id_ref` (`osr_usa_id_ref`)) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;");
	    $this->db->query("CREATE TABLE `oauth_server_token` (`ost_id` int(11) NOT NULL AUTO_INCREMENT, `ost_osr_id_ref` int(11) NOT NULL, `ost_usa_id_ref` int(11) NOT NULL, `ost_token` varchar(64) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL, `ost_token_secret` varchar(64) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL, `ost_token_type` enum('request','access') DEFAULT NULL, `ost_authorized` tinyint(1) NOT NULL DEFAULT '0', `ost_referrer_host` varchar(128) NOT NULL DEFAULT '', `ost_token_ttl` datetime NOT NULL DEFAULT '9999-12-31 00:00:00', `ost_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP, `ost_verifier` char(10) DEFAULT NULL, `ost_callback_url` varchar(512) DEFAULT NULL, PRIMARY KEY (`ost_id`), UNIQUE KEY `ost_token` (`ost_token`), KEY `ost_osr_id_ref` (`ost_osr_id_ref`), KEY `ost_token_ttl` (`ost_token_ttl`)) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;");
	    $this->db->query("ALTER TABLE `oauth_server_token` ADD CONSTRAINT `oauth_server_token_ibfk_1` FOREIGN KEY (`ost_osr_id_ref`) REFERENCES `oauth_server_registry` (`osr_id`) ON DELETE CASCADE ON UPDATE CASCADE;");
	
		// Places
		$this->dbforge->add_key('place_id', TRUE);
		$this->dbforge->add_field(config_item('database_places_table'));
		$this->dbforge->create_table('places'); 
  		
		// Ratings
		$this->dbforge->add_key('rating_id', TRUE);
		$this->dbforge->add_field(config_item('database_ratings_table'));
		$this->dbforge->create_table('ratings');
				
		// Relationships
		$this->dbforge->add_key('relationship_id', TRUE);
		$this->dbforge->add_field(config_item('database_relationships_table'));
		$this->dbforge->create_table('relationships');
		
		// Settings
		$this->dbforge->add_key('settings_id', TRUE);
		$this->dbforge->add_field(config_item('database_settings_table'));
		$this->dbforge->create_table('settings');
		
		// Sites
		$this->dbforge->add_key('site_id', TRUE);
		$this->dbforge->add_field(config_item('database_sites_table'));
		$this->dbforge->create_table('sites');
		
		// Tags
		$this->dbforge->add_key('tag_id', TRUE);
		$this->dbforge->add_field(config_item('database_tags_table'));
		$this->dbforge->create_table('tags');

		// Tags Link
		$this->dbforge->add_key('tag_link_id', TRUE);
		$this->dbforge->add_field(config_item('database_tags_link_table'));
		$this->dbforge->create_table('tags_link');		
			
		// Taxonomy
		$this->dbforge->add_key('taxonomy_id', TRUE);
		$this->dbforge->add_field(config_item('database_taxnomy_table'));
		$this->dbforge->create_table('taxonomy');		
		
		// Uploads
		$this->dbforge->add_key('upload_id', TRUE);
		$this->dbforge->add_field(config_item('database_uploads_table'));
		$this->dbforge->create_table('uploads');

		// Users
		$this->dbforge->add_key('user_id', TRUE);
		$this->dbforge->add_field(config_item('database_users_table'));
		$this->dbforge->create_table('users');
		
		// Users Level
		$this->dbforge->add_key('user_level_id', TRUE);
		$this->dbforge->add_field(config_item('database_users_level_table'));
		$this->dbforge->create_table('users_level');		

		// Users Meta
		$this->dbforge->add_key('user_meta_id', TRUE);
		$this->dbforge->add_field(config_item('database_users_meta_table'));
		$this->dbforge->create_table('users_meta');

		// Users Sessions
		$this->dbforge->add_key('session_id', TRUE);
		$this->dbforge->add_key('last_activity');
		$this->dbforge->add_field(config_item('database_users_sessions_table'));
		$this->dbforge->create_table('users_sessions');
	}

	public function down()
	{
		$this->dbforge->drop_table('activity');
		$this->dbforge->drop_table('categories');
		$this->dbforge->drop_table('comments');
		$this->dbforge->drop_table('connections');
		$this->dbforge->drop_table('content');
		$this->dbforge->drop_table('content_meta');
		$this->dbforge->drop_table('places');
		$this->dbforge->drop_table('oauth_server_nonce');
		$this->dbforge->drop_table('oauth_server_registry');
		$this->dbforge->drop_table('oauth_server_token');
		$this->dbforge->drop_table('ratings');
		$this->dbforge->drop_table('relationships');
		$this->dbforge->drop_table('settings');
		$this->dbforge->drop_table('sites');
		$this->dbforge->drop_table('tags');
		$this->dbforge->drop_table('tags_link');
		$this->dbforge->drop_table('taxonomy');
		$this->dbforge->drop_table('uploads');
		$this->dbforge->drop_table('users');
		$this->dbforge->drop_table('users_level');
		$this->dbforge->drop_table('users_meta');
		$this->dbforge->drop_table('users_sessions');
	}
}