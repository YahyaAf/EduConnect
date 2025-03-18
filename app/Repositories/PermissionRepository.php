<?php

namespace App\Repositories;

use Spatie\Permission\Models\Permission;

class PermissionRepository
{
    public function getAllPermissions()
    {
        return Permission::all();
    }

    public function createPermission($data)
    {
        return Permission::create($data);
    }

    public function getPermissionById($id)
    {
        return Permission::findOrFail($id);
    }

    public function updatePermission($id, $data)
    {
        $permission = Permission::findOrFail($id);
        $permission->update($data);
        return $permission;
    }

    public function deletePermission($id)
    {
        $permission = Permission::findOrFail($id);
        $permission->delete();
    }
}
