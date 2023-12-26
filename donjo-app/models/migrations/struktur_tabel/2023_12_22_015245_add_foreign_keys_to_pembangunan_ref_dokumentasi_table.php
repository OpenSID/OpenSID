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
        Schema::table('pembangunan_ref_dokumentasi', function (Blueprint $table) {
            $table->foreign(['config_id'], 'pembangunan_ref_dokumentasi_config_fk')->references(['id'])->on('config')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pembangunan_ref_dokumentasi', function (Blueprint $table) {
            $table->dropForeign('pembangunan_ref_dokumentasi_config_fk');
        });
    }
};
