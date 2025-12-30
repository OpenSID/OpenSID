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
        Schema::create('security_reports', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('config_id')->nullable();
            $table->string('filename');
            $table->enum('type', ['integrity', 'scan']);
            $table->longText('data');
            $table->timestamps();

            $table->index(['config_id', 'type', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('security_reports');
    }
};
