<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_create_user_permissions_table extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
            'id' =>
                [
                    'type'           => 'INT',
                    'constraint'     => 8,
                    'unsigned'       => TRUE,
                    'auto_increment' => TRUE
                ],
            'user_id' =>
                [
                    'type'       => 'INT',
                    'constraint' => 8,
                    'unsigned'   => TRUE,
                ],
            'category_id' =>
                [
                    'type'       => 'INT',
                    'constraint' => 8,
                    'unsigned'   => TRUE,
                ],

            'created_at DATETIME default CURRENT_TIMESTAMP',
            
        ]);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (user_id) REFERENCES users(id)');
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (category_id) REFERENCES user_permissions_categories(id)');
        $this->dbforge->create_table('user_permissions');
    }

    public function down()
    {
        $this->dbforge->drop_table('user_permissions',TRUE);
    }
}