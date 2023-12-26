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
        Schema::create('log_login', function (Blueprint $table) {
            $table->char('uuid', 36)->primary();
            $table->integer('config_id')->index('log_login_config_id_foreign');
            $table->string('username');
            $table->string('ip_address');
            $table->string('user_agent');
            $table->string('referer');
            $table->string('lainnya')->nullable();
            $table->timestamps();

            $table->unique(['uuid', 'config_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('log_login');
    }
};
