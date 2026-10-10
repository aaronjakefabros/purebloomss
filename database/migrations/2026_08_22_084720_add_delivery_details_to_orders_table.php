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
        $table->date('delivery_date')->nullable()->after('customer_email');
        $table->string('delivery_time')->nullable()->after('delivery_date');
        $table->text('delivery_address')->nullable()->after('delivery_time');
        $table->string('phone_number')->nullable()->after('delivery_address');
    });
}

public function down(): void
{
    Schema::table('orders', function (Blueprint $table) {
        $table->dropColumn(['delivery_date', 'delivery_time', 'delivery_address', 'phone_number']);
    });
}
};
