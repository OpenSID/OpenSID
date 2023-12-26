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
        Schema::create('widget', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('config_id')->nullable()->index('widget_config_fk');
            $table->text('isi')->nullable();
            $table->integer('enabled')->nullable();
            $table->string('judul', 100)->nullable();
            $table->tinyInteger('jenis_widget')->default(3);
            $table->integer('urut')->nullable();
            $table->string('form_admin', 100)->nullable();
            $table->text('setting')->nullable();
            $table->string('foto')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('widget');
    }
};
