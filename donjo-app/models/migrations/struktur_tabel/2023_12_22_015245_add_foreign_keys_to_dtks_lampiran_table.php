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
        Schema::table('dtks_lampiran', function (Blueprint $table) {
            $table->foreign(['config_id'], 'dtks_lampiran_config_fk')->references(['id'])->on('config')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['id_rtm'], 'FK_dtks_lampiran_rtm')->references(['id'])->on('tweb_rtm')->onUpdate('CASCADE')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dtks_lampiran', function (Blueprint $table) {
            $table->dropForeign('dtks_lampiran_config_fk');
            $table->dropForeign('FK_dtks_lampiran_rtm');
        });
    }
};
