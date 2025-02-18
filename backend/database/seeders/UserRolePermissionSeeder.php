<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

class UserRolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if roles already exist
        if (Role::count() > 0) {
            $this->command->info('Roles already seeded, skipping UserRolePermissionSeeder.');
            return;
        }

        // Create Permissions if they don't exist
        $permissions = [
            'view role', 'create role', 'update role', 'delete role',
            'view permission', 'create permission', 'update permission', 'delete permission',
            'view user', 'create user', 'update user', 'delete user',
            'view task', 'create task', 'update task', 'delete task',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // Create Roles if they don't exist
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $userRole = Role::firstOrCreate(['name' => 'user', 'guard_name' => 'web']);

        Log::info('Roles and permissions created successfully', [
            'admin' => $adminRole->toArray(),
            'user' => $userRole->toArray(),
        ]);

        // Give specific permissions to admin role for both guards
        $adminRole->syncPermissions([
            'create role', 'view role', 'update role',
            'create permission', 'view permission',
            'create user', 'view user', 'update user',
            'create task', 'view task', 'update task',
        ]);


        $adminUser = User::firstOrCreate([
            'email' => 'admin@gmail.com',
        ], [
            'username' => 'Admin',
            'first_name' => 'Admin',
            'last_name' => 'User',
            'password' => Hash::make('12345678'),
        ]);

        $adminRole = Role::where('name', 'admin')->first();
        if ($adminRole) {
            $adminUser->assignRole($adminRole);
        } else {
            Log::error('Admin role does not exist');
        }

        $this->command->info('Roles assigned to admin user successfully.');

        Log::info('Admin user created successfully', $adminUser->toArray());

        $this->command->info('User roles and permissions seeded successfully.');
    }
}