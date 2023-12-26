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
        Schema::table('kehadiran_jam_kerja', function (Blueprint $table) {
            $table->foreign(['config_id'], 'kehadiran_jam_kerja_config_fk')->references(['id'])->on('config')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('kehadiran_jam_kerja', function (Blueprint $table) {
            $table->dropForeign('kehadiran_jam_kerja_config_fk');
        });
    }
};
