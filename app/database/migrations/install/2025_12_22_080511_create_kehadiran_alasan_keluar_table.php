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
        Schema::create('kehadiran_alasan_keluar', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('config_id')->index('kehadiran_alasan_keluar_config_fk');
            $table->string('alasan');
            $table->text('keterangan')->nullable();
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->integer('created_by')->nullable();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->nullable()->useCurrent();
            $table->integer('updated_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kehadiran_alasan_keluar');
    }
};
