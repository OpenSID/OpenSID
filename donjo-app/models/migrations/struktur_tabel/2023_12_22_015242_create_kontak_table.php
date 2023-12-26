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
        Schema::create('kontak', function (Blueprint $table) {
            $table->integer('id_kontak', true);
            $table->integer('config_id')->nullable();
            $table->string('nama', 100);
            $table->string('telepon', 20)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('telegram', 50)->nullable();
            $table->string('hubung_warga', 50)->default('Telegram');
            $table->text('keterangan')->nullable();
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->nullable()->useCurrent();

            $table->unique(['config_id', 'telepon'], 'telepon_config');
            $table->unique(['config_id', 'telegram'], 'telegram_config');
            $table->unique(['config_id', 'email'], 'email_config');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kontak');
    }
};
