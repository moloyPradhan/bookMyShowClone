<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateEmailJobsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([

            'id' => [
                'type' => 'VARCHAR',
                'constraint' => 36,
            ],

            'to_email' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],

            'subject' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],

            'body' => [
                'type' => 'TEXT',
            ],

            'status' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'default' => 'pending',
            ],

            'error_message' => [
                'type' => 'TEXT',
                'null' => true,
            ],

            'processed_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],

            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],

            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);

        $this->forge->createTable('email_jobs');
    }

    public function down()
    {
        $this->forge->dropTable('email_jobs');
    }
}