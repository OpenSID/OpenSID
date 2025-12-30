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
        Schema::table('covid19_vaksin', function (Blueprint $table) {
            $table->foreign(['config_id'], 'covid19_vaksin_config_fk')->references(['id'])->on('config')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('covid19_vaksin', function (Blueprint $table) {
            $table->dropForeign('covid19_vaksin_config_fk');
        });
    }
};
