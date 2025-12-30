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
        Schema::table('kehadiran_pengajuan_izin_detail', function (Blueprint $table) {
            $table->foreign(['config_id'])->references(['id'])->on('config')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['pengajuan_izin_id'], 'pengajuan_detail_header_fk')->references(['id'])->on('kehadiran_pengajuan_izin')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['id_pamong'], 'pengajuan_detail_pamong_fk')->references(['pamong_id'])->on('tweb_desa_pamong')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kehadiran_pengajuan_izin_detail', function (Blueprint $table) {
            $table->dropForeign('kehadiran_pengajuan_izin_detail_config_id_foreign');
            $table->dropForeign('pengajuan_detail_header_fk');
            $table->dropForeign('pengajuan_detail_pamong_fk');
        });
    }
};
