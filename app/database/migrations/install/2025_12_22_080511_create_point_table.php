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
        Schema::create('point', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('config_id')->nullable()->index('point_config_fk');
            $table->string('nama', 50);
            $table->string('simbol', 50)->nullable();
            $table->integer('tipe')->nullable()->default(0);
            $table->integer('parrent')->default(1)->index('parrent');
            $table->integer('enabled')->default(1);
            $table->enum('sumber', ['OpenSID', 'OpenKab'])->default('OpenSID');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('point');
    }
};
