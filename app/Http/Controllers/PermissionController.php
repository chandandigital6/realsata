<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class PermissionController extends Controller
{
    public function index()
    {
        return view('permissions.index', [
            'permissions' => Permission::orderBy('name')->get(),
            'roles' => Role::with('permissions')->orderBy('name')->get(),
            'users' => User::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name',
        ]);

        Permission::create([
            'name' => strtolower(trim($request->name)),
            'guard_name' => 'web',
        ]);

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        return back()->with('success', 'Permission created successfully.');
    }

    public function assign(Request $request)
    {
        $request->validate([
            'assign_type' => 'required|in:role,user',
            'role_id' => 'required_if:assign_type,role|nullable|exists:roles,id',
            'user_id' => 'required_if:assign_type,user|nullable|exists:users,id',
            'permissions' => 'required|array|min:1',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $permissionNames = Permission::whereIn('id', $request->permissions)
            ->pluck('name')
            ->toArray();

        if ($request->assign_type === 'role') {
            $role = Role::findOrFail($request->role_id);
            $role->syncPermissions($permissionNames);

            $message = 'Permissions assigned to role successfully.';
        } else {
            $user = User::findOrFail($request->user_id);
            $user->syncPermissions($permissionNames);

            $message = 'Permissions assigned to user successfully.';
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        return back()->with('success', $message);
    }

    public function destroy(Permission $permission)
    {
        $permission->delete();

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        return back()->with('success', 'Permission deleted successfully.');
    }
}