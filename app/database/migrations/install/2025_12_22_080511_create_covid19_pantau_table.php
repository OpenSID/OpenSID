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
        Schema::create('covid19_pantau', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('config_id')->index('covid19_pantau_config_fk');
            $table->integer('id_pemudik')->nullable()->index('fk_pantau_pemudik');
            $table->dateTime('tanggal_jam')->nullable();
            $table->decimal('suhu_tubuh', 4)->nullable();
            $table->string('batuk', 20)->nullable();
            $table->string('flu', 20)->nullable();
            $table->string('sesak_nafas', 20)->nullable();
            $table->string('keluhan_lain')->nullable();
            $table->string('status_covid', 50)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('covid19_pantau');
    }
};
