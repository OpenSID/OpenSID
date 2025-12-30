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
        Schema::table('dokumen', function (Blueprint $table) {
            $table->foreign(['config_id'], 'dokumen_config_fk')->references(['id'])->on('config')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['id_pend'], 'id_pend_dokumen_fk')->references(['id'])->on('tweb_penduduk')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dokumen', function (Blueprint $table) {
            $table->dropForeign('dokumen_config_fk');
            $table->dropForeign('id_pend_dokumen_fk');
        });
    }
};
