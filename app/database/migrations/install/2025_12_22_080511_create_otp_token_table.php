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
        Schema::create('otp_token', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('config_id')->nullable()->index('otp_token_config_id_foreign');
            $table->integer('user_id');
            $table->string('token_hash');
            $table->enum('channel', ['email', 'telegram']);
            $table->string('identifier');
            $table->enum('purpose', ['activation', 'login'])->default('login');
            $table->timestamp('expires_at');
            $table->integer('attempts')->default(0);

            $table->index(['user_id', 'expires_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('otp_token');
    }
};
