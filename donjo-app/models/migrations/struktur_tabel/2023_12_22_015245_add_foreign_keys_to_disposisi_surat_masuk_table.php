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
        Schema::table('disposisi_surat_masuk', function (Blueprint $table) {
            $table->foreign(['config_id'], 'disposisi_surat_masuk_config_fk')->references(['id'])->on('config')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['id_desa_pamong'], 'desa_pamong_fk')->references(['pamong_id'])->on('tweb_desa_pamong')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['id_surat_masuk'], 'id_surat_fk')->references(['id'])->on('surat_masuk')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('disposisi_surat_masuk', function (Blueprint $table) {
            $table->dropForeign('disposisi_surat_masuk_config_fk');
            $table->dropForeign('desa_pamong_fk');
            $table->dropForeign('id_surat_fk');
        });
    }
};
