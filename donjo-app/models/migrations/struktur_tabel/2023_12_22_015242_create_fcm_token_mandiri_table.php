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
        Schema::create('fcm_token_mandiri', function (Blueprint $table) {
            $table->integer('id_user_mandiri')->comment('id user mandiri');
            $table->mediumInteger('config_id');
            $table->string('device')->unique()->comment('id device dari android pemohon');
            $table->longText('token')->comment('token yang didapat dari FCM');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fcm_token_mandiri');
    }
};
