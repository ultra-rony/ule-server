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
			'balance' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'default' => 0,
                'null' => FALSE
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
            'last_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => TRUE,
            ],
            'first_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => TRUE,
            ],
            'middle_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => TRUE,
            ],
            'series' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => TRUE,
            ],
            'number' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => TRUE,
            ],
            'issued_by' => [
                'type' => 'TEXT',
                'null' => TRUE,
            ],
            'issue_date' => [
                'type' => 'DATE',
                'null' => TRUE,
            ],
            'birth_date' => [
                'type' => 'DATE',
                'null' => TRUE,
            ],
            'birth_place' => [
                'type' => 'TEXT',
                'null' => TRUE,
            ],
            'passport_image' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => TRUE,
            ],
			'token' => [
				'type' => 'VARCHAR',
				'constraint' => 128,
				'null' => TRUE,
			],
			'first_deposit_at' => [
				'type' => 'DATETIME',
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