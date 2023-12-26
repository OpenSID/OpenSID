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
        Schema::table('kelompok', function (Blueprint $table) {
            $table->foreign(['id_master'], 'kelompok_kelompok_master_fk')->references(['id'])->on('kelompok_master')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['config_id'], 'kelompok_config_fk')->references(['id'])->on('config')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('kelompok', function (Blueprint $table) {
            $table->dropForeign('kelompok_kelompok_master_fk');
            $table->dropForeign('kelompok_config_fk');
        });
    }
};
