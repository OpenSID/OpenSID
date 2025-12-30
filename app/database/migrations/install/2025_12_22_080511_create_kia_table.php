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
        Schema::create('kia', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('config_id')->index('kia_config_fk');
            $table->string('no_kia')->index('no_kia');
            $table->integer('ibu_id')->nullable()->index('kia_ibu_fk');
            $table->integer('anak_id')->nullable()->index('kia_anak_fk');
            $table->date('hari_perkiraan_lahir')->nullable();
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->integer('created_by')->nullable();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->nullable()->useCurrent();
            $table->integer('updated_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kia');
    }
};
