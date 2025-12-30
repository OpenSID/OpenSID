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
        Schema::create('tweb_keluarga', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('config_id');
            $table->string('no_kk', 16)->nullable();
            $table->integer('nik_kepala')->nullable()->index('nik_kepala');
            $table->timestamp('tgl_daftar')->nullable()->useCurrent();
            $table->integer('kelas_sosial')->nullable();
            $table->dateTime('tgl_cetak_kk')->nullable();
            $table->string('alamat', 200)->nullable();
            $table->integer('id_cluster')->nullable()->index('tweb_keluarga_cluster_fk');
            $table->timestamp('updated_at')->useCurrent();
            $table->integer('updated_by')->nullable();

            $table->unique(['config_id', 'no_kk'], 'no_kk_config');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tweb_keluarga');
    }
};
