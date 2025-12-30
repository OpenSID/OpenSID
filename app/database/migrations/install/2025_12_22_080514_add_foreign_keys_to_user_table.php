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
        Schema::table('user', function (Blueprint $table) {
            $table->foreign(['config_id'], 'user_config_fk')->references(['id'])->on('config')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['id_grup'], 'user_grup_fk')->references(['id'])->on('user_grup')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['pamong_id'], 'user_pamong_fk')->references(['pamong_id'])->on('tweb_desa_pamong')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user', function (Blueprint $table) {
            $table->dropForeign('user_config_fk');
            $table->dropForeign('user_grup_fk');
            $table->dropForeign('user_pamong_fk');
        });
    }
};
