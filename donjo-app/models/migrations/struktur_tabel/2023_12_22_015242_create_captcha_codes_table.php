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
        Schema::create('captcha_codes', function (Blueprint $table) {
            $table->string('id', 40);
            $table->string('namespace', 32);
            $table->string('code', 32);
            $table->string('code_display', 32);
            $table->integer('created')->index('created');
            $table->binary('audio_data')->nullable();

            $table->primary(['id', 'namespace']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('captcha_codes');
    }
};
