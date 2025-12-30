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
        Schema::create('pendapat', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('config_id')->index('pendapat_config_fk');
            $table->text('pengguna');
            $table->timestamp('tanggal')->useCurrent();
            $table->integer('pilihan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pendapat');
    }
};
