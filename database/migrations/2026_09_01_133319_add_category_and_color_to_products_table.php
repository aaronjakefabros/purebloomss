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
    Schema::table('order_items', function (Blueprint $table) {
        if (!Schema::hasColumn('order_items', 'color')) {
            $table->string('color')->nullable()->after('price');
        }
        if (!Schema::hasColumn('order_items', 'addons')) {
            $table->text('addons')->nullable()->after('color'); // JSON or comma-separated
        }
    });
}

public function down(): void
{
    Schema::table('order_items', function (Blueprint $table) {
        $table->dropColumn(['color', 'addons']);
    });
}
};
