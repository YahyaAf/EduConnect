<?php

namespace App\Services;

use App\Repositories\PermissionRepository;

class PermissionService
{
    protected $permissionRepository;

    public function __construct(PermissionRepository $permissionRepository)
    {
        $this->permissionRepository = $permissionRepository;
    }

    public function getPermissions()
    {
        return $this->permissionRepository->getAllPermissions();
    }

    public function createPermission($data)
    {
        return $this->permissionRepository->createPermission($data);
    }

    public function getPermission($id)
    {
        return $this->permissionRepository->getPermissionById($id);
    }

    public function updatePermission($id, $data)
    {
        return $this->permissionRepository->updatePermission($id, $data);
    }

    public function deletePermission($id)
    {
        return $this->permissionRepository->deletePermission($id);
    }
}
