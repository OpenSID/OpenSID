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
        Schema::create('klasifikasi_surat', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('config_id')->index('klasifikasi_surat_config_fk');
            $table->string('kode', 50);
            $table->text('nama');
            $table->mediumText('uraian');
            $table->integer('enabled')->default(1);

            $table->unique(['config_id', 'kode'], 'config_idkode');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('klasifikasi_surat');
    }
};
