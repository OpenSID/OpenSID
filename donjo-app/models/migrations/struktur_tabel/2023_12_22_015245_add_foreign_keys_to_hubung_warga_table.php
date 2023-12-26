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
        Schema::table('hubung_warga', function (Blueprint $table) {
            $table->foreign(['id_grup'], 'hubung_warga_id_grup_fk')->references(['id_grup'])->on('kontak_grup')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['config_id'], 'hubung_warga_config_fk')->references(['id'])->on('config')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hubung_warga', function (Blueprint $table) {
            $table->dropForeign('hubung_warga_id_grup_fk');
            $table->dropForeign('hubung_warga_config_fk');
        });
    }
};
