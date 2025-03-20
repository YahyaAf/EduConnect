<?php

namespace App\Http\Controllers\V1;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Services\PermissionService;
use Illuminate\Routing\Controller as BaseController;

class PermissionController extends BaseController
{
    protected $permissionService;

    public function __construct(PermissionService $permissionService)
    {
        $this->permissionService = $permissionService;

        $this->middleware('can:create-permission')->only(['store']);
        $this->middleware('can:edit-permission')->only(['update']);
        $this->middleware('can:delete-permission')->only(['destroy']);
        $this->middleware('can:view-permission')->only(['index', 'show']);
    }

    public function index()
    {
        $permissions = $this->permissionService->getPermissions();
        return response()->json($permissions);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:permissions,name',
        ]);

        $permission = $this->permissionService->createPermission([
            'name' => $request->name,
            // 'guard_name' => 'api', 
        ]); 

        return response()->json([
            'message' => 'Permission created successfully',
            'permission' => $permission
        ], 201);
    }

    public function show($id)
    {
        $permission = $this->permissionService->getPermission($id);
        return response()->json($permission);
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'name' => 'required|string|unique:permissions,name,' . $id
            ]);

            $permission = $this->permissionService->updatePermission($id, [
                'name' => $request->name
            ]);

            return response()->json([
                'message' => 'Permission updated successfully',
                'permission' => $permission
            ]);
        } catch (\Exception $e) {
            \Log::error('Error updating permission: ' . $e->getMessage());
            return response()->json([
                'message' => 'An error occurred while updating the permission.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id): JsonResponse
    {
        $test = $this->permissionService->deletePermission($id);

        return response()->json(['success' => $test['success'], 'message' => $test['message'],], $test['success'] ? 200 : 404);
    } 
}
