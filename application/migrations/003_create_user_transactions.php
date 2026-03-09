<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Create_user_transactions extends CI_Migration
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
            'type' => [
                'type' => 'ENUM("deposit","withdrawal")',
                'null' => TRUE,
            ],
            'amount' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'default' => 0,
                'null' => FALSE,
            ],
            'balance_before' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'default' => 0,
                'null' => FALSE,
            ],
            'balance_after' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'default' => 0,
                'null' => FALSE,
            ],
            'meta' => [
                'type' => 'JSON',
                'null' => TRUE,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => TRUE,
            ],
        ];

        $this->dbforge->add_field($fields);

        $this->dbforge->add_field('CONSTRAINT fk_user_transactions_user_id FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE');

        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_key('user_id');

        $this->dbforge->create_table('user_transactions', TRUE, ['ENGINE' => 'InnoDB']);
    }

    public function down()
    {
        $this->dbforge->drop_table('user_transactions', TRUE);
    }
}