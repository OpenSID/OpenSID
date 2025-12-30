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
        Schema::table('kehadiran_pengajuan_izin', function (Blueprint $table) {
            $table->foreign(['config_id'])->references(['id'])->on('config')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['approved_by'], 'pengajuan_izin_approved_by_fk')->references(['id'])->on('user')->onUpdate('cascade')->onDelete('set null');
            $table->foreign(['id_pamong'], 'pengajuan_izin_pamong_pamong_fk')->references(['pamong_id'])->on('tweb_desa_pamong')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kehadiran_pengajuan_izin', function (Blueprint $table) {
            $table->dropForeign('kehadiran_pengajuan_izin_config_id_foreign');
            $table->dropForeign('pengajuan_izin_approved_by_fk');
            $table->dropForeign('pengajuan_izin_pamong_pamong_fk');
        });
    }
};
