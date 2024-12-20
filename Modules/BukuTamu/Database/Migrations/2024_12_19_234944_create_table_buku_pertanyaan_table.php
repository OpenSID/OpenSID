<?php

use App\Models\Produk;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\BukuTamu\Models\PertanyaanModel;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('buku_pertanyaan', function (Blueprint $table) {
            $table->id();
            $table->config();
            $table->mediumText('pertanyaan')->nullable();
            $table->status();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExistsDBGabungan('buku_pertanyaan', function () {
            PertanyaanModel::withoutConfigId(identitas('id'))->delete();
        });
    }
};
