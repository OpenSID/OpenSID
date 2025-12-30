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
        Schema::create('pesan', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('config_id')->index('pesan_config_fk');
            $table->string('judul')->nullable();
            $table->string('jenis', 50);
            $table->integer('sudah_dibaca')->default(1);
            $table->integer('diarsipkan')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesan');
    }
};
