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
        Schema::table('cdesa_penduduk', function (Blueprint $table) {
            $table->foreign(['id_cdesa'], 'cdesa_penduduk_fk')->references(['id'])->on('cdesa')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['config_id'], 'cdesa_penduduk_config_fk')->references(['id'])->on('config')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cdesa_penduduk', function (Blueprint $table) {
            $table->dropForeign('cdesa_penduduk_fk');
            $table->dropForeign('cdesa_penduduk_config_fk');
        });
    }
};
