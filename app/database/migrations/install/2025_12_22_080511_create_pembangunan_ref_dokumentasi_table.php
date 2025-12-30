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
        Schema::create('pembangunan_ref_dokumentasi', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('config_id')->index('pembangunan_ref_dokumentasi_config_fk');
            $table->integer('id_pembangunan')->nullable()->index('id_pembangunan');
            $table->string('gambar')->nullable();
            $table->string('persentase')->nullable();
            $table->string('keterangan')->nullable();
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembangunan_ref_dokumentasi');
    }
};
