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
        Schema::table('dtks_pengaturan_program', function (Blueprint $table) {
            $table->foreign(['config_id'], 'dtks_pengaturan_program_config_fk')->references(['id'])->on('config')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['id_bantuan'], 'FK_dtks_p_program')->references(['id'])->on('program')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dtks_pengaturan_program', function (Blueprint $table) {
            $table->dropForeign('dtks_pengaturan_program_config_fk');
            $table->dropForeign('FK_dtks_p_program');
        });
    }
};
