<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateBookingsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([

            'id' => [
                'type' => 'CHAR',
                'constraint' => 36,
                'null' => false,
            ],

            'user_id' => [
                'type' => 'CHAR',
                'constraint' => 36,
                'null' => false,
            ],

            'show_id' => [
                'type' => 'CHAR',
                'constraint' => 36,
                'null' => false,
            ],

            'booking_number' => [
                'type'       => 'VARCHAR',
                'constraint' => 30,
            ],

            'total_amount' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
            ],

            'status' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'default'    => 'confirmed',
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

        $this->forge->addKey('show_id');

        $this->forge->addUniqueKey('booking_number');

        $this->forge->createTable('bookings');
    }

    public function down()
    {
        $this->forge->dropTable('bookings');
    }
}
