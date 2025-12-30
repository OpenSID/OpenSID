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
        Schema::create('tweb_status_ktp', function (Blueprint $table) {
            $table->tinyInteger('id', true);
            $table->string('nama', 50);
            $table->tinyInteger('ktp_el');
            $table->string('status_rekam', 50);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tweb_status_ktp');
    }
};
