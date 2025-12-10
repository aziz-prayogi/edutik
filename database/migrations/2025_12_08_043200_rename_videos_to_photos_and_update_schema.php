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
        Schema::rename('videos', 'photos');

    // 2. Modifikasi kolom di tabel 'photos'
        Schema::table('photos', function (Blueprint $table) {
            // Ganti nama kolom path file
            $table->renameColumn('video_url', 'photo_url');

            // Hapus kolom yang tidak relevan untuk foto
            $table->dropColumn('duration_seconds');

            // Hapus kolom thumbnail (jika thumbnail = foto utama)
            $table->dropColumn('thumbnail_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::rename('photos', 'videos');

    // 2. Rollback kolom (jika Anda benar-benar perlu kembali ke video)
        Schema::table('videos', function (Blueprint $table) {
            $table->renameColumn('photo_url', 'video_url');

            // Tambahkan kembali kolom yang dihapus saat rollback
            $table->string('thumbnail_url', 255)->nullable()->after('video_url');
            $table->unsignedSmallInteger('duration_seconds')->default(60)->after('thumbnail_url');
        });
    }
};
