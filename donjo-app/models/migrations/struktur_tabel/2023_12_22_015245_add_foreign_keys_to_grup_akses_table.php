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
        Schema::table('grup_akses', function (Blueprint $table) {
            $table->foreign(['id_modul'], 'fk_id_modul')->references(['id'])->on('setting_modul')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['id_grup'], 'fk_id_grup')->references(['id'])->on('user_grup')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['config_id'], 'grup_akses_config_fk')->references(['id'])->on('config')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('grup_akses', function (Blueprint $table) {
            $table->dropForeign('fk_id_modul');
            $table->dropForeign('fk_id_grup');
            $table->dropForeign('grup_akses_config_fk');
        });
    }
};
