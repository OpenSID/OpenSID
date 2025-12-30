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
        Schema::table('kader_pemberdayaan_masyarakat', function (Blueprint $table) {
            $table->foreign(['config_id'], 'kader_pemberdayaan_masyarakat_config_fk')->references(['id'])->on('config')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['penduduk_id'], 'kader_pemberdayaan_masyarakat_penduduk_fk')->references(['id'])->on('tweb_penduduk')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kader_pemberdayaan_masyarakat', function (Blueprint $table) {
            $table->dropForeign('kader_pemberdayaan_masyarakat_config_fk');
            $table->dropForeign('kader_pemberdayaan_masyarakat_penduduk_fk');
        });
    }
};
