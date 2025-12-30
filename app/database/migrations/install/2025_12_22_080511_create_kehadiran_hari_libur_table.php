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
        Schema::create('kehadiran_hari_libur', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('config_id');
            $table->date('tanggal');
            $table->text('keterangan')->nullable();

            $table->unique(['config_id', 'tanggal'], 'tanggal_config');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kehadiran_hari_libur');
    }
};
