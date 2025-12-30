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
        Schema::table('tanah_desa', function (Blueprint $table) {
            $table->foreign(['config_id'], 'tanah_desa_config_fk')->references(['id'])->on('config')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['id_penduduk'], 'tanah_desa_penduduk_fk')->references(['id'])->on('tweb_penduduk')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tanah_desa', function (Blueprint $table) {
            $table->dropForeign('tanah_desa_config_fk');
            $table->dropForeign('tanah_desa_penduduk_fk');
        });
    }
};
