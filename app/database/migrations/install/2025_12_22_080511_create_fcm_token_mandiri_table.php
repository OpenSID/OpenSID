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
        Schema::create('fcm_token_mandiri', function (Blueprint $table) {
            $table->integer('id_user_mandiri')->nullable()->index('fcm_token_mandiri_user_mandiri_fk');
            $table->integer('config_id')->index('fcm_token_mandiri_config_fk');
            $table->string('device')->unique()->comment('id device dari android pemohon');
            $table->longText('token')->comment('token yang didapat dari FCM');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fcm_token_mandiri');
    }
};
