<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ref_sinkronisasi', function (Blueprint $table) {
            $table->string('tabel', 100)->primary();
            $table->string('server')->nullable();
            $table->tinyInteger('jenis_update')->nullable();
            $table->string('tabel_hapus', 100)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ref_sinkronisasi');
    }
};
