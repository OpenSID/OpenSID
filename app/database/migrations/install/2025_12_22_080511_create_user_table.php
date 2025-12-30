<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('config_id')->nullable();
            $table->string('username', 100)->nullable();
            $table->string('password', 100);
            $table->string('remember_token')->nullable();
            $table->integer('id_grup')->nullable()->index('user_grup_fk');
            $table->integer('pamong_id')->nullable()->index('user_pamong_fk');
            $table->string('email', 100)->nullable();
            $table->dateTime('last_login')->nullable();
            $table->dateTime('email_verified_at')->nullable();
            $table->boolean('active')->unsigned()->nullable()->default(false);
            $table->boolean('otp_enabled')->default(false);
            $table->enum('otp_channel', ['email', 'telegram', 'both'])->nullable();
            $table->string('otp_identifier')->nullable();
            $table->string('telegram_chat_id', 100)->nullable();
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
            $table->unsignedTinyInteger('batasi_wilayah')->default(0);
            $table->text('akses_wilayah')->nullable();
            $table->boolean('two_factor_enabled')->default(false);

            $table->unique(['config_id', 'email'], 'email_config');
            $table->unique(['config_id', 'pamong_id'], 'pamong_id_config');
            $table->unique(['config_id', 'username'], 'username_config');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user');
    }
};
