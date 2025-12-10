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
        Schema::create('videos', function (Blueprint $table) {
            $table->id();
            // Relasi ke User
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            // Data Video
            $table->string('title', 255);
            $table->text('description')->nullable();
            $table->string('subject', 100);

            // File Path
            $table->string('video_url', 255);
            $table->string('thumbnail_url', 255)->nullable();
            $table->unsignedSmallInteger('duration_seconds')->default(60);

            $table->timestamps();
            $table->softDeletes(); // Soft Deletes untuk Video
        });
    }

    /**
     * Reverse the migrations.
     */
public function down(): void
    {
        Schema::dropIfExists('videos');
    }
};
