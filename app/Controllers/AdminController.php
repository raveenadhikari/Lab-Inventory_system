<?php

namespace App\Controllers;

use App\Models\RoleModel;
use App\Models\UserModel;

class AdminController extends BaseController
{
    public function dashboard()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('users');
        $builder->select('users.*, roles.name as role_name');
        $builder->join('roles', 'roles.id = users.role_id', 'left');
        $users = $builder->get()->getResultArray();

        $roleModel = new RoleModel();
        $roles = $roleModel->findAll();

        // Fetch all permissions
        $permissions = $db->table('permissions')->get()->getResultArray();

        // Fetch existing role-permission relationships
        $rolePermissions = $db->table('role_permissions')->get()->getResultArray();

        // Create a mapping of role_id => [permission_ids]
        $rolePermissionsMap = [];
        foreach ($rolePermissions as $rp) {
            $rolePermissionsMap[$rp['role_id']][] = $rp['permission_id'];
        }

        return view('admin/dashboard', [
            'users' => $users,
            'roles' => $roles,
            'permissions' => $permissions,
            'rolePermissionsMap' => $rolePermissionsMap,
        ]);
    }

    public function updateRole($userId)
    {
        $roleId = $this->request->getPost('role_id');
        $userModel = new UserModel();

        $userModel->update($userId, ['role_id' => $roleId]);

        return redirect()->to('/dashboard')->with('success', 'Role updated successfully');
    }
    public function updateRolePermissions()
    {
        $db = \Config\Database::connect();
        $rolePermissions = $this->request->getPost('role_permissions');

        // Clear existing permissions for all roles
        $db->table('role_permissions')->truncate();

        // Insert updated permissions
        if ($rolePermissions) {
            foreach ($rolePermissions as $roleId => $permissions) {
                foreach ($permissions as $permissionId) {
                    $db->table('role_permissions')->insert([
                        'role_id' => $roleId,
                        'permission_id' => $permissionId,
                    ]);
                }
            }
        }

        return redirect()->to('/dashboard')->with('success', 'Role permissions updated successfully.');
    }
}
