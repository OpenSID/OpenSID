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
        Schema::table('log_penduduk', function (Blueprint $table) {
            $table->foreign(['ref_pindah'], 'id_ref_pindah')->references(['id'])->on('ref_pindah')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['id_pend'], 'fk_tweb_penduduk')->references(['id'])->on('tweb_penduduk')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['config_id'], 'log_penduduk_config_fk')->references(['id'])->on('config')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('log_penduduk', function (Blueprint $table) {
            $table->dropForeign('id_ref_pindah');
            $table->dropForeign('fk_tweb_penduduk');
            $table->dropForeign('log_penduduk_config_fk');
        });
    }
};
