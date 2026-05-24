<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateScreensTable extends Migration
{
    public function up()
    {
        $this->forge->addField([

            'id' => [
                'type' => 'CHAR',
                'constraint' => 36,
                'null' => false,
            ],

            'theater_id' => [
                'type' => 'CHAR',
                'constraint' => 36,
                'null' => false,
            ],

            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],

            'type' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'default'    => '2D',
            ],

            'total_seats' => [
                'type' => 'INT',
                'constraint' => 5,
            ],

            'status' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'default'    => 'active',
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

        $this->forge->addKey('theater_id');

        $this->forge->createTable('screens');
    }

    public function down()
    {
        $this->forge->dropTable('screens');
    }
}