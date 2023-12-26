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
        Schema::table('alias_kodeisian', function (Blueprint $table) {
            $table->foreign(['config_id'])->references(['id'])->on('config')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('alias_kodeisian', function (Blueprint $table) {
            $table->dropForeign('alias_kodeisian_config_id_foreign');
        });
    }
};
