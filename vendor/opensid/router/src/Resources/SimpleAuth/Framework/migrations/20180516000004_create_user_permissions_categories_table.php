<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_create_user_permissions_categories_table extends CI_Migration
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
            'name' =>
                [
                    'type'       => 'VARCHAR',
                    'constraint' => 65,
                ],
            'parent_id' =>
                [
                    'type'       => 'INT',
                    'constraint' => 8,
                    'unsigned'   => TRUE,
                    'null'       => TRUE,
                ],

           'created_at DATETIME default CURRENT_TIMESTAMP',
           
        ]);

        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (parent_id) REFERENCES user_permissions_categories(id)');
        $this->dbforge->create_table('user_permissions_categories');
    }

    public function down()
    {
        $this->dbforge->drop_table('user_permissions_categories',TRUE);
    }
}