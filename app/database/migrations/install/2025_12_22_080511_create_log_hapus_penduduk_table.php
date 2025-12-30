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
        Schema::create('log_hapus_penduduk', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('config_id')->index('log_hapus_penduduk_config_fk');
            $table->integer('id_pend')->nullable()->index('log_hapus_penduduk_pend_fk');
            $table->decimal('nik', 16, 0);
            $table->string('foto', 100)->nullable();
            $table->string('deleted_by', 100)->nullable();
            $table->softDeletes()->nullable(false)->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_hapus_penduduk');
    }
};
