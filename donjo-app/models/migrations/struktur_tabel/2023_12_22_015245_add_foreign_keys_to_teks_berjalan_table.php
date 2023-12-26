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
        Schema::table('teks_berjalan', function (Blueprint $table) {
            $table->foreign(['config_id'], 'teks_berjalan_config_fk')->references(['id'])->on('config')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('teks_berjalan', function (Blueprint $table) {
            $table->dropForeign('teks_berjalan_config_fk');
        });
    }
};
