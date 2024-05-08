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

use App\Models\GrupAkses;
use App\Models\Modul;
use App\Models\UserGrup;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

defined('BASEPATH') || exit('No direct script access allowed');

class Migrasi_dev extends MY_model
{
    public function up()
    {
        $hasil = true;

        $hasil = $hasil && $this->migrasi_tabel($hasil);

        return $hasil && $this->migrasi_data($hasil);
    }

    protected function migrasi_tabel($hasil)
    {
        return $hasil && true;
    }

    // Migrasi perubahan data
    protected function migrasi_data($hasil)
    {
        // Migrasi berdasarkan config_id
        $config_id = DB::table('config')->pluck('id')->toArray();

        foreach ($config_id as $id) {
            $hasil = $hasil && $this->migrasi_2024050271($hasil, $id);
            $hasil = $hasil && $this->migrasi_2024050272($hasil, $id);
            $hasil = $hasil && $this->migrasi_2024050251($hasil, $id);
        }

        $hasil = $hasil && $this->migrasi_2024050551($hasil);

        return $hasil && true;
    }

    public function migrasi_2024050272($hasil, $id)
    {
        return $hasil && $this->tambah_setting([
            'judul'      => 'Icon Pembangunan Peta',
            'key'        => 'icon_pembangunan_peta',
            'value'      => 'construction.png',
            'keterangan' => 'Icon penanda Lokasi Pembangunan yang ditampilkan pada Peta',
            'jenis'      => 'select-simbol',
            'option'     => json_encode(['model' => 'App\\Models\\Simbol', 'value' => 'simbol', 'label' => 'simbol']),
            'attribute'  => 'class="required"',
            'kategori'   => 'pembangunan',
        ], $id);
    }

    protected function migrasi_2024050251($hasil)
    {
        return $hasil && $this->ubah_modul(
            ['slug' => 'informasi-publik', 'url' => 'dokumen/clear'],
            ['url' => 'dokumen']
        );
    }

    protected function migrasi_2024050551($hasil)
    {
        if ($this->db->field_exists('nama', 'user_grup')) {
            $hasil = $hasil && $this->dbforge->modify_column('user_grup', [
                'nama' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 255,
                ],
            ]);
        }

        $pengguna = [
            [
                'config_id'  => identitas('id'),
                'nama'       => 'Sekretaris Desa',
                'slug'       => 'sekretaris-desa',
                'jenis'      => 1,
                'created_at' => Carbon::now(),
                'created_by' => 0,
                'updated_at' => Carbon::now(),
                'updated_by' => 0,
            ],
            [
                'config_id'  => identitas('id'),
                'nama'       => 'Kaur Perencanaan',
                'slug'       => 'kaur-perencanaan',
                'jenis'      => 1,
                'created_at' => Carbon::now(),
                'created_by' => 0,
                'updated_at' => Carbon::now(),
                'updated_by' => 0,
            ],
            [
                'config_id'  => identitas('id'),
                'nama'       => 'Kasi Pemerintahan',
                'slug'       => 'kasi-pemerintahan',
                'jenis'      => 1,
                'created_at' => Carbon::now(),
                'created_by' => 0,
                'updated_at' => Carbon::now(),
                'updated_by' => 0,
            ],
            [
                'config_id'  => identitas('id'),
                'nama'       => 'Kasi Pelayanan',
                'slug'       => 'kasi-pelayanan',
                'jenis'      => 1,
                'created_at' => Carbon::now(),
                'created_by' => 0,
                'updated_at' => Carbon::now(),
                'updated_by' => 0,
            ],
            [
                'config_id'  => identitas('id'),
                'nama'       => 'Kasi Kesejahteraan',
                'slug'       => 'kasi-kesejahteraan',
                'jenis'      => 1,
                'created_at' => Carbon::now(),
                'created_by' => 0,
                'updated_at' => Carbon::now(),
                'updated_by' => 0,
            ],
            [
                'config_id'  => identitas('id'),
                'nama'       => 'Kaur Umum dan Perencanaan',
                'slug'       => 'kaur-umum-dan-perencanaan',
                'jenis'      => 1,
                'created_at' => Carbon::now(),
                'created_by' => 0,
                'updated_at' => Carbon::now(),
                'updated_by' => 0,
            ],
            [
                'config_id'  => identitas('id'),
                'nama'       => 'Kaur Keuangan',
                'slug'       => 'kaur-keuangan',
                'jenis'      => 1,
                'created_at' => Carbon::now(),
                'created_by' => 0,
                'updated_at' => Carbon::now(),
                'updated_by' => 0,
            ],
            [
                'config_id'  => identitas('id'),
                'nama'       => 'Kepala Dusun',
                'slug'       => 'kepala-dusun',
                'jenis'      => 1,
                'created_at' => Carbon::now(),
                'created_by' => 0,
                'updated_at' => Carbon::now(),
                'updated_by' => 0,
            ],
        ];

        UserGrup::upsert($pengguna, ['slug']);

        $data = [
            [
                'grup'  => 'kaur-perencanaan',
                'slug'  => 'info-desa',
                'akses' => 0,
            ],
            [
                'grup'  => 'kaur-perencanaan',
                'slug'  => 'wilayah-administratif',
                'akses' => 7,
            ],
            [
                'grup'  => 'kaur-perencanaan',
                'slug'  => 'status-desa',
                'akses' => 7,
            ],
            [
                'grup'  => 'kaur-perencanaan',
                'slug'  => 'pemetaan',
                'akses' => 0,
            ],
            [
                'grup'  => 'kaur-perencanaan',
                'slug'  => 'peta',
                'akses' => 7,
            ],
            [
                'grup'  => 'kaur-perencanaan',
                'slug'  => 'pengaturan-peta',
                'akses' => 7,
            ],
            [
                'grup'  => 'kaur-perencanaan',
                'slug'  => 'plan',
                'akses' => 7,
            ],
            [
                'grup'  => 'kaur-perencanaan',
                'slug'  => 'point',
                'akses' => 7,
            ],
            [
                'grup'  => 'kaur-perencanaan',
                'slug'  => 'garis',
                'akses' => 7,
            ],
            [
                'grup'  => 'kaur-perencanaan',
                'slug'  => 'line',
                'akses' => 7,
            ],
            [
                'grup'  => 'kaur-perencanaan',
                'slug'  => 'area',
                'akses' => 7,
            ],
            [
                'grup'  => 'kaur-perencanaan',
                'slug'  => 'polygon',
                'akses' => 7,
            ],
            [
                'grup'  => 'kaur-perencanaan',
                'slug'  => 'admin-web',
                'akses' => 0,
            ],
            [
                'grup'  => 'kaur-perencanaan',
                'slug'  => 'artikel',
                'akses' => 7,
            ],
            [
                'grup'  => 'kaur-perencanaan',
                'slug'  => 'widget',
                'akses' => 7,
            ],
            [
                'grup'  => 'kaur-perencanaan',
                'slug'  => 'kategori',
                'akses' => 7,
            ],
            [
                'grup'  => 'kaur-perencanaan',
                'slug'  => 'menu',
                'akses' => 7,
            ],
            [
                'grup'  => 'kaur-perencanaan',
                'slug'  => 'komentar',
                'akses' => 7,
            ],
            [
                'grup'  => 'kaur-perencanaan',
                'slug'  => 'galeri',
                'akses' => 7,
            ],
            [
                'grup'  => 'kaur-perencanaan',
                'slug'  => 'theme',
                'akses' => 7,
            ],
            [
                'grup'  => 'kaur-perencanaan',
                'slug'  => 'media-sosial',
                'akses' => 7,
            ],
            [
                'grup'  => 'kaur-perencanaan',
                'slug'  => 'slider',
                'akses' => 7,
            ],
            [
                'grup'  => 'kaur-perencanaan',
                'slug'  => 'teks-berjalan',
                'akses' => 7,
            ],
            [
                'grup'  => 'kaur-perencanaan',
                'slug'  => 'pengunjung',
                'akses' => 7,
            ],
            [
                'grup'  => 'kaur-perencanaan',
                'slug'  => 'pengaturan-web',
                'akses' => 7,
            ],
            [
                'grup'  => 'kasi-pemerintahan',
                'slug'  => 'kependudukan',
                'akses' => 0,
            ],
            [
                'grup'  => 'kasi-pemerintahan',
                'slug'  => 'penduduk',
                'akses' => 7,
            ],
            [
                'grup'  => 'kasi-pemerintahan',
                'slug'  => 'keluarga',
                'akses' => 7,
            ],
            [
                'grup'  => 'kasi-pemerintahan',
                'slug'  => 'rumah-tangga',
                'akses' => 7,
            ],
            [
                'grup'  => 'kasi-pemerintahan',
                'slug'  => 'kelompok',
                'akses' => 7,
            ],
            [
                'grup'  => 'kasi-pemerintahan',
                'slug'  => 'data-suplemen',
                'akses' => 7,
            ],
            [
                'grup'  => 'kasi-pemerintahan',
                'slug'  => 'calon-pemilih',
                'akses' => 7,
            ],
            [
                'grup'  => 'kasi-pemerintahan',
                'slug'  => 'kategori-kelompok',
                'akses' => 7,
            ],
            [
                'grup'  => 'kasi-pemerintahan',
                'slug'  => 'peristiwa',
                'akses' => 7,
            ],
            [
                'grup'  => 'kasi-pemerintahan',
                'slug'  => 'statistik',
                'akses' => 0,
            ],
            [
                'grup'  => 'kasi-pemerintahan',
                'slug'  => 'statistik-kependudukan',
                'akses' => 7,
            ],
            [
                'grup'  => 'kasi-pemerintahan',
                'slug'  => 'laporan-bulanan',
                'akses' => 7,
            ],
            [
                'grup'  => 'kasi-pemerintahan',
                'slug'  => 'laporan-kelompok-rentan',
                'akses' => 7,
            ],
            [
                'grup'  => 'kasi-pemerintahan',
                'slug'  => 'laporan-penduduk',
                'akses' => 7,
            ],
            [
                'grup'  => 'kasi-pemerintahan',
                'slug'  => 'sekretariat',
                'akses' => 0,
            ],
            [
                'grup'  => 'kasi-pemerintahan',
                'slug'  => 'produk-hukum',
                'akses' => 7,
            ],
            [
                'grup'  => 'kasi-pemerintahan',
                'slug'  => 'informasi-publik',
                'akses' => 7,
            ],
            [
                'grup'  => 'kasi-pemerintahan',
                'slug'  => 'buku-administrasi-desa',
                'akses' => 0,
            ],
            [
                'grup'  => 'kasi-pemerintahan',
                'slug'  => 'administrasi-umum',
                'akses' => 7,
            ],
            [
                'grup'  => 'kasi-pemerintahan',
                'slug'  => 'administrasi-penduduk',
                'akses' => 7,
            ],
            [
                'grup'  => 'kasi-pemerintahan',
                'slug'  => 'buku-mutasi-penduduk',
                'akses' => 7,
            ],
            [
                'grup'  => 'kasi-pemerintahan',
                'slug'  => 'buku-rekapitulasi-jumlah-penduduk',
                'akses' => 7,
            ],
            [
                'grup'  => 'kasi-pemerintahan',
                'slug'  => 'buku-penduduk-sementara',
                'akses' => 7,
            ],
            [
                'grup'  => 'kasi-pemerintahan',
                'slug'  => 'buku-ktp-dan-kk',
                'akses' => 7,
            ],
            [
                'grup'  => 'kasi-pemerintahan',
                'slug'  => 'pertanahan',
                'akses' => 0,
            ],
            [
                'grup'  => 'kasi-pemerintahan',
                'slug'  => 'daftar-persil',
                'akses' => 7,
            ],
            [
                'grup'  => 'kasi-pemerintahan',
                'slug'  => 'c-desa',
                'akses' => 7,
            ],
            [
                'grup'  => 'kasi-pelayanan',
                'slug'  => 'layanan-surat',
                'akses' => 0,
            ],
            [
                'grup'  => 'kasi-pelayanan',
                'slug'  => 'pengaturan-surat',
                'akses' => 7,
            ],
            [
                'grup'  => 'kasi-pelayanan',
                'slug'  => 'cetak-surat',
                'akses' => 7,
            ],
            [
                'grup'  => 'kasi-pelayanan',
                'slug'  => 'permohonan-surat',
                'akses' => 7,
            ],
            [
                'grup'  => 'kasi-pelayanan',
                'slug'  => 'arsip-layanan',
                'akses' => 7,
            ],
            [
                'grup'  => 'kasi-pelayanan',
                'slug'  => 'daftar-persyaratan',
                'akses' => 7,
            ],
            [
                'grup'  => 'kasi-pelayanan',
                'slug'  => 'sekretariat',
                'akses' => 0,
            ],
            [
                'grup'  => 'kasi-pelayanan',
                'slug'  => 'surat-masuk',
                'akses' => 7,
            ],
            [
                'grup'  => 'kasi-pelayanan',
                'slug'  => 'surat-keluar',
                'akses' => 7,
            ],
            [
                'grup'  => 'kasi-pelayanan',
                'slug'  => 'surat-dinas',
                'akses' => 0,
            ],
            [
                'grup'  => 'kasi-pelayanan',
                'slug'  => 'pengaturan-surat-dinas',
                'akses' => 7,
            ],
            [
                'grup'  => 'kasi-pelayanan',
                'slug'  => 'cetak-surat-dinas',
                'akses' => 7,
            ],
            [
                'grup'  => 'kasi-pelayanan',
                'slug'  => 'arsip-surat-dinas',
                'akses' => 7,
            ],
            [
                'grup'  => 'kasi-kesejahteraan',
                'slug'  => 'analisis',
                'akses' => 0,
            ],
            [
                'grup'  => 'kasi-kesejahteraan',
                'slug'  => 'analisis-kategori',
                'akses' => 7,
            ],
            [
                'grup'  => 'kasi-kesejahteraan',
                'slug'  => 'analisis-indikator',
                'akses' => 7,
            ],
            [
                'grup'  => 'kasi-kesejahteraan',
                'slug'  => 'analisis-klasifikasi',
                'akses' => 7,
            ],
            [
                'grup'  => 'kasi-kesejahteraan',
                'slug'  => 'analisis-periode',
                'akses' => 7,
            ],
            [
                'grup'  => 'kasi-kesejahteraan',
                'slug'  => 'analisis-respon',
                'akses' => 7,
            ],
            [
                'grup'  => 'kasi-kesejahteraan',
                'slug'  => 'analisis-laporan',
                'akses' => 7,
            ],
            [
                'grup'  => 'kasi-kesejahteraan',
                'slug'  => 'analisis-statistik-jawaban',
                'akses' => 7,
            ],
            [
                'grup'  => 'kasi-kesejahteraan',
                'slug'  => 'master-analisis',
                'akses' => 7,
            ],
            [
                'grup'  => 'kasi-kesejahteraan',
                'slug'  => 'pengaturan-analisis',
                'akses' => 7,
            ],
            [
                'grup'  => 'kasi-kesejahteraan',
                'slug'  => 'bantuan',
                'akses' => 0,
            ],
            [
                'grup'  => 'kasi-kesejahteraan',
                'slug'  => 'program-bantuan',
                'akses' => 7,
            ],
            [
                'grup'  => 'kasi-kesejahteraan',
                'slug'  => 'peserta-bantuan',
                'akses' => 7,
            ],
            [
                'grup'  => 'kasi-kesejahteraan',
                'slug'  => 'satu-data',
                'akses' => 0,
            ],
            [
                'grup'  => 'kasi-kesejahteraan',
                'slug'  => 'dtks',
                'akses' => 7,
            ],
            [
                'grup'  => 'kaur-umum-dan-perencanaan',
                'slug'  => 'sekretariat',
                'akses' => 0,
            ],
            [
                'grup'  => 'kaur-umum-dan-perencanaan',
                'slug'  => 'inventaris',
                'akses' => 7,
            ],
            [
                'grup'  => 'kaur-umum-dan-perencanaan',
                'slug'  => 'inventaris-asset',
                'akses' => 7,
            ],
            [
                'grup'  => 'kaur-umum-dan-perencanaan',
                'slug'  => 'inventaris-gedung',
                'akses' => 7,
            ],
            [
                'grup'  => 'kaur-umum-dan-perencanaan',
                'slug'  => 'inventaris-jalan',
                'akses' => 7,
            ],
            [
                'grup'  => 'kaur-umum-dan-perencanaan',
                'slug'  => 'inventaris-kontruksi',
                'akses' => 7,
            ],
            [
                'grup'  => 'kaur-umum-dan-perencanaan',
                'slug'  => 'inventaris-peralatan',
                'akses' => 7,
            ],
            [
                'grup'  => 'kaur-umum-dan-perencanaan',
                'slug'  => 'api-inventaris-asset',
                'akses' => 7,
            ],
            [
                'grup'  => 'kaur-umum-dan-perencanaan',
                'slug'  => 'api-inventaris-gedung',
                'akses' => 7,
            ],
            [
                'grup'  => 'kaur-umum-dan-perencanaan',
                'slug'  => 'api-inventaris-gedung-1',
                'akses' => 7,
            ],
            [
                'grup'  => 'kaur-umum-dan-perencanaan',
                'slug'  => 'api-inventaris-jalan',
                'akses' => 7,
            ],
            [
                'grup'  => 'kaur-umum-dan-perencanaan',
                'slug'  => 'api-inventaris-kontruksi',
                'akses' => 7,
            ],
            [
                'grup'  => 'kaur-umum-dan-perencanaan',
                'slug'  => 'api-inventaris-peralatan',
                'akses' => 7,
            ],
            [
                'grup'  => 'kaur-umum-dan-perencanaan',
                'slug'  => 'api-inventaris-tanah',
                'akses' => 7,
            ],
            [
                'grup'  => 'kaur-umum-dan-perencanaan',
                'slug'  => 'laporan-inventaris',
                'akses' => 7,
            ],
            [
                'grup'  => 'kaur-keuangan',
                'slug'  => 'keuangan',
                'akses' => 0,
            ],
            [
                'grup'  => 'kaur-keuangan',
                'slug'  => 'impor-data',
                'akses' => 7,
            ],
            [
                'grup'  => 'kaur-keuangan',
                'slug'  => 'laporan',
                'akses' => 7,
            ],
            [
                'grup'  => 'kaur-keuangan',
                'slug'  => 'input-data',
                'akses' => 7,
            ],
            [
                'grup'  => 'kaur-keuangan',
                'slug'  => 'laporan-manual',
                'akses' => 7,
            ],
            [
                'grup'  => 'kaur-keuangan',
                'slug'  => 'laporan-apbdes',
                'akses' => 7,
            ],
            [
                'grup'  => 'kepala-dusun',
                'slug'  => 'kependudukan',
                'akses' => 0,
            ],
            [
                'grup'  => 'kepala-dusun',
                'slug'  => 'penduduk',
                'akses' => 3,
            ],
            [
                'grup'  => 'kepala-dusun',
                'slug'  => 'keluarga',
                'akses' => 3,
            ],
            [
                'grup'  => 'kepala-dusun',
                'slug'  => 'rumah-tangga',
                'akses' => 3,
            ],
            [
                'grup'  => 'kepala-dusun',
                'slug'  => 'kelompok',
                'akses' => 3,
            ],
            [
                'grup'  => 'kepala-dusun',
                'slug'  => 'peristiwa',
                'akses' => 3,
            ],
            [
                'grup'  => 'kepala-dusun',
                'slug'  => 'statistik',
                'akses' => 0,
            ],
            [
                'grup'  => 'kepala-dusun',
                'slug'  => 'statistik-kependudukan',
                'akses' => 3,
            ],
            [
                'grup'  => 'kepala-dusun',
                'slug'  => 'laporan-bulanan',
                'akses' => 3,
            ],
            [
                'grup'  => 'kepala-dusun',
                'slug'  => 'laporan-kelompok-rentan',
                'akses' => 3,
            ],
            [
                'grup'  => 'kepala-dusun',
                'slug'  => 'laporan-penduduk',
                'akses' => 3,
            ],
            [
                'grup'  => 'kepala-dusun',
                'slug'  => 'buku-administrasi-desa',
                'akses' => 0,
            ],
            [
                'grup'  => 'kepala-dusun',
                'slug'  => 'administrasi-umum',
                'akses' => 3,
            ],
            [
                'grup'  => 'kepala-dusun',
                'slug'  => 'administrasi-penduduk',
                'akses' => 3,
            ],
            [
                'grup'  => 'kepala-dusun',
                'slug'  => 'buku-mutasi-penduduk',
                'akses' => 3,
            ],
            [
                'grup'  => 'kepala-dusun',
                'slug'  => 'buku-rekapitulasi-jumlah-penduduk',
                'akses' => 3,
            ],
            [
                'grup'  => 'kepala-dusun',
                'slug'  => 'buku-penduduk-sementara',
                'akses' => 3,
            ],
            [
                'grup'  => 'kepala-dusun',
                'slug'  => 'buku-ktp-dan-kk',
                'akses' => 3,
            ],
            [
                'grup'  => 'kepala-dusun',
                'slug'  => 'pertanahan',
                'akses' => 0,
            ],
            [
                'grup'  => 'kepala-dusun',
                'slug'  => 'daftar-persil',
                'akses' => 3,
            ],
            [
                'grup'  => 'kepala-dusun',
                'slug'  => 'c-desa',
                'akses' => 3,
            ],
        ];

        $result = [];

        foreach (Modul::select(['slug', 'parent'])->get() as $value) {
            $sekre         = [];
            $sekre['grup'] = 'sekretaris-desa';
            $sekre['slug'] = $value->slug;

            if ($value->parent == 0) {
                $sekre['akses'] = 0;
            } else {
                $sekre['akses'] = 7;
            }

            $result[] = $sekre;
        }

        $data = array_merge($data, $result);

        foreach ($data as $row) {
            if ($id_modul = Modul::where('slug', $row['slug'])->first()->id) {
                $dataInsert = [
                    'config_id' => identitas('id'),
                    'id_grup'   => UserGrup::where('slug', $row['grup'])->first()->id,
                    'id_modul'  => $id_modul,
                    'akses'     => $row['akses'],
                ];
            }
            GrupAkses::upsert($dataInsert, ['id_grup'], ['id_modul']);
        }

        return $hasil;
    }

    public function migrasi_2024050271($hasil, $id)
    {
        $hasil = $hasil && $this->tambah_setting([
            'judul'      => 'Jumlah Gambar Galeri',
            'key'        => 'jumlah_gambar_galeri',
            'value'      => 4,
            'keterangan' => 'Jumlah gambar galeri yang ditampilkan pada widget galeri',
            'jenis'      => 'input-number',
            'attribute'  => 'min="1" max="50" step="1"',
            'kategori'   => 'galeri',
        ], $id);

        return $hasil && $this->tambah_setting([
            'judul'      => 'Urutan Gambar Galeri',
            'key'        => 'urutan_gambar_galeri',
            'value'      => 'acak',
            'keterangan' => 'Urutan gambar galeri yang ditampilkan pada widget galeri',
            'jenis'      => 'option',
            'option'     => json_encode([
                'asc'  => 'A - Z',
                'desc' => 'Z - A',
                'acak' => 'Acak',
            ]),
            'kategori' => 'galeri',
        ], $id);
    }
}
