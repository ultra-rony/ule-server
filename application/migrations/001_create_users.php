<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Create_users extends CI_Migration
{

	public function up()
	{
		$fields = [
			'id' => [
				'type' => 'BIGINT',
				'unsigned' => TRUE,
				'auto_increment' => TRUE,
			],
			'device' => [
				'type' => 'JSON',
				'null' => TRUE,
			],
			'email' => [
				'type' => 'VARCHAR',
				'constraint' => 255,
				'null' => TRUE,
			],
			'phone' => [
				'type' => 'VARCHAR',
				'constraint' => 100,
				'null' => TRUE,
			],
			'username' => [
				'type' => 'VARCHAR',
				'constraint' => 100,
				'null' => TRUE,
			],
			'image_url' => [
				'type' => 'TEXT',
				'null' => TRUE,
			],
			'city' => [
				'type' => 'VARCHAR',
				'constraint' => 255,
				'null' => TRUE
			],
			'latitude' => [
				'type' => 'DECIMAL',
				'constraint' => '11,8',
				'null' => TRUE
			],
			'longitude' => [
				'type' => 'DECIMAL',
				'constraint' => '11,8',
				'null' => TRUE
			],
			'fcm_token' => [
				'type' => 'VARCHAR',
				'constraint' => 512,
				'null' => TRUE,
			],
			'passport_series' => [
				'type' => 'VARCHAR',
				'constraint' => 20,
				'null' => TRUE
			],
			'passport_number' => [
				'type' => 'VARCHAR',
				'constraint' => 20,
				'null' => TRUE
			],
			'passport_issue_date' => [
				'type' => 'DATE',
				'null' => TRUE
			],
			'passport_expiry_date' => [
				'type' => 'DATE',
				'null' => TRUE
			],
			'passport_issued_by' => [
				'type' => 'VARCHAR',
				'constraint' => 255,
				'null' => TRUE
			],
			'passport_department_code' => [
				'type' => 'VARCHAR',
				'constraint' => 20,
				'null' => TRUE
			],
			'passport_image_url' => [
				'type' => '	TEXT',
				'null' => TRUE
			],
			'token' => [
				'type' => 'VARCHAR',
				'constraint' => 128,
				'null' => TRUE,
			],
			'created_at' => [
				'type' => 'DATETIME',
				'null' => TRUE,
			],
			'updated_at' => [
				'type' => 'DATETIME',
				'null' => TRUE,
			],
			'created_ip' => [
				'type' => 'VARCHAR',
				'constraint' => 128,
				'null' => TRUE,
			],
		];

		$this->dbforge->add_field($fields);

		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->add_key('token');
		$this->dbforge->add_key('phone', FALSE, TRUE);

		$this->dbforge->create_table('users', TRUE, ['ENGINE' => 'InnoDB']);
	}

	public function down()
	{
		$this->dbforge->drop_table('users', TRUE);
	}
}