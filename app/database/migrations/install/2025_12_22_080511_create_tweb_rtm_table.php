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
        Schema::create('tweb_rtm', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('config_id');
            $table->integer('nik_kepala')->nullable()->index('tweb_rtm_kepala_fk');
            $table->string('no_kk', 30)->index('idx_no_kk');
            $table->timestamp('tgl_daftar')->useCurrent();
            $table->integer('kelas_sosial')->nullable();
            $table->string('bdt', 16)->nullable();
            $table->boolean('terdaftar_dtks')->default(false);

            $table->unique(['config_id', 'no_kk'], 'no_kk_config');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tweb_rtm');
    }
};
