<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PermissionController extends Controller
{
    // TODO: Uncomment the code below to apply permissions middleware
    // public function __construct()
    // {
    //     // Applying permissions middleware
    //     $this->middleware('permission:view permission', ['only' => ['index']]);
    //     $this->middleware('permission:create permission', ['only' => ['create', 'store']]);
    //     $this->middleware('permission:update permission', ['only' => ['update', 'edit']]);
    //     $this->middleware('permission:delete permission', ['only' => ['destroy']]);
    // }

    public function index()
    {
        try {
            $permissions = Permission::all();
            return response()->json($permissions, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to retrieve permissions'], 500);
        }
    }

    public function store(Request $request)
    {
         // check if permission already exists
        $permission = Permission::where('name', $request->name)->first();
        if ($permission) {
            return response()->json(['error' => 'Permission already exists'], 400);
        }

        $request->validate([
            'name' => [
                'required',
                'string',
                'unique:permissions,name'
            ]
        ]);

        try {
            $permission = Permission::create([
                'name' => $request->name,
                'guard_name' => 'api'
            ]);
            return response()->json(['message' => 'Permission Created Successfully', 'permission' => $permission], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to create permission'], 500);
        }
    }

    public function show($id)
    {
        try {
            $permission = Permission::findOrFail($id);
            return response()->json($permission, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Permission not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to retrieve permission'], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'unique:permissions,name,' . $id
            ]
        ]);

        try {
            $permission = Permission::findOrFail($id);
            $permission->update(['name' => $request->name]);
            return response()->json(['message' => 'Permission Updated Successfully', 'permission' => $permission], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Permission not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update permission'], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $permission = Permission::findOrFail($id);
            $permission->delete();
            return response()->json(['message' => 'Permission Deleted Successfully'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Permission not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete permission'], 500);
        }
    }
}
