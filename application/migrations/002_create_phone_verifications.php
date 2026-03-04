<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Create_phone_verifications extends CI_Migration
{

	public function up()
	{
		$fields = [
			'id' => [
				'type' => 'BIGINT',
				'unsigned' => TRUE,
				'auto_increment' => TRUE,
			],
			'phone' => [
				'type' => 'VARCHAR',
				'constraint' => 100,
				'null' => TRUE,
			],
            'is_verification' => array(
				'type' => 'TINYINT',
				'constraint' => '1',
                'default' => 0,
				'null' => TRUE,
			),
            'callgate_create_response' => [
				'type' => 'JSON',
				'null' => TRUE,
			], 
            'callgate_check_response' => [
				'type' => 'JSON',
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
		$this->dbforge->create_table('phone_verifications', TRUE, ['ENGINE' => 'InnoDB']);
	}

	public function down()
	{
		$this->dbforge->drop_table('phone_verifications', TRUE);
	}
}