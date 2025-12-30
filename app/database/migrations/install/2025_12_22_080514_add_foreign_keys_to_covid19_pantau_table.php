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
        Schema::table('covid19_pantau', function (Blueprint $table) {
            $table->foreign(['config_id'], 'covid19_pantau_config_fk')->references(['id'])->on('config')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['id_pemudik'], 'fk_pantau_pemudik')->references(['id'])->on('covid19_pemudik')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('covid19_pantau', function (Blueprint $table) {
            $table->dropForeign('covid19_pantau_config_fk');
            $table->dropForeign('fk_pantau_pemudik');
        });
    }
};
