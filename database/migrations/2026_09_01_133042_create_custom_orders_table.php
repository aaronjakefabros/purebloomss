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
    Schema::create('custom_orders', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->string('reference_image'); // Customer's reference photo
        $table->text('description'); // Description of what they want
        $table->string('preferred_colors')->nullable();
        $table->decimal('budget', 10, 2)->nullable();
        $table->string('occasion')->nullable(); // Birthday, Anniversary, etc.
        $table->enum('status', ['pending', 'in_progress', 'completed', 'cancelled'])->default('pending');
        $table->text('admin_notes')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('custom_orders');
    }
};
