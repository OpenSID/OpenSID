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
        Schema::create('keuangan_manual_ref_rek2', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('Akun', 100);
            $table->string('Kelompok', 100);
            $table->string('Nama_Kelompok', 100);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('keuangan_manual_ref_rek2');
    }
};
