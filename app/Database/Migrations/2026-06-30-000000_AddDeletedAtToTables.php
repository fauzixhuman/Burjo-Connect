<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDeletedAtToTables extends Migration
{
    public function up()
    {
        $fields = [
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ];
        
        $this->forge->addColumn('menus', $fields);
        $this->forge->addColumn('categories', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('menus', 'deleted_at');
        $this->forge->dropColumn('categories', 'deleted_at');
    }
}
