<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        //
        $permissions = [
            ['name' => 'Manage Roles', 'description' => 'Create, edit, delete roles'],
            ['name' => 'Manage Users', 'description' => 'Create, edit, delete users'],
            ['name' => 'Borrow Items', 'description' => 'Borrow items from the lab'],
            ['name' => 'Return Items', 'description' => 'Return borrowed items to the lab'],
            ['name' => 'View Inventory', 'description' => 'View lab inventory'],
            ['name' => 'Manage Inventory', 'description' => 'Add, edit, or delete lab inventory items'],
            ['name' => 'Manage Permissions', 'description' => 'Create and assign permissions to roles'],
            ['name' => 'Manage Labs', 'description' => 'Allow creating/editing/deleting labs']
        ];

        foreach ($permissions as $permission) {
            $existingPermission = $this->db->table('permissions')
                ->where('name', $permission['name'])
                ->get()
                ->getRow();

            if (!$existingPermission) {
                $this->db->table('permissions')->insert($permission);
            }
        }

        echo "Permissions seeding completed successfully.\n";
    }
}
