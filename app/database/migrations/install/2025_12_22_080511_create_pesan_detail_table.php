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
        Schema::create('pesan_detail', function (Blueprint $table) {
            $table->unsignedInteger('id')->primary();
            $table->integer('config_id')->index('pesan_detail_config_fk');
            $table->integer('pesan_id')->nullable()->index('pesan_detail_pesan_fk');
            $table->text('text');
            $table->string('pengirim', 100)->nullable();
            $table->string('nama_pengirim', 100)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesan_detail');
    }
};
