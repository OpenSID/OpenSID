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
 * Hak Cipta 2016 - 2026 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2026 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

namespace App\Actions\GrupAkses;

use App\Models\Modul;
use App\Models\UserGrup;
use App\Traits\Migrator;

class DefaultGrupAkses
{
    use Migrator;

    /**
     * Create hak akses.
     *
     * @param int $configId
     *
     * @return void
     */
    public function handle($configId = null)
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

            // Grup tambahan berdasarkan tupoksi perangkat
            'kaur-perencanaan' => [
                'info-desa'             => 0,
                'wilayah-administratif' => 7,
                'status-desa'           => 7,
                'pemetaan'              => 0,
                'peta'                  => 7,
                'pengaturan-peta'       => 7,
                'plan'                  => 7,
                'point'                 => 7,
                'garis'                 => 7,
                'line'                  => 7,
                'area'                  => 7,
                'polygon'               => 7,
                'admin-web'             => 0,
                'artikel'               => 7,
                'widget'                => 7,
                'kategori'              => 7,
                'menu'                  => 7,
                'komentar'              => 7,
                'galeri'                => 7,
                'theme'                 => 7,
                'media-sosial'          => 7,
                'slider'                => 7,
                'teks-berjalan'         => 7,
                'pengunjung'            => 7,
                'pengaturan-web'        => 7,
            ],
            'kasi-pemerintahan' => [
                'kependudukan'                      => 0,
                'penduduk'                          => 7,
                'keluarga'                          => 7,
                'rumah-tangga'                      => 7,
                'kelompok'                          => 7,
                'data-suplemen'                     => 7,
                'calon-pemilih'                     => 7,
                'peristiwa'                         => 7,
                'statistik'                         => 0,
                'statistik-kependudukan'            => 7,
                'laporan-bulanan'                   => 7,
                'laporan-kelompok-rentan'           => 7,
                'laporan-penduduk'                  => 7,
                'sekretariat'                       => 0,
                'produk-hukum'                      => 7,
                'informasi-publik'                  => 7,
                'buku-administrasi-desa'            => 0,
                'administrasi-umum'                 => 7,
                'administrasi-penduduk'             => 7,
                'buku-mutasi-penduduk'              => 7,
                'buku-rekapitulasi-jumlah-penduduk' => 7,
                'buku-penduduk-sementara'           => 7,
                'buku-ktp-dan-kk'                   => 7,
                'pertanahan'                        => 0,
                'daftar-persil'                     => 7,
                'c-desa'                            => 7,
            ],
            'kasi-pelayanan' => [
                'layanan-surat'          => 0,
                'pengaturan-surat'       => 7,
                'cetak-surat'            => 7,
                'permohonan-surat'       => 7,
                'arsip-layanan'          => 7,
                'daftar-persyaratan'     => 7,
                'sekretariat'            => 0,
                'surat-masuk'            => 7,
                'surat-keluar'           => 7,
                'surat-dinas'            => 0,
                'pengaturan-surat-dinas' => 7,
                'cetak-surat-dinas'      => 7,
                'arsip-surat-dinas'      => 7,
            ],
            'kasi-kesejahteraan' => [
                'analisis'                   => 0,
                'analisis-kategori'          => 7,
                'analisis-indikator'         => 7,
                'analisis-klasifikasi'       => 7,
                'analisis-periode'           => 7,
                'analisis-respon'            => 7,
                'analisis-laporan'           => 7,
                'analisis-statistik-jawaban' => 7,
                'bantuan'                    => 0,
                'program-bantuan'            => 7,
                'peserta-bantuan'            => 7,
                'satu-data'                  => 0,
                'dtks'                       => 7,
            ],
            'kaur-umum-dan-perencanaan' => [
                'sekretariat'          => 0,
                'inventaris'           => 7,
                'inventaris-asset'     => 7,
                'inventaris-gedung'    => 7,
                'inventaris-jalan'     => 7,
                'inventaris-kontruksi' => 7,
                'inventaris-peralatan' => 7,
                'laporan-inventaris'   => 7,
            ],
            'kaur-keuangan' => [
                'keuangan'       => 0,
                'laporan'        => 7,
                'input-data'     => 7,
                'laporan-apbdes' => 7,
            ],
            'kepala-dusun' => [
                'kependudukan'                      => 0,
                'penduduk'                          => 3,
                'keluarga'                          => 3,
                'rumah-tangga'                      => 3,
                'kelompok'                          => 3,
                'peristiwa'                         => 3,
                'statistik'                         => 0,
                'statistik-kependudukan'            => 3,
                'laporan-bulanan'                   => 3,
                'laporan-kelompok-rentan'           => 3,
                'laporan-penduduk'                  => 3,
                'buku-administrasi-desa'            => 0,
                'administrasi-umum'                 => 3,
                'administrasi-penduduk'             => 3,
                'buku-mutasi-penduduk'              => 3,
                'buku-rekapitulasi-jumlah-penduduk' => 3,
                'buku-penduduk-sementara'           => 3,
                'buku-ktp-dan-kk'                   => 3,
                'pertanahan'                        => 0,
                'daftar-persil'                     => 3,
                'c-desa'                            => 3,
            ],
        ];

        $configId ??= identitas('id');
        $modul    = Modul::withoutConfigId($configId)->get();
        $modulMap = $modul->pluck('id', 'slug');

        foreach ($hakAksesBawaan as $role => $akses) {
            $idGrup = UserGrup::withoutConfigId($configId)->where('slug', $role)->first()->id;

            if (! $idGrup) {
                logger()->warning("Grup akses tidak ditemukan: {$role}");

                continue;
            }

            if (count($akses) == 1) {
                if (array_keys($akses)[0] == '*') {
                    $modul->each(static function ($q) use ($idGrup, $configId, $akses) {
                        $dataInsert = [
                            'config_id' => $configId,
                            'id_grup'   => $idGrup,
                            'id_modul'  => $q->id,
                            'akses'     => $akses['*'],
                        ];

                        (new UpsertGrupAkses())->handle($dataInsert);
                    });
                }
            } else {
                foreach ($akses as $slug => $itemAkses) {
                    if (! isset($modulMap[$slug])) {
                        logger()->warning("Slug modul tidak ditemukan: {$slug}");

                        continue;
                    }

                    $idModul    = $modulMap[$slug];
                    $dataInsert = [
                        'config_id' => $configId,
                        'id_grup'   => $idGrup,
                        'id_modul'  => $idModul,
                        'akses'     => $itemAkses,
                    ];

                    (new UpsertGrupAkses())->handle($dataInsert);
                }
            }
        }

        cache()->flush();

        return true;
    }
}
