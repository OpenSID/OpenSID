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
        Schema::create('security_baselines', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('config_id')->nullable();
            $table->timestamp('generated_at');
            $table->string('version', 10)->default('1.0');
            $table->string('target_directory');
            $table->json('excluded_dirs')->nullable();
            $table->json('statistics');
            $table->longText('files');
            $table->timestamps();

            $table->index(['config_id', 'generated_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('security_baselines');
    }
};
