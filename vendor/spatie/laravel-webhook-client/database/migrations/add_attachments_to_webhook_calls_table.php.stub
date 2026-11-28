<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasColumn('webhook_calls', 'attachments')) {
            return;
        }

        Schema::table('webhook_calls', function (Blueprint $table) {
            $table->json('attachments')->nullable()->after('payload');
        });
    }
};
