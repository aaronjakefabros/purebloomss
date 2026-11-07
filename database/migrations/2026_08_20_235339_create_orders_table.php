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
    Schema::create('orders', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
        
        $table->string('customer_name')->nullable();
        $table->string('customer_email')->nullable();
        $table->string('phone_number')->nullable(); // <--- IDINAGDAG
        
        // Address Fields
        $table->string('house_no')->nullable();
        $table->string('street')->nullable();
        $table->string('barangay')->nullable();
        $table->string('city')->nullable();
        $table->string('province')->nullable();
        $table->string('postal_code')->nullable();
        
        // Order Details
        $table->decimal('total_amount', 10, 2)->default(0);
        $table->decimal('delivery_fee', 10, 2)->default(0); // <--- IDINAGDAG
        $table->string('delivery_method')->nullable();
        $table->date('delivery_date')->nullable();          // <--- IDINAGDAG
        $table->string('delivery_time')->nullable();        // <--- IDINAGDAG
        
        $table->string('payment_method')->nullable();
        $table->string('payment_status')->default('Pending');
        $table->string('status')->default('Pending');
        
        // CUSTOM ORDER FIELDS (Important!)
        $table->string('order_type')->default('regular');   // <--- IDINAGDAG
        $table->text('custom_message')->nullable();         // <--- IDINAGDAG
        $table->text('messenger_link')->nullable();
        
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
