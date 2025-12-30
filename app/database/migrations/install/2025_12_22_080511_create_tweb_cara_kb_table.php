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
        Schema::create('tweb_cara_kb', function (Blueprint $table) {
            $table->tinyInteger('id', true);
            $table->string('nama', 50);
            $table->tinyInteger('sex')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tweb_cara_kb');
    }
};
