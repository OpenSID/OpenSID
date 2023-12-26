<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tweb_penduduk_mandiri', function (Blueprint $table) {
            $table->char('pin', 32);
            $table->integer('config_id')->nullable()->index('tweb_penduduk_mandiri_config_fk');
            $table->dateTime('last_login')->nullable();
            $table->dateTime('tanggal_buat')->nullable();
            $table->integer('id_pend')->primary();
            $table->integer('aktif')->nullable()->default(1);
            $table->string('scan_ktp', 100)->nullable();
            $table->string('scan_kk', 100)->nullable();
            $table->string('foto_selfie', 100)->nullable();
            $table->boolean('ganti_pin')->default(true);
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->nullable()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tweb_penduduk_mandiri');
    }
};
