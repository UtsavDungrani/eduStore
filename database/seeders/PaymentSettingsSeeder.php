<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('settings')->updateOrInsert(
            ['key' => 'active_payment_method'],
            ['value' => 'razorpay', 'created_at' => now(), 'updated_at' => now()]
        );

        DB::table('settings')->updateOrInsert(
            ['key' => 'upi_apps_visibility'],
            ['value' => 'show', 'created_at' => now(), 'updated_at' => now()]
        );
    }
}
