<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Create_orders extends CI_Migration
{

    public function up()
    {
        $fields = [
            'id' => [
                'type' => 'BIGINT',
                'unsigned' => TRUE,
                'auto_increment' => TRUE,
            ],
            'user_id' => [
                'type' => 'BIGINT',
                'unsigned' => TRUE,
                'null' => FALSE,
            ],
            'category_id' => [
                'type' => 'BIGINT',
                'unsigned' => TRUE,
                'null' => FALSE,
            ],
            'phone' => [
				'type' => 'VARCHAR',
				'constraint' => 100,
				'null' => TRUE,
			],
            'title' => [
                'type' => 'VARCHAR',
                'constraint' => 512,
                'null' => FALSE,
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => TRUE,
            ],
            'image_urls' => [
                'type' => 'TEXT',
                'null' => TRUE,
            ],
            'amount' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'default' => 0,
                'null' => FALSE,
            ],
            'city' => [
				'type' => 'VARCHAR',
				'constraint' => 255,
				'null' => TRUE
			],
            'address' => [
                'type' => 'VARCHAR',
                'constraint' => 512,
                'null' => TRUE,
            ],
            'latitude' => [
                'type' => 'DECIMAL',
                'constraint' => '11,8',
                'null' => TRUE,
            ],
            'longitude' => [
                'type' => 'DECIMAL',
                'constraint' => '11,8',
                'null' => TRUE,
            ],
            'required_workers' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'default' => 1,
                'null' => FALSE,
            ],
            'status' => [
				'type' => 'TINYINT',
				'constraint' => '1',
                'default' => 1,
            ],
            'work_start_at' => [
                'type' => 'DATETIME',
                'null' => TRUE,
            ],
            'work_end_at' => [
                'type' => 'DATETIME',
                'null' => TRUE,
            ],
            'created_at' => [
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
        $this->dbforge->add_key('user_id');
        $this->dbforge->add_key('category_id');

        $this->dbforge->create_table('orders', TRUE, ['ENGINE' => 'InnoDB']);

        $this->db->query("
            ALTER TABLE `orders`
            ADD CONSTRAINT `fk_orders_user`
            FOREIGN KEY (`user_id`)
            REFERENCES `users`(`id`)
            ON DELETE CASCADE
            ON UPDATE CASCADE
        ");

        $this->db->query("
            ALTER TABLE `orders`
            ADD CONSTRAINT `fk_orders_category`
            FOREIGN KEY (`category_id`)
            REFERENCES `categories`(`id`)
            ON DELETE RESTRICT
            ON UPDATE CASCADE
        ");
    }

    public function down()
    {
        $this->dbforge->drop_table('orders', TRUE);
    }
    
}