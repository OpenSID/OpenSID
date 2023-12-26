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
        Schema::create('komentar', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('config_id')->nullable()->index('komentar_config_fk');
            $table->integer('id_artikel');
            $table->string('owner', 50);
            $table->string('email', 50)->nullable();
            $table->tinyText('subjek')->nullable();
            $table->text('komentar');
            $table->timestamp('tgl_upload')->useCurrent();
            $table->boolean('status')->nullable();
            $table->boolean('tipe')->nullable();
            $table->string('no_hp', 15)->nullable();
            $table->timestamp('updated_at')->useCurrent();
            $table->boolean('is_archived')->nullable()->default(false);
            $table->text('permohonan')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('komentar');
    }
};
