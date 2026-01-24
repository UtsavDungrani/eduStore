<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Create the 'Instructor' role
        \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'Instructor']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Delete the 'Instructor' role
        $role = \Spatie\Permission\Models\Role::where('name', 'Instructor')->first();
        if ($role) {
            $role->delete();
        }
    }
};
