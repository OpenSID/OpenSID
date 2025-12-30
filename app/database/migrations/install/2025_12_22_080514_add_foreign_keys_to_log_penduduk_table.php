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
        Schema::table('log_penduduk', function (Blueprint $table) {
            $table->foreign(['id_pend'], 'fk_tweb_penduduk')->references(['id'])->on('tweb_penduduk')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['ref_pindah'], 'id_ref_pindah')->references(['id'])->on('ref_pindah')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['config_id'], 'log_penduduk_config_fk')->references(['id'])->on('config')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('log_penduduk', function (Blueprint $table) {
            $table->dropForeign('fk_tweb_penduduk');
            $table->dropForeign('id_ref_pindah');
            $table->dropForeign('log_penduduk_config_fk');
        });
    }
};
