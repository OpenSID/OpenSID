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
        Schema::create('suplemen', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('config_id')->nullable();
            $table->string('nama', 100)->nullable();
            $table->string('slug')->nullable();
            $table->tinyInteger('sasaran')->nullable();
            $table->string('keterangan', 300)->nullable();
            $table->tinyInteger('status')->default(1)->comment('1 = Aktif, 0 = Nonaktif');
            $table->enum('sumber', ['OpenSID', 'OpenKab'])->default('OpenSID');
            $table->longText('form_isian')->nullable()->comment('Menyimpan data formulir dinamis tambahan sebagai JSON atau teks');

            $table->unique(['config_id', 'slug'], 'slug_config');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suplemen');
    }
};
