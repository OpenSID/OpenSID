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
        Schema::table('analisis_respon_bukti', function (Blueprint $table) {
            $table->foreign(['config_id'], 'analisis_respon_bukti_config_fk')->references(['id'])->on('config')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('analisis_respon_bukti', function (Blueprint $table) {
            $table->dropForeign('analisis_respon_bukti_config_fk');
        });
    }
};
