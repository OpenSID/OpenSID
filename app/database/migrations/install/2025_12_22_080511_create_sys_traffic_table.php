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
        Schema::create('sys_traffic', function (Blueprint $table) {
            $table->date('Tanggal');
            $table->integer('config_id');
            $table->longText('ipAddress');
            $table->bigInteger('Jumlah');

            $table->unique(['config_id', 'Tanggal'], 'config_idtanggal');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sys_traffic');
    }
};
