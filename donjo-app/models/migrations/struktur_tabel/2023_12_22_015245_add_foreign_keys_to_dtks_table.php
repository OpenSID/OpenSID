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
        Schema::table('dtks', function (Blueprint $table) {
            $table->foreign(['id_keluarga'], 'FK_kel_dtks')->references(['id'])->on('tweb_keluarga')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['id_rtm'], 'FK_dtks_rtm')->references(['id'])->on('tweb_rtm')->onUpdate('CASCADE')->onDelete('SET NULL');
            $table->foreign(['config_id'], 'dtks_config_fk')->references(['id'])->on('config')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dtks', function (Blueprint $table) {
            $table->dropForeign('FK_kel_dtks');
            $table->dropForeign('FK_dtks_rtm');
            $table->dropForeign('dtks_config_fk');
        });
    }
};
