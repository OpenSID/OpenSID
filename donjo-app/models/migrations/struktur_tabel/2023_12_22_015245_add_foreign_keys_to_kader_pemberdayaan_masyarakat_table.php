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
        Schema::table('kader_pemberdayaan_masyarakat', function (Blueprint $table) {
            $table->foreign(['config_id'], 'kader_pemberdayaan_masyarakat_config_fk')->references(['id'])->on('config')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('kader_pemberdayaan_masyarakat', function (Blueprint $table) {
            $table->dropForeign('kader_pemberdayaan_masyarakat_config_fk');
        });
    }
};
