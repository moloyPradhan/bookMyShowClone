<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateShowsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([

            'id' => [
                'type' => 'CHAR',
                'constraint' => 36,
                'null' => false,
            ],

            'movie_id' => [
                'type' => 'CHAR',
                'constraint' => 36,
                'null' => false,
            ],

            'screen_id' => [
                'type' => 'CHAR',
                'constraint' => 36,
                'null' => false,
            ],

            'start_time' => [
                'type' => 'TIMESTAMP',
                'null' => false,
            ],

            'end_time' => [
                'type' => 'TIMESTAMP',
                'null' => false,
            ],

            'price' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
            ],

            'language' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
            ],

            'format' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'default'    => '2D',
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

        $this->forge->addKey('movie_id');

        $this->forge->addKey('screen_id');

        $this->forge->createTable('shows');
    }

    public function down()
    {
        $this->forge->dropTable('shows');
    }
}