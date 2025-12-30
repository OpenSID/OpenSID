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
        Schema::create('log_perubahan_penduduk', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('config_id')->index('log_perubahan_penduduk_config_fk');
            $table->integer('id_pend')->nullable()->index('log_perubahan_penduduk_pend_fk');
            $table->integer('id_cluster')->nullable()->index('log_perubahan_penduduk_cluster_fk');
            $table->timestamp('tanggal')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_perubahan_penduduk');
    }
};
