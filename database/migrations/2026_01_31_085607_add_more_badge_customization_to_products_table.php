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
        Schema::table('products', function (Blueprint $table) {
            $table->string('highlight_badge_position')->default('left_top')->after('highlight_badge_shape');
            $table->string('highlight_badge_color')->default('golden')->after('highlight_badge_position');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['highlight_badge_position', 'highlight_badge_color']);
        });
    }
};
