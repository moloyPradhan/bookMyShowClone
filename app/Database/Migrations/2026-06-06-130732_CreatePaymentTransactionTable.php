<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePaymentTransactionTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'VARCHAR',
                'constraint' => 36,
            ],

            'uid' => [
                'type' => 'VARCHAR',
                'constraint' => 36,
            ],

            'payment_id' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],

            'order_id' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],

            'purpose' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],

            'amount' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
            ],

            'status' => [
                'type' => 'ENUM',
                'constraint' => [
                    'created',
                    'paid',
                    'failed',
                    'cancelled'
                ],
                'default' => 'created'
            ],

            'payload' => [
                'type' => 'LONGTEXT',
                'null' => true,
            ],

            'webhook_response' => [
                'type' => 'LONGTEXT',
                'null' => true,
            ],

            'success_action' => [
                'type' => 'LONGTEXT',
                'null' => true,
            ],

            'created_at DATETIME DEFAULT CURRENT_TIMESTAMP',
            'updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
        ]);

        $this->forge->addKey('id', true);

        $this->forge->createTable(
            'payment_transactions'
        );
    }

    public function down()
    {
        $this->forge->dropTable('payment_transactions');
    }
}
