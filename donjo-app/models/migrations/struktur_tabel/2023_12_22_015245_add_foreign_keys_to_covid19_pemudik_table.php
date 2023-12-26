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
        Schema::table('covid19_pemudik', function (Blueprint $table) {
            $table->foreign(['id_terdata'], 'fk_pemudik_penduduk')->references(['id'])->on('tweb_penduduk')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['config_id'], 'covid19_pemudik_config_fk')->references(['id'])->on('config')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('covid19_pemudik', function (Blueprint $table) {
            $table->dropForeign('fk_pemudik_penduduk');
            $table->dropForeign('covid19_pemudik_config_fk');
        });
    }
};
