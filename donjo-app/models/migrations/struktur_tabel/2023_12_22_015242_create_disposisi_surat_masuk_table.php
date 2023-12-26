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
        Schema::create('disposisi_surat_masuk', function (Blueprint $table) {
            $table->integer('id_disposisi', true);
            $table->integer('config_id')->nullable()->index('disposisi_surat_masuk_config_fk');
            $table->integer('id_surat_masuk')->index('id_surat_fk');
            $table->integer('id_desa_pamong')->nullable()->index('desa_pamong_fk');
            $table->string('disposisi_ke', 50)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('disposisi_surat_masuk');
    }
};
