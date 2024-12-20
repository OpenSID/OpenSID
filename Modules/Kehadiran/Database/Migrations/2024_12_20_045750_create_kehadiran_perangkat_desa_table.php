<?php

use Illuminate\Support\Facades\Schema;
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
        if (! Schema::hasTable('kehadiran_perangkat_desa')) {
            Schema::create('kehadiran_perangkat_desa', function (Blueprint $table) {
                $table->increments('id');
                $table->configId();
                $table->date('tanggal')->nullable();
                $table->unsignedInteger('pamong_id')->nullable();
                $table->time('jam_masuk')->nullable();
                $table->time('jam_keluar')->nullable();
                $table->string('status_kehadiran', 255)->nullable();

                $table->index('pamong_id', 'kehadiran_perangkat_desa_pamong_fk');
                $table->foreign('pamong_id', 'kehadiran_perangkat_desa_pamong_fk')
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
        // Schema::dropIfExistsDBGabungan('kehadiran_perangkat_desa', function () {
        //     Kehadiran::withoutConfigId(identitas('id'))->delete();
        // });
    }
};
