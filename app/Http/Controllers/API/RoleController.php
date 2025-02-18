<?php


namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;

class RoleController extends Controller
{
    // // TODO: Uncomment the code below to apply permissions middleware
    // public function __construct()
    // {
    //     // Applying permissions middleware
    //     $this->middleware('permission:view role', ['only' => ['index']]);
    //     $this->middleware('permission:create role', ['only' => ['store', 'givePermissionToRole']]);
    //     $this->middleware('permission:update role', ['only' => ['update']]);
    //     $this->middleware('permission:delete role', ['only' => ['destroy']]);
        
    // }

    public function index()
    {
        try {
            $roles = Role::all();
            return response()->json($roles, 200);
        } catch (\Exception $e) {
            Log::info('Failed to retrieve roles', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to retrieve roles'], 500);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'unique:roles,name'
            ]
        ]);

        try {
            $role = Role::create([
                'name' => $request->name,
                'guard_name' => 'web'
            ]);
            return response()->json(['message' => 'Role Created Successfully', 'role' => $role], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to create role'], 500);
        }
    }

    public function update(Request $request, $roleId)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'unique:roles,name,' . $roleId
            ]
        ]);

        try {
            $role = Role::findOrFail($roleId);
            $role->update(['name' => $request->name]);
            return response()->json(['message' => 'Role Updated Successfully', 'role' => $role], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Role not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update role'], 500);
        }
    }

    public function destroy($roleId)
    {
        try {
            $role = Role::findOrFail($roleId);
            $role->delete();
            return response()->json(['message' => 'Role Deleted Successfully'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Role not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete role'], 500);
        }
    }

    public function addPermissionToRole($roleId)
    {
        try {
            $permissions = Permission::all();
            $role = Role::findOrFail($roleId);
            $rolePermissions = DB::table('role_has_permissions')
                ->where('role_has_permissions.role_id', $role->id)
                ->pluck('role_has_permissions.permission_id', 'role_has_permissions.permission_id')
                ->all();

            return response()->json([
                'role' => $role,
                'permissions' => $permissions,
                'rolePermissions' => $rolePermissions
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Role not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to retrieve permissions'], 500);
        }
    }

    public function givePermissionToRole(Request $request, $roleId)
    {
        // Validate request
        if (!$request->has('permission')) {
            return response()->json(['error' => 'Permission is required'], 422);
        }

        //  check if permission is an array
        if (!is_array($request->permission)) {
            return response()->json(['error' => 'Permission must be an array'], 422);
        }
        
        $request->validate([
            'permission' => 'required|array'
        ]);

        try {
            $role = Role::findOrFail($roleId);
            $role->syncPermissions($request->permission);
            return response()->json(['message' => 'Permissions added to role'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Role not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to add permissions'], 500);
        }
    }
}
