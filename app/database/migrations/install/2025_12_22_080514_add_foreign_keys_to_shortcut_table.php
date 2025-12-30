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
        Schema::table('shortcut', function (Blueprint $table) {
            $table->foreign(['config_id'])->references(['id'])->on('config')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shortcut', function (Blueprint $table) {
            $table->dropForeign('shortcut_config_id_foreign');
        });
    }
};
