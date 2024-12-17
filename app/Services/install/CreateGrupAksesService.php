<?php

namespace App\Services\Install;

use App\Models\Modul;
use App\Models\UserGrup;
use App\Traits\Migrator;

class CreateGrupAksesService
{
    use Migrator;

    /**
     * Create hak akses.
     *
     * @param int $configId
     * 
     * @return void
     */
    public function run($configId)
    {
        $hakAksesBawaan = [
            'administrator' => [
                '*' => 7,
            ],
            'kontributor' => [
                'admin-web' => 0,
                'artikel'   => 3,
                'komentar'  => 3,
                'galeri'    => 3,
                'slider'    => 3,
            ],
            'redaksi' => [
                'admin-web'      => 0,
                'artikel'        => 3,
                'widget'         => 3,
                'menu'           => 3,
                'komentar'       => 3,
                'galeri'         => 3,
                'media-sosial'   => 3,
                'slider'         => 3,
                'teks-berjalan'  => 3,
                'pengunjung'     => 3,
                'pengaturan-web' => 3,
                'kategori'       => 3,
                'lapak'          => 3,
            ],
            'operator' => [
                '*' => 3,
            ],
            'satgas-covid-19' => [
                'statistik'              => 0,
                'statistik-kependudukan' => 3,
                'kesehatan'              => 0,
                'pendataan'              => 7,
                'pemantauan'             => 7,
            ],
        ];

        $modul    = Modul::withoutConfigId($configId)->get();
        $modulMap = $modul->pluck('id', 'slug');

        foreach ($hakAksesBawaan as $role => $akses) {
            $idGrup = UserGrup::withoutConfigId($configId)->where('slug', $role)->first()->id;

            if (count($akses) == 1) {
                if (array_keys($akses)[0] == '*') {
                    $modul->each(function ($q) use ($idGrup, $configId, $akses) {
                        $dataInsert = [
                            'config_id' => $configId,
                            'id_grup'   => $idGrup,
                            'id_modul'  => $q->id,
                            'akses'     => $akses['*'],
                        ];

                        $this->createHakAkses($dataInsert);
                    });
                }
            } else {
                foreach ($akses as $slug => $itemAkses) {
                    $idModul    = $modulMap[$slug];
                    $dataInsert = [
                        'config_id' => $configId,
                        'id_grup'   => $idGrup,
                        'id_modul'  => $idModul,
                        'akses'     => $itemAkses,
                    ];
                    $this->createHakAkses($dataInsert);
                }
            }
        }

        cache()->flush();

        return true;
    }
}