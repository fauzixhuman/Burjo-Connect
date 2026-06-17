<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSessionIdToOrders extends Migration
{
    public function up()
    {
        // Add session_id to orders
        $this->forge->addColumn('orders', [
            'session_id' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
                'after'      => 'id',
            ],
        ]);

        // Create transactions table
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'order_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'payment_method' => [
                'type'       => 'ENUM',
                'constraint' => ['qris', 'cash'],
                'default'    => 'qris',
            ],
            'payment_mode' => [
                'type'       => 'ENUM',
                'constraint' => ['single', 'split'],
                'default'    => 'single',
            ],
            'amount' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'default'    => 0,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['paid', 'unpaid'],
                'default'    => 'unpaid',
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
        $this->forge->addForeignKey('order_id', 'orders', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('transactions');

        // Add transaction_id to order_items
        $this->forge->addColumn('order_items', [
            'transaction_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'order_id',
            ],
        ]);
        
        $this->forge->addForeignKey('transaction_id', 'transactions', 'id', 'SET NULL', 'CASCADE', 'order_items');
        $this->forge->processIndexes('order_items');
    }

    public function down()
    {
        $this->forge->dropForeignKey('order_items', 'order_items_transaction_id_foreign');
        $this->forge->dropColumn('order_items', 'transaction_id');
        $this->forge->dropTable('transactions');
        $this->forge->dropColumn('orders', 'session_id');
    }
}
