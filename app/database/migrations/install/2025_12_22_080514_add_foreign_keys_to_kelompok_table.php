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
        Schema::table('kelompok', function (Blueprint $table) {
            $table->foreign(['config_id'], 'kelompok_config_fk')->references(['id'])->on('config')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['id_master'], 'kelompok_kelompok_master_fk')->references(['id'])->on('kelompok_master')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['id_ketua'], 'kelompok_ketua_fk')->references(['id'])->on('tweb_penduduk')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kelompok', function (Blueprint $table) {
            $table->dropForeign('kelompok_config_fk');
            $table->dropForeign('kelompok_kelompok_master_fk');
            $table->dropForeign('kelompok_ketua_fk');
        });
    }
};
