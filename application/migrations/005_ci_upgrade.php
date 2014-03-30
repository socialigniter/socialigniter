<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Ci_upgrade extends CI_Migration
{
	public function up()
	{
    $fields = array('ip_address' => array(
      'name' => 'ip_address',
      'type' => 'VARCHAR',
      'constraint' => 45
    ));
    
    $this->dbforge->modify_column('users_sessions', $fields);
	}

	public function down()
	{
    $fields = array('ip_address' => array(
      'name' => 'ip_address',
      'type' => 'VARCHAR',
      'constraint' => 16
    ));
    
    $this->dbforge->modify_column('users_sessions', $fields);
	}
}