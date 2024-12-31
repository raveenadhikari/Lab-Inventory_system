<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run()
    {
        //
        $roles = [
            ['name' => 'Super Admin', 'description' => 'Full access to the system'],
            ['name' => 'Admin', 'description' => 'Manage users and permissions'],
            ['name' => 'Student', 'description' => 'Default role with limited access'],
            ['name' => 'Lab Assistant', 'description' => 'Manage lab inventory'],
            ['name' => 'Dean', 'description' => 'View and manage departments'],
            ['name' => 'Department Head', 'description' => 'Assign lab owners and manage labs'],
            ['name' => 'Lecturer', 'description' => 'Borrow and return lab items'],
            ['name' => 'Lab Staff', 'description' => 'Assist in lab operations'],
        ];

        foreach ($roles as $role) {
            $existingRole = $this->db->table('roles')
                ->where('name', $role['name'])
                ->get()
                ->getRow();

            if (!$existingRole) {
                $this->db->table('roles')->insert($role);
            }
        }
        echo "Roles seeding completed successfully.\n";
    }
}
