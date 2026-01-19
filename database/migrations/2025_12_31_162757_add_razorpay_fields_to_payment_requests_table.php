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
        Schema::table('payment_requests', function (Blueprint $table) {
            $table->string('razorpay_order_id')->nullable()->after('payment_proof');
            $table->string('razorpay_payment_id')->nullable()->after('razorpay_order_id');
            $table->string('razorpay_signature')->nullable()->after('razorpay_payment_id');
            // status already exists in the model and probably migration, but let's ensure it handles 'completed' etc.
            // If status doesn't exist, we would add it. Based on model it exists.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payment_requests', function (Blueprint $table) {
            $table->dropColumn(['razorpay_order_id', 'razorpay_payment_id', 'razorpay_signature']);
        });
    }
};
