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
        Schema::create('likes', function (Blueprint $table) {
            $table->id();
            // Relasi ke User dan Video
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('photo_id')->constrained('photos')->onDelete('cascade');

            // User hanya bisa like satu video sekali
            $table->unique(['user_id', 'photo_id']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('likes');
    }
};
