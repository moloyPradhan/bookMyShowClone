<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMoviesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([

            'id' => [
                'type' => 'UUID',
                'null' => false,
            ],

            'title' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],

            'slug' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],

            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],

            'duration_minutes' => [
                'type' => 'INT',
                'constraint' => 5,
            ],

            'language' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],

            'genre' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],

            'release_date' => [
                'type' => 'DATE',
                'null' => true,
            ],

            'poster_url' => [
                'type' => 'TEXT',
                'null' => true,
            ],

            'banner_url' => [
                'type' => 'TEXT',
                'null' => true,
            ],

            'trailer_url' => [
                'type' => 'TEXT',
                'null' => true,
            ],

            'censor_rating' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
            ],

            'status' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'default'    => 'upcoming',
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

        $this->forge->addUniqueKey('slug');

        $this->forge->createTable('movies');
    }

    public function down()
    {
        $this->forge->dropTable('movies');
    }
} 