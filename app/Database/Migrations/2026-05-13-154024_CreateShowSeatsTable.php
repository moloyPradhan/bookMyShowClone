<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateShowSeatsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([

            'id' => [
                'type' => 'CHAR',
                'constraint' => 36,
                'null' => false,
            ],

            'show_id' => [
                'type' => 'CHAR',
                'constraint' => 36,
                'null' => false,
            ],

            'screen_seat_id' => [
                'type' => 'CHAR',
                'constraint' => 36,
                'null' => false,
            ],

            'status' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'default'    => 'available',
            ],

            'locked_until' => [
                'type' => 'TIMESTAMP',
                'null' => true,
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

        $this->forge->addKey('show_id');

        $this->forge->addKey('screen_seat_id');

        $this->forge->addUniqueKey([
            'show_id',
            'screen_seat_id',
        ]);

        $this->forge->createTable('show_seats');
    }

    public function down()
    {
        $this->forge->dropTable('show_seats');
    }
}