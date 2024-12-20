<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Kehadiran\Models\AlasanKeluar;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('kehadiran_alasan_keluar')) {
            Schema::create('kehadiran_alasan_keluar', static function (Blueprint $table) {
                $table->increments('id');
                $table->configId();
                $table->string('alasan', 255);
                $table->mediumText('keterangan')->nullable();
                $table->timesWithUserstamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExistsDBGabungan('kehadiran_alasan_keluar', static function () {
            AlasanKeluar::withoutConfigId(identitas('id'))->delete();
        });
    }
};
