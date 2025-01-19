<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        //
        $rolePermissions = [
            // Super Admin
            ['role_id' => 1, 'permission_id' => 1], // Manage Roles
            ['role_id' => 1, 'permission_id' => 2], // Manage Users
            ['role_id' => 1, 'permission_id' => 3], // Borrow Items
            ['role_id' => 1, 'permission_id' => 4], // Return Items
            ['role_id' => 1, 'permission_id' => 5], // View Inventory
            ['role_id' => 1, 'permission_id' => 7], // Manage Permissions
            ['role_id' => 1, 'permission_id' => 8], // Manage Labs

            // Admin
            ['role_id' => 2, 'permission_id' => 1], // Manage Roles
            ['role_id' => 2, 'permission_id' => 2], // Manage Users
            ['role_id' => 2, 'permission_id' => 5], // View Inventory
            ['role_id' => 2, 'permission_id' => 8], // Manage Labs

            ['role_id' => 2, 'permission_id' => 7], // Manage Permissions

            // Student
            ['role_id' => 3, 'permission_id' => 3], // Borrow Items
            ['role_id' => 3, 'permission_id' => 4], // Return Items

            // Lab Manager
            ['role_id' => 4, 'permission_id' => 3], // Borrow Items
            ['role_id' => 4, 'permission_id' => 4], // Return Items
            ['role_id' => 4, 'permission_id' => 5], // View Inventory
            ['role_id' => 4, 'permission_id' => 6], // Manage Inventory

            // Dean
            ['role_id' => 6, 'permission_id' => 5], // View Inventory
            ['role_id' => 6, 'permission_id' => 3], // Borrow Items
            ['role_id' => 6, 'permission_id' => 4], // Return Items

            // Department Head
            ['role_id' => 7, 'permission_id' => 5], // View Inventory
            ['role_id' => 7, 'permission_id' => 3], // Borrow Items
            ['role_id' => 7, 'permission_id' => 4], // Return Items

            // Lecturer
            ['role_id' => 8, 'permission_id' => 3], // Borrow Items
            ['role_id' => 8, 'permission_id' => 4], // Return Items

            // Lab Staff
            ['role_id' => 9, 'permission_id' => 3], // Borrow Items
            ['role_id' => 9, 'permission_id' => 4], // Return Items
            ['role_id' => 9, 'permission_id' => 5], // View Inventory
        ];

        foreach ($rolePermissions as $rolePermission) {
            $existingRolePermission = $this->db->table('role_permissions')
                ->where('role_id', $rolePermission['role_id'])
                ->where('permission_id', $rolePermission['permission_id'])
                ->get()
                ->getRow();

            if (!$existingRolePermission) {
                $this->db->table('role_permissions')->insert($rolePermission);
            }
        }
    }
}
