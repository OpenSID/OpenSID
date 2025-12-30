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
        Schema::create('suplemen_terdata', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('config_id')->index('suplemen_terdata_config_fk');
            $table->integer('id_suplemen')->nullable()->index('id_suplemen');
            $table->string('id_terdata', 20)->nullable();
            $table->integer('keluarga_id')->nullable()->index('suplemen_terdata_keluarga_fk');
            $table->integer('penduduk_id')->nullable()->index('suplemen_terdata_penduduk_fk');
            $table->tinyInteger('sasaran')->nullable();
            $table->string('keterangan', 100)->nullable();
            $table->longText('data_form_isian')->nullable()->comment('Menyimpan data dinamis sebagai JSON atau teks');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suplemen_terdata');
    }
};
