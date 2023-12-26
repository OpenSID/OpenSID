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
        Schema::create('sasaran_paud', function (Blueprint $table) {
            $table->increments('id_sasaran_paud');
            $table->integer('config_id')->nullable()->index('sasaran_paud_config_fk');
            $table->integer('posyandu_id');
            $table->integer('kia_id');
            $table->boolean('kategori_usia');
            $table->boolean('januari');
            $table->boolean('februari');
            $table->boolean('maret');
            $table->boolean('april');
            $table->boolean('mei');
            $table->boolean('juni');
            $table->boolean('juli');
            $table->boolean('agustus');
            $table->boolean('september');
            $table->boolean('oktober');
            $table->boolean('november');
            $table->boolean('desember');
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->integer('created_by')->nullable();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->nullable()->useCurrent();
            $table->integer('updated_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sasaran_paud');
    }
};
