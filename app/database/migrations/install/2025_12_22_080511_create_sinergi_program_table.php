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
        Schema::create('sinergi_program', function (Blueprint $table) {
            $table->char('uuid', 36)->primary();
            $table->integer('config_id');
            $table->string('judul', 100);
            $table->string('gambar', 100)->nullable();
            $table->string('tautan', 200);
            $table->integer('urut')->default(1);
            $table->tinyInteger('status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sinergi_program');
    }
};
