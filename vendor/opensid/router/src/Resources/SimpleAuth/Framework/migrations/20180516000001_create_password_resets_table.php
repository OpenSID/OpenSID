<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_create_password_resets_table extends CI_Migration
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
            'email' =>
                [
                    'type'       => 'VARCHAR',
                    'constraint' => '255',
                ],
            'token' =>
                [
                    'type'       => 'VARCHAR',
                    'constraint' => '255',
                ],
            'active' =>
                [
                    'type'           => 'INT',
                    'constraint'     => 1,
                    'default'        => 1,
                ],

            'created_at DATETIME default CURRENT_TIMESTAMP',

        ]);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('password_resets');
    }

    public function down()
    {
        $this->dbforge->drop_table('password_resets',TRUE);
    }
}