<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTheatersTable extends Migration
{
    public function up()
    {
        $this->forge->addField([

            'id' => [
                'type' => 'CHAR',
                'constraint' => 36,
                'null' => false,
            ],

            'owner_id' => [
                'type' => 'CHAR',
                'constraint' => 36,
                'null' => false,
            ],

            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
            ],

            'email' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
            ],

            'mobile' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
            ],

            'country' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],

            'state' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],

            'city' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],

            'address_line_1' => [
                'type' => 'TEXT',
            ],

            'address_line_2' => [
                'type' => 'TEXT',
                'null' => true,
            ],

            'postal_code' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
            ],

            'latitude' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,7',
                'null'       => true,
            ],

            'longitude' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,7',
                'null'       => true,
            ],

            'status' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'default'    => 'pending',
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

        $this->forge->addKey('owner_id');

        $this->forge->addUniqueKey('email');
        $this->forge->addUniqueKey('mobile');

        $this->forge->createTable('theaters');
    }

    public function down()
    {
        $this->forge->dropTable('theaters');
    }
}
