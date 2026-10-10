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
    Schema::table('orders', function (Blueprint $table) {
        if (!Schema::hasColumn('orders', 'address_id')) {
            $table->foreignId('address_id')->nullable()->after('user_id')->constrained()->nullOnDelete();
        }
        if (!Schema::hasColumn('orders', 'custom_order_id')) {
            $table->foreignId('custom_order_id')->nullable()->after('address_id')->constrained()->nullOnDelete();
        }
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            //
        });
    }
};
