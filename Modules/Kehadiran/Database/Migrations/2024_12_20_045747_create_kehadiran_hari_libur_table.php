<?php

use Illuminate\Support\Facades\Schema;
use Modules\Kehadiran\Models\HariLibur;
use Modules\Kehadiran\Models\Kehadiran;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasTable('kehadiran_hari_libur')) {
            Schema::create('kehadiran_hari_libur', function (Blueprint $table) {
                $table->increments('id');
                $table->configId();
                $table->date('tanggal');
                $table->mediumText('keterangan')->nullable();
                $table->unique(['config_id', 'tanggal'], 'tanggal_config');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExistsDBGabungan('kehadiran_jam_kerja', function () {
            HariLibur::withoutConfigId(identitas('id'))->delete();
        });
    }
};
