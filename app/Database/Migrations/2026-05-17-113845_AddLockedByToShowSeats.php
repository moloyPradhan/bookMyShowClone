<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddLockedByToShowSeats extends Migration
{
    public function up()
    {
        $this->forge->addColumn('show_seats', [

            'locked_by' => [

                'type' => 'UUID',

                'null' => true,

                'after' => 'locked_until',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn(
            'show_seats',
            'locked_by'
        );
    }
}
