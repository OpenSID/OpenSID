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
        Schema::table('suplemen_terdata', function (Blueprint $table) {
            $table->foreign(['id_suplemen'], 'suplemen_terdata_ibfk_1')->references(['id'])->on('suplemen')->onDelete('CASCADE');
            $table->foreign(['config_id'], 'suplemen_terdata_config_fk')->references(['id'])->on('config')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('suplemen_terdata', function (Blueprint $table) {
            $table->dropForeign('suplemen_terdata_ibfk_1');
            $table->dropForeign('suplemen_terdata_config_fk');
        });
    }
};
