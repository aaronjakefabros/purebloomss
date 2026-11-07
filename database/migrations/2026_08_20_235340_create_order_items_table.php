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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            
            // INAYOS: Ginawang nullable para hindi mag-error kapag Custom Order o walang specific product
            $table->foreignId('product_id')->nullable()->constrained()->onDelete('set null');
            
            $table->integer('quantity');
            $table->decimal('price', 10, 2); // price at the time of order
            $table->string('color')->nullable();
            $table->text('addons')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};