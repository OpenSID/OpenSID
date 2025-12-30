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
        Schema::table('otp_token', function (Blueprint $table) {
            $table->foreign(['config_id'])->references(['id'])->on('config')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['user_id'])->references(['id'])->on('user')->onUpdate('restrict')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('otp_token', function (Blueprint $table) {
            $table->dropForeign('otp_token_config_id_foreign');
            $table->dropForeign('otp_token_user_id_foreign');
        });
    }
};
