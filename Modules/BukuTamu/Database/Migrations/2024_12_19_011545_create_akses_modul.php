<?php

use App\Traits\Migrator;
use Illuminate\Support\Facades\Schema;
use Modules\BukuTamu\Database\Seeders\BukuTamuSeeder;

return new class
{
    use Migrator;

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Jalankan seeder
        (new BukuTamuSeeder())->run();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $id = identitas('id');
        $this->deleteSetting(['config_id' => $id, 'kategori' => 'buku-tamu']);
        $this->deleteModul(['config_id' => $id, 'slug' => 'buku-tamu']);
    }
};
