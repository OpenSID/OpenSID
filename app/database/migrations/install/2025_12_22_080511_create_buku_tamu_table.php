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
        Schema::create('buku_tamu', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('config_id')->index('buku_tamu_config_fk');
            $table->string('nama', 50);
            $table->string('telepon', 20);
            $table->string('instansi', 100);
            $table->boolean('jenis_kelamin')->default(true);
            $table->text('alamat')->nullable();
            $table->string('bidang', 100)->nullable();
            $table->string('keperluan', 100)->nullable();
            $table->tinyInteger('status')->default(0)->comment('0: Baru, 1: Selesai');
            $table->string('foto', 50)->nullable();
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->nullable()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buku_tamu');
    }
};
