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
        Schema::table('log_keluarga', function (Blueprint $table) {
            $table->foreign(['config_id'], 'log_keluarga_config_fk')->references(['id'])->on('config')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['id_kk'], 'log_keluarga_kk_fk')->references(['id'])->on('tweb_keluarga')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['id_pend'], 'log_keluarga_pend_fk')->references(['id'])->on('tweb_penduduk')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['id_log_penduduk'], 'log_penduduk_fk')->references(['id'])->on('log_penduduk')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('log_keluarga', function (Blueprint $table) {
            $table->dropForeign('log_keluarga_config_fk');
            $table->dropForeign('log_keluarga_kk_fk');
            $table->dropForeign('log_keluarga_pend_fk');
            $table->dropForeign('log_penduduk_fk');
        });
    }
};
