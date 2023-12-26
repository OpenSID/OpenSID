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
        Schema::table('keuangan_manual_rinci', function (Blueprint $table) {
            $table->foreign(['config_id'], 'keuangan_manual_rinci_config_fk')->references(['id'])->on('config')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('keuangan_manual_rinci', function (Blueprint $table) {
            $table->dropForeign('keuangan_manual_rinci_config_fk');
        });
    }
};
