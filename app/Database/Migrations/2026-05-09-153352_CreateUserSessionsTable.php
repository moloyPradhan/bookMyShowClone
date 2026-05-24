<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUserSessionsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([

            'id' => [
                'type'       => 'CHAR',
                'constraint' => 36,
                'null'       => false,
            ],

            'user_id' => [
                'type' => 'CHAR',
                'constraint' => 36,
                'null' => false,
            ],

            'refresh_token' => [
                'type' => 'TEXT',
                'null' => false,
            ],

            'ip_address' => [
                'type'       => 'VARCHAR',
                'constraint' => 45,
                'null'       => true,
            ],

            'user_agent' => [
                'type' => 'TEXT',
                'null' => true,
            ],

            'expires_at' => [
                'type' => 'TIMESTAMP',
                'null' => false,
            ],

            'created_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],

            'updated_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);

        $this->forge->addKey('user_id');

        $this->forge->createTable('user_sessions');
    }

    public function down()
    {
        $this->forge->dropTable('user_sessions');
    }
}
