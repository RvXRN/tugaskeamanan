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
        Schema::create('rate_limits', function (Blueprint $table) {
             $table->id();
            $table->string('key'); // Key unik untuk setiap pengguna atau IP
            $table->integer('attempts')->default(0); // Jumlah percobaan login
            $table->timestamp('expires_at')->nullable(); // Waktu kadaluarsa
            $table->timestamps();

            // Index untuk mempercepat pencarian berdasarkan key
            $table->index('key');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rate_limits');
    }
};
