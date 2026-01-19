<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create Permissions
        $permissions = [
            'manage users',
            'manage products',
            'manage banners',
            'manage categories',
            'manage settings',
            'view access logs',
            'view content',
            'download content',
        ];

        foreach ($permissions as $permission) {
            \Spatie\Permission\Models\Permission::create(['name' => $permission]);
        }

        // Create Roles
        $adminRole = \Spatie\Permission\Models\Role::create(['name' => 'Super Admin']);
        $userRole = \Spatie\Permission\Models\Role::create(['name' => 'User']);

        // Assign Permissions to Roles
        $adminRole->givePermissionTo(\Spatie\Permission\Models\Permission::all());
        $userRole->givePermissionTo(['view content']);

        // Create Super Admin User
        $admin = \App\Models\User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
        ]);
        $admin->assignRole($adminRole);

        // Create Regular User
        $user = \App\Models\User::create([
            'name' => 'Student',
            'email' => 'student@example.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
        ]);
        $user->assignRole($userRole);

        // Seed Default Settings
        $settings = [
            'site_name' => 'EduStore',
            'brand_color' => '#2563eb', // Nice Blue
            'support_email' => 'support@edustore.com',
            'upi_id' => 'admin@upi',
            'upi_name' => 'EduStore Admin',
        ];

        foreach ($settings as $key => $value) {
            \App\Models\Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }
    }
}
