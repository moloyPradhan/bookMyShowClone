<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateScreenSeatsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([

            'id' => [
                'type' => 'UUID',
                'null' => false,
            ],

            'screen_id' => [
                'type' => 'UUID',
                'null' => false,
            ],

            'seat_row' => [
                'type'       => 'VARCHAR',
                'constraint' => 5,
            ],

            'seat_number' => [
                'type' => 'INTEGER',
            ],

            'seat_label' => [
                'type'       => 'VARCHAR',
                'constraint' => 10,
            ],

            'seat_type' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'default'    => 'regular',
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

        $this->forge->addKey('screen_id');

        $this->forge->addUniqueKey([
            'screen_id',
            'seat_label',
        ]);

        $this->forge->createTable('screen_seats');
    }

    public function down()
    {
        $this->forge->dropTable('screen_seats');
    }
}