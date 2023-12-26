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
        Schema::table('log_keluarga', function (Blueprint $table) {
            $table->foreign(['id_log_penduduk'], 'log_penduduk_fk')->references(['id'])->on('log_penduduk')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['config_id'], 'log_keluarga_config_fk')->references(['id'])->on('config')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('log_keluarga', function (Blueprint $table) {
            $table->dropForeign('log_penduduk_fk');
            $table->dropForeign('log_keluarga_config_fk');
        });
    }
};
