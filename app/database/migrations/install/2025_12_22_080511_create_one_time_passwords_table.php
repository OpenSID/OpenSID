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
        Schema::create('one_time_passwords', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('config_id')->nullable()->index('one_time_passwords_config_id_foreign');
            $table->string('password');
            $table->text('origin_properties')->nullable();
            $table->dateTime('expires_at');
            $table->string('authenticatable_type');
            $table->unsignedBigInteger('authenticatable_id');
            $table->timestamps();

            $table->index(['authenticatable_type', 'authenticatable_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('one_time_passwords');
    }
};
