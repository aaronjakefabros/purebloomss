<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('custom_orders', function (Blueprint $table) {
            // Drop ang old status column
            $table->dropColumn('status');
        });
        
        Schema::table('custom_orders', function (Blueprint $table) {
            // Re-add ang status column na may mas malaking size
            $table->string('status')->default('Pending')->after('reference_image');
        });
    }

    public function down(): void
    {
        Schema::table('custom_orders', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->string('status')->default('Pending');
        });
    }
};