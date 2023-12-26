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
        Schema::table('mutasi_inventaris_peralatan', function (Blueprint $table) {
            $table->foreign(['config_id'], 'mutasi_inventaris_peralatan_config_fk')->references(['id'])->on('config')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['id_inventaris_peralatan'], 'FK_mutasi_inventaris_peralatan')->references(['id'])->on('inventaris_peralatan');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mutasi_inventaris_peralatan', function (Blueprint $table) {
            $table->dropForeign('mutasi_inventaris_peralatan_config_fk');
            $table->dropForeign('FK_mutasi_inventaris_peralatan');
        });
    }
};
