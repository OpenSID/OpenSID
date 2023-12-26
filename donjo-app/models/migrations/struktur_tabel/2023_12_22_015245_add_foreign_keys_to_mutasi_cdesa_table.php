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
        Schema::table('mutasi_cdesa', function (Blueprint $table) {
            $table->foreign(['config_id'], 'mutasi_cdesa_config_fk')->references(['id'])->on('config')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['id_cdesa_masuk'], 'cdesa_mutasi_fk')->references(['id'])->on('cdesa')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mutasi_cdesa', function (Blueprint $table) {
            $table->dropForeign('mutasi_cdesa_config_fk');
            $table->dropForeign('cdesa_mutasi_fk');
        });
    }
};
