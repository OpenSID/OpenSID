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
        Schema::table('permohonan_surat', function (Blueprint $table) {
            $table->foreign(['config_id'], 'permohonan_surat_config_fk')->references(['id'])->on('config')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['id_pemohon'], 'permohonan_surat_pemohon_fk')->references(['id'])->on('tweb_penduduk')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['id_surat'], 'permohonan_surat_surat_fk')->references(['id'])->on('tweb_surat_format')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permohonan_surat', function (Blueprint $table) {
            $table->dropForeign('permohonan_surat_config_fk');
            $table->dropForeign('permohonan_surat_pemohon_fk');
            $table->dropForeign('permohonan_surat_surat_fk');
        });
    }
};
