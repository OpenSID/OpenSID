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
        Schema::create('log_perubahan_penduduk', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('config_id')->nullable()->index('log_perubahan_penduduk_config_fk');
            $table->integer('id_pend');
            $table->string('id_cluster', 200);
            $table->timestamp('tanggal')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('log_perubahan_penduduk');
    }
};
