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
        Schema::table('pesan_mandiri', function (Blueprint $table) {
            $table->foreign(['config_id'])->references(['id'])->on('config')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['penduduk_id'])->references(['id'])->on('tweb_penduduk')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pesan_mandiri', function (Blueprint $table) {
            $table->dropForeign('pesan_mandiri_config_id_foreign');
            $table->dropForeign('pesan_mandiri_penduduk_id_foreign');
        });
    }
};
