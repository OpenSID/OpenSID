<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Kehadiran\Models\KehadiranPengaduan;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasTable('kehadiran_pengaduan')) {
            Schema::create('kehadiran_pengaduan', function (Blueprint $table) {
                $table->increments('id');
                $table->configId();
                $table->dateTime('waktu');
                $table->tinyInteger('status')->default(0);
                $table->mediumText('keterangan')->nullable();
                $table->unsignedInteger('id_penduduk')->nullable();
                $table->unsignedInteger('id_pamong')->nullable();

                $table->index('id_penduduk', 'kehadiran_pengaduan_penduduk_fk');
                $table->foreign('id_penduduk', 'kehadiran_pengaduan_penduduk_fk')
                    ->references('id')->on('tweb_penduduk')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');

                $table->index('id_pamong', 'kehadiran_pengaduan_pamong_fk');
                $table->foreign('id_pamong', 'kehadiran_pengaduan_pamong_fk')
                    ->references('pamong_id')->on('tweb_desa_pamong')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExistsDBGabungan('kehadiran_pengaduan', function () {
            KehadiranPengaduan::withoutConfigId(identitas('id'))->delete();
        });
    }
};
