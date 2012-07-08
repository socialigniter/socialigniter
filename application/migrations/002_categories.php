<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Categories extends CI_Migration
{
	public function up()
	{
		$category_fields = array(
			'status' => array(
				'type' 					=> 'CHAR',
				'constraint' 			=> 1,
				'null'					=> TRUE
			)
		);

		$this->dbforge->add_column('categories', $category_fields);
	}

	public function down()
	{
		$this->dbforge->drop_column('categories', 'status');
	}
}