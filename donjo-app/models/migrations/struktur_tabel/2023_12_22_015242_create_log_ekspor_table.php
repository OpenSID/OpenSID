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
        Schema::create('log_ekspor', function (Blueprint $table) {
            $table->integer('id', true);
            $table->timestamp('tgl_ekspor')->useCurrent();
            $table->string('kode_ekspor', 100);
            $table->integer('semua')->default(1);
            $table->date('dari_tgl')->nullable();
            $table->integer('total')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('log_ekspor');
    }
};
