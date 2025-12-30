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
        Schema::create('area', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('config_id')->index('area_config_fk');
            $table->string('nama', 50);
            $table->text('path')->nullable();
            $table->integer('enabled')->default(1);
            $table->integer('ref_polygon');
            $table->string('foto', 100)->nullable();
            $table->integer('id_cluster')->nullable()->index('area_cluster_fk');
            $table->text('desk');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('area');
    }
};
