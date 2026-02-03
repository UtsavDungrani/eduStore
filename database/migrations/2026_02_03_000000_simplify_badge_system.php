<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Add new simplified badge strip columns
            $table->string('badge_strip_text')->nullable()->default(null)->after('sale_display_mode');
            $table->string('badge_strip_color')->default('golden')->after('badge_strip_text');

            // Drop old complex badge columns if they exist
            if (Schema::hasColumn('products', 'highlight_badge')) {
                $table->dropColumn('highlight_badge');
            }
            if (Schema::hasColumn('products', 'highlight_badge_shape')) {
                $table->dropColumn('highlight_badge_shape');
            }
            if (Schema::hasColumn('products', 'highlight_badge_color')) {
                $table->dropColumn('highlight_badge_color');
            }
            if (Schema::hasColumn('products', 'highlight_badge_position')) {
                $table->dropColumn('highlight_badge_position');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Remove new columns
            if (Schema::hasColumn('products', 'badge_strip_text')) {
                $table->dropColumn('badge_strip_text');
            }
            if (Schema::hasColumn('products', 'badge_strip_color')) {
                $table->dropColumn('badge_strip_color');
            }
        });
    }
};
