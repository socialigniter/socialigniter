<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Users extends CI_Migration
{
	public function up()
	{
		$this->load->config('install');
		$this->load->library('installer');
	
		// Add Columns
		$users_fields = array(
			'url' => array(
				'type' 			=> 'VARCHAR',
				'constraint' 	=> 128,
				'null'			=> TRUE
			),
			'bio' => array(
				'type' 			=> 'VARCHAR',
				'constraint' 	=> 512,
				'null'			=> TRUE
			),
			'location' => array(
				'type' 			=> 'VARCHAR',
				'constraint' 	=> 255,
				'null'			=> TRUE
			),
			'company' => array(
				'type' 			=> 'VARCHAR',
				'constraint' 	=> 255,
				'null'			=> TRUE
			)	
		);

		$this->dbforge->add_column('users', $users_fields);
		
	}

	public function down()
	{
		$this->dbforge->drop_column('users', 'url');
		$this->dbforge->drop_column('users', 'bio');
		$this->dbforge->drop_column('users', 'location');
		$this->dbforge->drop_column('users', 'company');
	}
}