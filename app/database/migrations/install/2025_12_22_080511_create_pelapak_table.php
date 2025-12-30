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
        Schema::create('pelapak', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('config_id')->index('pelapak_config_fk');
            $table->integer('id_pend')->nullable()->index('pelapak_pend_fk');
            $table->string('telepon', 20)->nullable();
            $table->string('lat', 20)->nullable();
            $table->string('lng', 20)->nullable();
            $table->tinyInteger('zoom')->default(10);
            $table->boolean('status')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pelapak');
    }
};
