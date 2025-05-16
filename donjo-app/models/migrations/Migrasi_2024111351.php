<?php

/*
 *
 * File ini bagian dari:
 *
 * OpenSID
 *
 * Sistem informasi desa sumber terbuka untuk memajukan desa
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * Hak Cipta 2016 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 *
 * Dengan ini diberikan izin, secara gratis, kepada siapa pun yang mendapatkan salinan
 * dari perangkat lunak ini dan file dokumentasi terkait ("Aplikasi Ini"), untuk diperlakukan
 * tanpa batasan, termasuk hak untuk menggunakan, menyalin, mengubah dan/atau mendistribusikan,
 * asal tunduk pada syarat berikut:
 *
 * Pemberitahuan hak cipta di atas dan pemberitahuan izin ini harus disertakan dalam
 * setiap salinan atau bagian penting Aplikasi Ini. Barang siapa yang menghapus atau menghilangkan
 * pemberitahuan ini melanggar ketentuan lisensi Aplikasi Ini.
 *
 * PERANGKAT LUNAK INI DISEDIAKAN "SEBAGAIMANA ADANYA", TANPA JAMINAN APA PUN, BAIK TERSURAT MAUPUN
 * TERSIRAT. PENULIS ATAU PEMEGANG HAK CIPTA SAMA SEKALI TIDAK BERTANGGUNG JAWAB ATAS KLAIM, KERUSAKAN ATAU
 * KEWAJIBAN APAPUN ATAS PENGGUNAAN ATAU LAINNYA TERKAIT APLIKASI INI.
 *
 * @package   OpenSID
 * @author    Tim Pengembang OpenDesa
 * @copyright Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * @copyright Hak Cipta 2016 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

use App\Models\FormatSurat;
use App\Models\GrupAkses;
use App\Models\Modul;
use App\Models\UserGrup;

defined('BASEPATH') || exit('No direct script access allowed');

class Migrasi_2024111351 extends MY_model
{
    public function up()
    {
        $hasil = true;
        $hasil = $this->migrasi_2024110351($hasil);
        $hasil = $this->migrasi_2024110652($hasil);
        $hasil = $this->migrasi_2024111251($hasil);

        return $this->migrasi_2024110651($hasil);
    }

    private function migrasi_2024110651($hasil)
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

        $configId = identitas('id');
        $modul    = Modul::get();
        $modulMap = $modul->pluck('id', 'slug');

        foreach ($hakAksesBawaan as $role => $akses) {
            $idGrup = UserGrup::where('slug', $role)->first()->id;

            if (! $idGrup) continue;

            if (count($akses) == 1) {
                if (array_keys($akses)[0] == '*') {
                    $modul->each(static function ($q) use ($akses, $idGrup, $configId) {
                        $dataInsert = [
                            'config_id' => $configId,
                            'id_grup'   => $idGrup,
                            'id_modul'  => $q->id,
                            'akses'     => $akses['*'],
                        ];
                        GrupAkses::upsert($dataInsert, ['id_grup', 'id_modul']);
                    });

                    continue;
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
                    GrupAkses::upsert($dataInsert, ['id_grup', 'id_modul']);
                }
            }
        }

        cache()->flush();

        return $hasil;
    }

    private function migrasi_2024110652($hasil)
    {
        copyFavicon();

        return $hasil;
    }

    protected function migrasi_2024110351($hasil)
    {
        $hasil = $hasil && $this->hapus_foreign_key('lokasi', 'persil_peta_fk', 'persil');
        $hasil = $hasil && $this->tambahForeignKey('persil_peta_fk', 'persil', 'id_peta', 'area', 'id', true);
        $hasil = $hasil && $this->hapus_foreign_key('lokasi', 'mutasi_cdesa_peta_fk', 'mutasi_cdesa');

        return $hasil && $this->tambahForeignKey('mutasi_cdesa_peta_fk', 'mutasi_cdesa', 'id_peta', 'area', 'id', true);
    }

    protected function migrasi_2024111251($hasil)
    {
        FormatSurat::where('url_surat', 'sistem-surat-keterangan-pengantar-rujukcerai')->where('jenis', FormatSurat::TINYMCE_SISTEM)->delete();

        return $hasil;
    }
}
