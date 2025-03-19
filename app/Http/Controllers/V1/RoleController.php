<?php

namespace App\Http\Controllers\V1;

use App\Models\User;
use Illuminate\Http\Request;
use App\Services\RoleService;  
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

class RoleController extends Controller
{
    protected $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }

    public function index(): JsonResponse
    {
        $roles = $this->roleService->getRoles();
        return response()->json($roles);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|unique:roles,name',
        ]);

        $role = $this->roleService->createRole([
            'name' => $request->name,
        ]);

        return response()->json([
            'message' => 'Role created successfully',
            'role' => $role
        ], 201);
    }

    public function show($id): JsonResponse
    {
        $role = $this->roleService->getRoles($id);
        return response()->json($role);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|unique:roles,name,' . $id,
        ]);

        $role = $this->roleService->updateRole($id, [
            'name' => $request->name,
        ]);

        return response()->json([
            'message' => 'Role updated successfully',
            'role' => $role
        ]);
    }

    public function destroy($id): JsonResponse
    {
        $this->roleService->deleteRole($id);

        return response()->json([
            'message' => 'Role deleted successfully',
        ]);
    }

    public function assignPermissions(Request $request, $roleId): JsonResponse
    {
        $request->validate([
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $this->roleService->assignPermissionsToRole($roleId, $request->permissions);

        return response()->json([
            'message' => 'Permissions assigned successfully to the role.',
        ]);
    }

    public function revokePermissions(Request $request, $roleId): JsonResponse
    {
        $request->validate([
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $this->roleService->revokePermissionsFromRole($roleId, $request->permissions);

        return response()->json([
            'message' => 'Permissions revoked successfully from the role.',
        ]);
    }

    public function assignRoleToUser(Request $request, $userId): JsonResponse
    {
        $request->validate([
            'role' => 'required|string|exists:roles,name', 
        ]);

        $user = User::findOrFail($userId); 

        $user->assignRole($request->role);

        return response()->json([
            'message' => 'Role assigned successfully to the user.',
        ]);
    }

}
