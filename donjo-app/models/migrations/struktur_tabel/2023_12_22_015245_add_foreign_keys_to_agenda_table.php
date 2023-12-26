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
        Schema::table('agenda', function (Blueprint $table) {
            $table->foreign(['id_artikel'], 'id_artikel_fk')->references(['id'])->on('artikel')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['config_id'], 'agenda_config_fk')->references(['id'])->on('config')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('agenda', function (Blueprint $table) {
            $table->dropForeign('id_artikel_fk');
            $table->dropForeign('agenda_config_fk');
        });
    }
};
