<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateBookingItemsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([

            'id' => [
                'type' => 'CHAR',
                'constraint' => 36,
                'null' => false,
            ],

            'booking_id' => [
                'type' => 'CHAR',
                'constraint' => 36,
                'null' => false,
            ],

            'show_seat_id' => [
                'type' => 'CHAR',
                'constraint' => 36,
                'null' => false,
            ],

            'price' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
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

        $this->forge->addKey('booking_id');

        $this->forge->addKey('show_seat_id');

        $this->forge->createTable('booking_items');
    }

    public function down()
    {
        $this->forge->dropTable('booking_items');
    }
}
