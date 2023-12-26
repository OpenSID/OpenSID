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
        Schema::create('cdesa', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('config_id')->nullable()->index('cdesa_config_fk');
            $table->string('nomor', 20)->unique('nomor');
            $table->string('nama_kepemilikan', 100);
            $table->boolean('jenis_pemilik')->default(false);
            $table->string('nama_pemilik_luar', 100)->nullable();
            $table->string('alamat_pemilik_luar', 200)->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->integer('created_by');
            $table->timestamp('updated_at')->useCurrent();
            $table->integer('updated_by');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cdesa');
    }
};
