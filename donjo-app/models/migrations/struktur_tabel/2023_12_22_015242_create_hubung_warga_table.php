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
        Schema::create('hubung_warga', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('config_id')->nullable()->index('hubung_warga_config_fk');
            $table->integer('id_grup')->index('hubung_warga_id_grup_fk');
            $table->string('subjek', 100);
            $table->text('isi');
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
        Schema::dropIfExists('hubung_warga');
    }
};
