<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Various extends CI_Migration
{
	public function up()
	{
		$this->load->config('install');
		$this->load->library('installer');
	
		// Add New Design Settings
		$this->installer->install_settings('design', config_item('design_settings'), TRUE);

	}

	public function down()
	{
		$this->dbforge->drop_column('categories', 'status');
	}
}