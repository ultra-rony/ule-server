<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Create_users extends CI_Migration
{

	public function up()
	{
		$fields = array(
			'id' => array(
				'type' => 'BIGINT',
				'unsigned' => TRUE,
				'auto_increment' => TRUE,
			),
			'device' => array(
				'type' => 'VARCHAR',
				'constraint' => '255',
				'null' => TRUE,
			),
			'email' => array(
				'type' => 'VARCHAR',
				'constraint' => '255',
				'null' => TRUE,
				'unique' => TRUE,
			),
			'phone' => array(
				'type' => 'VARCHAR',
				'constraint' => '100',
				'null' => TRUE,
			),
			'firebase_token' => array(
				'type' => 'VARCHAR',
				'constraint' => '512',
				'null' => TRUE,
			),
			'lang' => array(
				'type' => 'VARCHAR',
				'constraint' => '32',
				'null' => TRUE,
			),
			'token' => array(
				'type' => 'VARCHAR',
				'constraint' => '128',
				'null' => TRUE,
			),
			'created_at' => array(
				'type' => 'DATETIME',
				'null' => TRUE,
			),
			'updated_at' => array(
				'type' => 'DATETIME',
				'null' => TRUE,
			),
			'created_ip' => array(
				'type' => 'VARCHAR',
				'constraint' => '128',
				'null' => TRUE,
			),
		);
		$this->dbforge->add_field($fields);

		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->add_key('token');

		$this->dbforge->create_table('users', TRUE, ['ENGINE' => 'InnoDB']);

		$this->db->query('ALTER TABLE users ADD UNIQUE (`email`)');
	}

	public function down()
	{
		$this->dbforge->drop_table('users', TRUE);
	}
}
