<?php

if (!function_exists('hasPermission')) {
    function hasPermission($permissionName)
    {
        $db = \Config\Database::connect();
        $roleId = session()->get('role_id'); // Assuming role_id is stored in the session

        // Fetch the permission ID
        $permission = $db->table('permissions')->where('name', $permissionName)->get()->getRow();
        if (!$permission) {
            return false;
        }

        // Check if the role has the permission
        $rolePermission = $db->table('role_permissions')
            ->where('role_id', $roleId)
            ->where('permission_id', $permission->id)
            ->get()
            ->getRow();

        return !empty($rolePermission);
    }
}
