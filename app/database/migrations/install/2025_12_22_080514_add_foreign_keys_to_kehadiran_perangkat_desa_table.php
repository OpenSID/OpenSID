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
        Schema::table('kehadiran_perangkat_desa', function (Blueprint $table) {
            $table->foreign(['config_id'], 'kehadiran_perangkat_desa_config_fk')->references(['id'])->on('config')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['pamong_id'], 'kehadiran_perangkat_desa_pamong_fk')->references(['pamong_id'])->on('tweb_desa_pamong')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kehadiran_perangkat_desa', function (Blueprint $table) {
            $table->dropForeign('kehadiran_perangkat_desa_config_fk');
            $table->dropForeign('kehadiran_perangkat_desa_pamong_fk');
        });
    }
};
