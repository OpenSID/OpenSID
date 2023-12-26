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
        Schema::create('user', function (Blueprint $table) {
            $table->mediumIncrements('id');
            $table->integer('config_id')->nullable();
            $table->string('username', 100)->nullable();
            $table->string('password', 100);
            $table->integer('id_grup');
            $table->integer('pamong_id')->nullable();
            $table->string('email', 100)->nullable();
            $table->dateTime('last_login')->nullable();
            $table->dateTime('email_verified_at')->nullable();
            $table->boolean('active')->unsigned()->nullable()->default(false);
            $table->string('nama', 50)->nullable();
            $table->string('id_telegram', 100);
            $table->string('token', 100)->nullable();
            $table->dateTime('token_exp')->nullable();
            $table->dateTime('telegram_verified_at')->nullable();
            $table->boolean('notif_telegram')->default(false);
            $table->string('company', 100)->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('foto', 100)->nullable()->default('kuser.png');
            $table->string('session', 40);

            $table->unique(['config_id', 'username'], 'username_config');
            $table->unique(['config_id', 'pamong_id'], 'pamong_id_config');
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
        Schema::dropIfExists('user');
    }
};
