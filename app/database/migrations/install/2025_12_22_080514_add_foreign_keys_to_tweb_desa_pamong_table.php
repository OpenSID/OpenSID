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
        Schema::table('tweb_desa_pamong', function (Blueprint $table) {
            $table->foreign(['config_id'], 'tweb_desa_pamong_config_fk')->references(['id'])->on('config')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['jabatan_id'], 'tweb_desa_pamong_jabatan_fk')->references(['id'])->on('ref_jabatan')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['id_pend'], 'tweb_desa_pamong_pend_fk')->references(['id'])->on('tweb_penduduk')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tweb_desa_pamong', function (Blueprint $table) {
            $table->dropForeign('tweb_desa_pamong_config_fk');
            $table->dropForeign('tweb_desa_pamong_jabatan_fk');
            $table->dropForeign('tweb_desa_pamong_pend_fk');
        });
    }
};
