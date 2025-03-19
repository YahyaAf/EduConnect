<?php

namespace App\Repositories;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleRepository
{
    /**
     * Get all roles.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllRoles()
    {
        return Role::all();
    }

    /**
     * Find a role by ID.
     *
     * @param  int  $id
     * @return \Spatie\Permission\Models\Role
     */
    public function findRoleById($id)
    {
        return Role::with('permissions')->findOrFail($id);
    }

    /**
     * Create a new role.
     *
     * @param  array  $data
     * @return \Spatie\Permission\Models\Role
     */
    public function createRole($data)
    {
        return Role::create($data);
    }

    /**
     * Update an existing role.
     *
     * @param  \Spatie\Permission\Models\Role  $role
     * @param  array  $data
     * @return \Spatie\Permission\Models\Role
     */
    public function updateRole($role, $data)
    {
        $role->update($data);
        return $role;
    }

    /**
     * Delete a role.
     *
     * @param  \Spatie\Permission\Models\Role  $role
     * @return bool
     */
    public function deleteRole($role)
    {
        return $role->delete();
    }

    /**
     * Assign permissions to a role.
     *
     * @param  \Spatie\Permission\Models\Role  $role
     * @param  array  $permissions
     * @return void
     */
    public function assignPermissionsToRole($role, $permissions)
    {
        $role->givePermissionTo($permissions);
    }

    /**
     * Revoke permissions from a role.
     *
     * @param  \Spatie\Permission\Models\Role  $role
     * @param  array  $permissions
     * @return void
     */
    public function revokePermissionsFromRole($role, $permissions)
    {
        $role->revokePermissionTo($permissions);
    }
}
