<?php

namespace App\Http\Controllers\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::all();
        return response()->json($permissions);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:permissions,name',
        ]);

        $permission = Permission::create([
            'name' => $request->name,
            'guard_name' => 'web', 
        ]);

        return response()->json([
            'message' => 'Permission created successfully',
            'permission' => $permission
        ], 201);
    }


    public function show($id)
    {
        $permission = Permission::findOrFail($id);
        return response()->json($permission);
    }


    public function update(Request $request, $id)
    {
        try {
            $permission = Permission::findOrFail($id);

            $request->validate([
                'name' => 'required|string|unique:permissions,name,' . $id
            ]);

            $permission->update(['name' => $request->name]);

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

    public function destroy($id)
    {
        $permission = Permission::findOrFail($id);
        $permission->delete();

        return response()->json([
            'message' => 'Permission deleted successfully',
        ]);
    }




}
