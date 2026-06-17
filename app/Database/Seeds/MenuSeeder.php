<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MenuSeeder extends Seeder
{
    public function run()
    {
        // 1. Insert Categories
        $categories = [
            ['name' => 'Makanan', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'Minuman', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'Cemilan', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
        ];
        $this->db->table('categories')->insertBatch($categories);

        // 2. Insert Menus
        $menus = [
            [
                'category_id' => 1,
                'name' => 'Nasi Telur Spesial Warmindo',
                'description' => 'Nasi putih dengan telur ceplok/dadar khas warmindo',
                'price' => 12000,
                'image_path' => null,
                'is_available' => true,
                'created_at' => date('Y-m-d H:i:s'), 
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'category_id' => 1,
                'name' => 'Indomie Goreng Jumbo Telur',
                'description' => 'Indomie goreng porsi jumbo ditambah telur',
                'price' => 15000,
                'image_path' => null,
                'is_available' => true,
                'created_at' => date('Y-m-d H:i:s'), 
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'category_id' => 2,
                'name' => 'Es Teh Manis Jumbo',
                'description' => 'Es teh manis segar ukuran jumbo',
                'price' => 5000,
                'image_path' => null,
                'is_available' => true,
                'created_at' => date('Y-m-d H:i:s'), 
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'category_id' => 2,
                'name' => 'Nutrisari Dingin',
                'description' => 'Nutrisari aneka rasa dingin',
                'price' => 5000,
                'image_path' => null,
                'is_available' => true,
                'created_at' => date('Y-m-d H:i:s'), 
                'updated_at' => date('Y-m-d H:i:s')
            ]
        ];
        $this->db->table('menus')->insertBatch($menus);
    }
}
