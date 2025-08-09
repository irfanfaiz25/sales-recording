<?php

namespace Database\Seeders;

use Hash;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            // Users permissions
            'view-users',
            'create-users',
            'edit-users',
            'delete-users',

            // Items permissions
            'view-items',
            'create-items',
            'edit-items',
            'delete-items',

            // Sales permissions
            'view-sales',
            'create-sales',
            'edit-sales',
            'delete-sales',

            // Payments permissions
            'view-payments',
            'create-payments',
            'edit-payments',
            'delete-payments',

            // Reports permissions
            'view-reports',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles and assign permissions
        $adminRole = Role::create(['name' => 'admin']);
        $kasirRole = Role::create(['name' => 'kasir']);

        // Admin gets all permissions
        $adminRole->givePermissionTo(Permission::all());

        // Kasir gets limited permissions
        $kasirRole->givePermissionTo([
            'view-items',
            'create-items',
            'edit-items',
            'delete-items',
            'view-sales',
            'create-sales',
            'edit-sales',
            'delete-sales',
            'view-payments',
            'create-payments',
            'edit-payments',
            'delete-payments',
        ]);

        // Create default admin user
        $adminUser = User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('rahasia123'),
        ]);
        $adminUser->assignRole('admin');

        // Create default kasir user
        $kasirUser = User::create([
            'name' => 'Kasir',
            'email' => 'kasir@gmail.com',
            'password' => Hash::make('rahasia123'),
        ]);
        $kasirUser->assignRole('kasir');
    }
}
