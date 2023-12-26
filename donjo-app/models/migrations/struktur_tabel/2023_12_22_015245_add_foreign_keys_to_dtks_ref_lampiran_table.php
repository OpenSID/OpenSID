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
        Schema::table('dtks_ref_lampiran', function (Blueprint $table) {
            $table->foreign(['id_dtks'], 'FK_ref_lampiran_dtks')->references(['id'])->on('dtks')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['id_lampiran'], 'FK_lampiran_dtks')->references(['id'])->on('dtks_lampiran')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dtks_ref_lampiran', function (Blueprint $table) {
            $table->dropForeign('FK_ref_lampiran_dtks');
            $table->dropForeign('FK_lampiran_dtks');
        });
    }
};
