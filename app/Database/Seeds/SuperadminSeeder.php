<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SuperadminSeeder extends Seeder
{
    public function run()
    {
        //
        $password = password_hash('superadmin123', PASSWORD_DEFAULT);

        $data = [
            'username' => 'superadmin',
            'email' => '2021s18744@stu.cmb.ac.lk',
            'password_hash' => $password,
            'faculty' => 'System',
            'department' => null,
            'role_id' => 1, // Assuming 1 is the role ID for 'Super Admin'
        ];

        // Insert the superadmin into the users table
        $this->db->table('users')->insert($data);

        echo "Superadmin user created successfully.\n";
    }
}
