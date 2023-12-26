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
        Schema::table('covid19_pantau', function (Blueprint $table) {
            $table->foreign(['id_pemudik'], 'fk_pantau_pemudik')->references(['id'])->on('covid19_pemudik')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['config_id'], 'covid19_pantau_config_fk')->references(['id'])->on('config')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('covid19_pantau', function (Blueprint $table) {
            $table->dropForeign('fk_pantau_pemudik');
            $table->dropForeign('covid19_pantau_config_fk');
        });
    }
};
