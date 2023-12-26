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
        Schema::create('tweb_status_ktp', function (Blueprint $table) {
            $table->tinyInteger('id', true);
            $table->string('nama', 50);
            $table->tinyInteger('ktp_el');
            $table->string('status_rekam', 50);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tweb_status_ktp');
    }
};
