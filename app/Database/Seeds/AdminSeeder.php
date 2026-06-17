<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\AdminModel;

class AdminSeeder extends Seeder
{
    public function run()
    {
        $adminModel = new AdminModel();
        
        // Cek apakah admin sudah ada
        if (!$adminModel->where('username', 'admin')->first()) {
            $adminModel->insert([
                'name'     => 'Super Admin',
                'username' => 'admin',
                'password' => password_hash('admin123', PASSWORD_DEFAULT),
            ]);
            echo "Akun admin berhasil dibuat (admin / admin123)\n";
        } else {
            echo "Akun admin sudah ada.\n";
        }
    }
}
