<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\BukuTamu\Models\TamuModel;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('buku_tamu', function (Blueprint $table) {
            $table->id();
            $table->config();
            $table->string('nama', 50);
            $table->string('telepon', 20);
            $table->string('instansi', 100);
            $table->boolean('jenis_kelamin')->default(1);
            $table->mediumText('alamat')->nullable();
            $table->string('bidang', 100)->nullable();
            $table->string('keperluan', 100)->nullable();
            $table->string('foto', 50)->nullable();
            $table->timestamps();
        });

        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExistsDBGabungan('buku_tamu', function () {
            TamuModel::withoutConfigId(identitas('id'))->delete();
        });
    }
};
