<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_create_email_verifications_table extends CI_Migration
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
                    'constraint' => 255,
                ],
            'token' =>
                [
                    'type'       => 'VARCHAR',
                    'constraint' => 255,
                ],

            'created_at DATETIME default CURRENT_TIMESTAMP',
            
        ]);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('email_verifications');
    }

    public function down()
    {
        $this->dbforge->drop_table('email_verifications',TRUE);
    }
}