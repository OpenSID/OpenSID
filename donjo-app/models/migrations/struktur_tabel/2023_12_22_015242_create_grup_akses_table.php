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
        Schema::create('grup_akses', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('config_id')->nullable()->index('grup_akses_config_fk');
            $table->integer('id_grup')->index('id_grup');
            $table->integer('id_modul')->index('id_modul');
            $table->tinyInteger('akses')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('grup_akses');
    }
};
