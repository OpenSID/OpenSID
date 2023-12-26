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
        Schema::create('keuangan_ref_korolari', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('config_id')->nullable()->index('keuangan_ref_korolari_config_fk');
            $table->integer('id_keuangan_master')->index('id_keuangan_ref_korolari_master_fk');
            $table->string('Kd_Rincian', 100);
            $table->string('Kd_RekDB', 100);
            $table->string('Kd_RekKD', 250);
            $table->string('Jenis', 30)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('keuangan_ref_korolari');
    }
};
