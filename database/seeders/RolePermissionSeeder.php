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
            \Spatie\Permission\Models\Permission::firstOrCreate(['name' => $permission]);
        }

        // Create Roles
        $adminRole = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'Super Admin']);
        $instructorRole = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'Instructor']);
        $userRole = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'User']);

        // Assign Permissions to Roles
        $adminRole->givePermissionTo(\Spatie\Permission\Models\Permission::all());
        $instructorRole->givePermissionTo(['manage products', 'view content']);
        $userRole->givePermissionTo(['view content']);

        // Create Super Admin User
        $admin = \App\Models\User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
            ]
        );
        $admin->assignRole($adminRole);

        // Create Instructor User
        $instructor = \App\Models\User::firstOrCreate(
            ['email' => 'instructor@example.com'],
            [
                'name' => 'Instructor',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
            ]
        );
        $instructor->assignRole($instructorRole);

        // Create Regular User
        $user = \App\Models\User::firstOrCreate(
            ['email' => 'student@example.com'],
            [
                'name' => 'Student',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
            ]
        );
        $user->assignRole($userRole);

        // Seed Default Settings
        $settings = [
            'site_name' => 'EduStore',
            'support_email' => 'support@edustore.com',
            'upi_id' => 'admin@upi',
            'upi_name' => 'EduStore Admin',
        ];

        foreach ($settings as $key => $value) {
            \App\Models\Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }
    }
}
