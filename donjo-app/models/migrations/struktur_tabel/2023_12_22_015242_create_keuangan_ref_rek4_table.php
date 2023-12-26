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
        Schema::create('keuangan_ref_rek4', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('config_id')->nullable()->index('keuangan_ref_rek4_config_fk');
            $table->integer('id_keuangan_master')->index('id_keuangan_ref_rek4_master_fk');
            $table->string('Jenis', 100);
            $table->string('Obyek', 100);
            $table->string('Nama_Obyek', 100);
            $table->string('Peraturan', 250);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('keuangan_ref_rek4');
    }
};
