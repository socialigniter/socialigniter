<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Categories extends CI_Migration
{
	public function up()
	{
		// Add Column
		$category_fields = array(
			'status' => array(
				'type' 			=> 'CHAR',
				'constraint' 	=> 1,
				'null'			=> TRUE
			)
		);

		$this->dbforge->add_column('categories', $category_fields);
		
		$this->load->library('social_tools');
		
		// Update Existing Categories with P status
		$categories	= $this->social_tools->get_categories();

		foreach ($categories as $category)
		{
			$this->social_tools->update_category($category->category_id, array('status' => 'P'));			
		}
	}

	public function down()
	{
		$this->dbforge->drop_column('categories', 'status');
	}
}