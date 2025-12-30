<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RefJabatan;
use App\Models\SettingAplikasi;

class RefJabatanSeeder extends Seeder
{
    public function run(): void
    {
        $sebutanDesa = SettingAplikasi::where('key', 'sebutan_desa')->value('value') ?? 'desa';

        $data = [
            [
                'nama'  => 'Kepala ' . ucwords($sebutanDesa),
                'jenis' => RefJabatan::KADES,
            ],
            [
                'nama'  => 'Sekretaris',
                'jenis' => RefJabatan::SEKDES,
            ],
        ];

        foreach ($data as $item) {
            RefJabatan::updateOrCreate(
                [
                    'nama'  => $item['nama'],
                    'jenis' => $item['jenis'],
                ],
                $item
            );
        }
    }
}
