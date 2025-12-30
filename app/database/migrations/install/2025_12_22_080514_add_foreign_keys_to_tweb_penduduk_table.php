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
        Schema::table('tweb_penduduk', function (Blueprint $table) {
            $table->foreign(['id_cluster'], 'tweb_penduduk_cluster_fk')->references(['id'])->on('tweb_wil_clusterdesa')->onUpdate('cascade')->onDelete('set null');
            $table->foreign(['config_id'], 'tweb_penduduk_config_fk')->references(['id'])->on('config')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['id_kk'], 'tweb_penduduk_kk_fk')->references(['id'])->on('tweb_keluarga')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tweb_penduduk', function (Blueprint $table) {
            $table->dropForeign('tweb_penduduk_cluster_fk');
            $table->dropForeign('tweb_penduduk_config_fk');
            $table->dropForeign('tweb_penduduk_kk_fk');
        });
    }
};
