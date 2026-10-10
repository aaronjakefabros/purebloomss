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
        if (!Schema::hasColumn('orders', 'delivery_method')) {
            $table->string('delivery_method')->default('Delivery')->after('customer_email');
        }
        if (!Schema::hasColumn('orders', 'delivery_fee')) {
            $table->decimal('delivery_fee', 8, 2)->default(0)->after('delivery_method');
        }
    });
}

public function down(): void
{
    Schema::table('orders', function (Blueprint $table) {
        $table->dropColumn(['delivery_method', 'delivery_fee']);
    });
}
};
