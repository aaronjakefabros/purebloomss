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
    Schema::create('activity_logs', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('user_id')->nullable(); // Nullable kung guest ang gumawa ng action
        $table->string('action'); // Halimbawa: 'Login', 'Register', 'Place Order', 'Update Profile'
        $table->text('description')->nullable(); // Detalye ng nangyari
        $table->string('ip_address')->nullable(); // Para makita kung saang device/network nag-logs
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
