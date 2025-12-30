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
        Schema::table('suplemen_terdata', function (Blueprint $table) {
            $table->foreign(['config_id'], 'suplemen_terdata_config_fk')->references(['id'])->on('config')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['keluarga_id'], 'suplemen_terdata_keluarga_fk')->references(['id'])->on('tweb_keluarga')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['penduduk_id'], 'suplemen_terdata_penduduk_fk')->references(['id'])->on('tweb_penduduk')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['id_suplemen'], 'suplemen_terdata_suplemen_fk')->references(['id'])->on('suplemen')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('suplemen_terdata', function (Blueprint $table) {
            $table->dropForeign('suplemen_terdata_config_fk');
            $table->dropForeign('suplemen_terdata_keluarga_fk');
            $table->dropForeign('suplemen_terdata_penduduk_fk');
            $table->dropForeign('suplemen_terdata_suplemen_fk');
        });
    }
};
