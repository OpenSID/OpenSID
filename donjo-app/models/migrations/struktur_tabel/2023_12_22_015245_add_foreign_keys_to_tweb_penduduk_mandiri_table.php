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
        Schema::table('tweb_penduduk_mandiri', function (Blueprint $table) {
            $table->foreign(['config_id'], 'tweb_penduduk_mandiri_config_fk')->references(['id'])->on('config')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['id_pend'], 'id_pend_fk')->references(['id'])->on('tweb_penduduk')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tweb_penduduk_mandiri', function (Blueprint $table) {
            $table->dropForeign('tweb_penduduk_mandiri_config_fk');
            $table->dropForeign('id_pend_fk');
        });
    }
};
