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
        Schema::create('ref_persil_kelas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('tipe', 20);
            $table->string('kode', 20);
            $table->text('ndesc')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ref_persil_kelas');
    }
};
