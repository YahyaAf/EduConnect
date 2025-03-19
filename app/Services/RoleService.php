<?php 

namespace App\Services;

use App\Repositories\RoleRepository;
use Spatie\Permission\Models\Permission;

class RoleService
{
    protected $roleRepository;

    public function __construct(RoleRepository $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    public function getRoles()
    {
        return $this->roleRepository->getAllRoles();
    }

    public function createRole($data)
    {
        return $this->roleRepository->createRole($data);
    }

    public function updateRole($roleId, $data)
    {
        $role = $this->roleRepository->findRoleById($roleId);
        return $this->roleRepository->updateRole($role, $data);
    }

    public function deleteRole($roleId)
    {
        $role = $this->roleRepository->findRoleById($roleId);
        return $this->roleRepository->deleteRole($role);
    }

    public function assignPermissionsToRole($roleId, $permissions)
    {
        $role = $this->roleRepository->findRoleById($roleId);
        $permissions = Permission::whereIn('id', $permissions)->get();
        return $this->roleRepository->assignPermissionsToRole($role, $permissions);
    }

    public function revokePermissionsFromRole($roleId, $permissions)
    {
        $role = $this->roleRepository->findRoleById($roleId);
        $permissions = Permission::whereIn('id', $permissions)->get();
        return $this->roleRepository->revokePermissionsFromRole($role, $permissions);
    }
}
