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
        Schema::create('sys_traffic', function (Blueprint $table) {
            $table->date('Tanggal');
            $table->integer('config_id')->nullable();
            $table->longText('ipAddress');
            $table->bigInteger('Jumlah');

            $table->unique(['config_id', 'Tanggal'], 'config_idTanggal');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sys_traffic');
    }
};
