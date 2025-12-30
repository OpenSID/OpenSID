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
        Schema::create('tweb_aset', function (Blueprint $table) {
            $table->integer('id_aset')->primary();
            $table->string('golongan', 11);
            $table->string('bidang', 11);
            $table->string('kelompok', 11);
            $table->string('sub_kelompok', 11);
            $table->string('sub_sub_kelompok', 11);
            $table->string('nama');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tweb_aset');
    }
};
