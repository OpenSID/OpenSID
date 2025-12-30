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
        Schema::create('fcm_token', function (Blueprint $table) {
            $table->integer('id_user')->nullable()->index('fcm_token_dd_user_fk');
            $table->integer('config_id')->index('fcm_token_config_fk');
            $table->string('device')->unique();
            $table->longText('token');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fcm_token');
    }
};
