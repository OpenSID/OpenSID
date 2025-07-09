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
 * Hak Cipta 2016 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

use App\Imports\KlasifikasiSuratImports;
use App\Models\Config;
use Illuminate\Support\Facades\DB;

defined('BASEPATH') || exit('No direct script access allowed');

class Data_awal_seeder extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        ini_set('memory_limit', '512M');
        set_time_limit(5400);
    }

    public function run()
    {
        $db = DB::getDatabaseName();
        // Error menggunakan Illuminate untuk alter database ini
        // DB::statement("ALTER DATABASE {$db} CHARACTER SET utf8 COLLATE utf8_general_ci;");
        $this->db->query(
            "ALTER DATABASE `{$db}` CHARACTER SET {$this->db->char_set} COLLATE {$this->db->dbcollat};"
        );

        $this->load->helper('directory');
        $directoryTable = 'donjo-app/models/migrations/struktur_tabel';
        $migrations     = directory_map($directoryTable, 1);
        // sort by name
        usort($migrations, static fn ($a, $b) => strcmp($a, $b));

        foreach ($migrations as $migrate) {
            $migrateFile = require $directoryTable . DIRECTORY_SEPARATOR . $migrate;
            $migrateFile->up();
        }

        $this->defaultConfig();
        $this->addSettingModul();
        $this->addDataMaster();
    }

    private function addSettingModul()
    {
        // id harus diset manual karena dipakai di code data awal
        DB::table('setting_modul')->insert(
            [
                [
                    'id'         => 2,
                    'config_id'  => 1,
                    'modul'      => 'Kependudukan',
                    'slug'       => 'kependudukan',
                    'url'        => '',
                    'aktif'      => 1,
                    'ikon'       => 'fa-users',
                    'urut'       => 30,
                    'level'      => 2,
                    'hidden'     => 0,
                    'ikon_kecil' => 'fa fa-users',
                    'parent'     => 0,
                ],
                [
                    'id'         => 3,
                    'config_id'  => 1,
                    'modul'      => 'Statistik',
                    'slug'       => 'statistik',
                    'url'        => '',
                    'aktif'      => 1,
                    'ikon'       => 'fa-line-chart',
                    'urut'       => 40,
                    'level'      => 2,
                    'hidden'     => 0,
                    'ikon_kecil' => 'fa fa-line-chart',
                    'parent'     => 0,
                ],
                [
                    'id'         => 4,
                    'config_id'  => 1,
                    'modul'      => 'Layanan Surat',
                    'slug'       => 'layanan-surat',
                    'url'        => '',
                    'aktif'      => 1,
                    'ikon'       => 'fa-book',
                    'urut'       => 50,
                    'level'      => 2,
                    'hidden'     => 0,
                    'ikon_kecil' => 'fa fa-book',
                    'parent'     => 0,
                ],
                [
                    'id'         => 5,
                    'config_id'  => 1,
                    'modul'      => 'Analisis',
                    'slug'       => 'analisis',
                    'url'        => '',
                    'aktif'      => 1,
                    'ikon'       => ' fa-check-square-o',
                    'urut'       => 90,
                    'level'      => 2,
                    'hidden'     => 0,
                    'ikon_kecil' => 'fa fa-check-square-o',
                    'parent'     => 0,
                ],
                [
                    'id'         => 6,
                    'config_id'  => 1,
                    'modul'      => 'Bantuan',
                    'slug'       => 'bantuan',
                    'url'        => 'program_bantuan/clear',
                    'aktif'      => 1,
                    'ikon'       => 'fa-heart',
                    'urut'       => 100,
                    'level'      => 2,
                    'hidden'     => 0,
                    'ikon_kecil' => 'fa fa-heart',
                    'parent'     => 0,
                ],
                [
                    'id'         => 7,
                    'config_id'  => 1,
                    'modul'      => 'Pertanahan',
                    'slug'       => 'pertanahan',
                    'url'        => '',
                    'aktif'      => 1,
                    'ikon'       => 'fa-map-signs',
                    'urut'       => 110,
                    'level'      => 2,
                    'hidden'     => 0,
                    'ikon_kecil' => 'fa fa-map-signs',
                    'parent'     => 0,
                ],
                [
                    'id'         => 9,
                    'config_id'  => 1,
                    'modul'      => 'Pemetaan',
                    'slug'       => 'pemetaan',
                    'url'        => '',
                    'aktif'      => 1,
                    'ikon'       => 'fa-globe',
                    'urut'       => 130,
                    'level'      => 2,
                    'hidden'     => 0,
                    'ikon_kecil' => 'fa fa-globe',
                    'parent'     => 0,
                ],
                [
                    'id'         => 10,
                    'config_id'  => 1,
                    'modul'      => 'Hubung Warga',
                    'slug'       => 'hubung-warga',
                    'url'        => '',
                    'aktif'      => 1,
                    'ikon'       => 'fa-envelope',
                    'urut'       => 140,
                    'level'      => 2,
                    'hidden'     => 0,
                    'ikon_kecil' => 'fa fa-envelope',
                    'parent'     => 0,
                ],
                [
                    'id'         => 11,
                    'config_id'  => 1,
                    'modul'      => 'Pengaturan',
                    'slug'       => 'pengaturan',
                    'url'        => '',
                    'aktif'      => 1,
                    'ikon'       => 'fa-users',
                    'urut'       => 150,
                    'level'      => 1,
                    'hidden'     => 1,
                    'ikon_kecil' => 'fa-users',
                    'parent'     => 0,
                ],
                [
                    'id'         => 13,
                    'config_id'  => 1,
                    'modul'      => 'Admin Web',
                    'slug'       => 'admin-web',
                    'url'        => '',
                    'aktif'      => 1,
                    'ikon'       => 'fa-desktop',
                    'urut'       => 160,
                    'level'      => 4,
                    'hidden'     => 0,
                    'ikon_kecil' => 'fa fa-desktop',
                    'parent'     => 0,
                ],
                [
                    'id'         => 14,
                    'config_id'  => 1,
                    'modul'      => 'Layanan Mandiri',
                    'slug'       => 'layanan-mandiri',
                    'url'        => '',
                    'aktif'      => 1,
                    'ikon'       => 'fa-inbox',
                    'urut'       => 170,
                    'level'      => 2,
                    'hidden'     => 0,
                    'ikon_kecil' => 'fa fa-inbox',
                    'parent'     => 0,
                ],
                [
                    'id'         => 15,
                    'config_id'  => 1,
                    'modul'      => 'Sekretariat',
                    'slug'       => 'sekretariat',
                    'url'        => '',
                    'aktif'      => 1,
                    'ikon'       => 'fa-archive',
                    'urut'       => 60,
                    'level'      => 2,
                    'hidden'     => 0,
                    'ikon_kecil' => 'fa fa-archive',
                    'parent'     => 0,
                ],
                [
                    'id'         => 52,
                    'config_id'  => 1,
                    'modul'      => 'Informasi Publik',
                    'slug'       => 'informasi-publik',
                    'url'        => 'dokumen/clear',
                    'aktif'      => 1,
                    'ikon'       => 'fa-file-text',
                    'urut'       => 4,
                    'level'      => 4,
                    'hidden'     => 0,
                    'ikon_kecil' => '',
                    'parent'     => 15,
                ],
                [
                    'id'         => 200,
                    'config_id'  => 1,
                    'modul'      => 'Info [Desa]',
                    'slug'       => 'info-desa',
                    'url'        => '',
                    'aktif'      => 1,
                    'ikon'       => 'fa-dashboard',
                    'urut'       => 20,
                    'level'      => 2,
                    'hidden'     => 1,
                    'ikon_kecil' => 'fa fa-home',
                    'parent'     => 0,
                ],
                [
                    'id'         => 201,
                    'config_id'  => 1,
                    'modul'      => 'Keuangan',
                    'slug'       => 'keuangan',
                    'url'        => '',
                    'aktif'      => 1,
                    'ikon'       => 'fa-balance-scale',
                    'urut'       => 80,
                    'level'      => 2,
                    'hidden'     => 0,
                    'ikon_kecil' => 'fa-balance-scale',
                    'parent'     => 0,
                ],
                [
                    'id'         => 206,
                    'config_id'  => 1,
                    'modul'      => 'Kesehatan',
                    'slug'       => 'kesehatan',
                    'url'        => '',
                    'aktif'      => 1,
                    'ikon'       => 'fa-heartbeat',
                    'urut'       => 41,
                    'level'      => 2,
                    'hidden'     => 0,
                    'ikon_kecil' => 'fa fa-heartbeat',
                    'parent'     => 0,
                ],
                [
                    'id'         => 301,
                    'config_id'  => 1,
                    'modul'      => 'Buku Administrasi [Desa]',
                    'slug'       => 'buku-administrasi-desa',
                    'url'        => '',
                    'aktif'      => 1,
                    'ikon'       => 'fa-paste',
                    'urut'       => 70,
                    'level'      => 2,
                    'hidden'     => 0,
                    'ikon_kecil' => 'fa fa-paste',
                    'parent'     => 0,
                ],
                [
                    'id'         => 312,
                    'config_id'  => 1,
                    'modul'      => 'Anjungan',
                    'slug'       => 'anjungan',
                    'url'        => '',
                    'aktif'      => 1,
                    'ikon'       => 'fa-desktop',
                    'urut'       => 180,
                    'level'      => 2,
                    'hidden'     => 0,
                    'ikon_kecil' => '',
                    'parent'     => 0,
                ],
                [
                    'id'         => 337,
                    'config_id'  => 1,
                    'modul'      => 'Kehadiran',
                    'slug'       => 'kehadiran',
                    'url'        => '',
                    'aktif'      => 1,
                    'ikon'       => 'fa-calendar-check-o',
                    'urut'       => 41,
                    'level'      => 0,
                    'hidden'     => 0,
                    'ikon_kecil' => 'fa-calendar-check-o',
                    'parent'     => 0,
                ],
                [
                    'id'         => 343,
                    'config_id'  => 1,
                    'modul'      => 'OpenDK',
                    'slug'       => 'opendk',
                    'url'        => '',
                    'aktif'      => 1,
                    'ikon'       => 'fa-university',
                    'urut'       => 124,
                    'level'      => 2,
                    'hidden'     => 0,
                    'ikon_kecil' => 'fa-university',
                    'parent'     => 0,
                ],
                [
                    'id'         => 352,
                    'config_id'  => 1,
                    'modul'      => 'Satu Data',
                    'slug'       => 'satu-data',
                    'url'        => '',
                    'aktif'      => 1,
                    'ikon'       => 'fa-globe',
                    'urut'       => 180,
                    'level'      => 1,
                    'hidden'     => 0,
                    'ikon_kecil' => 'fa-globe',
                    'parent'     => 0,
                ],
                [
                    'id'         => 354,
                    'config_id'  => 1,
                    'modul'      => 'Buku Tamu',
                    'slug'       => 'buku-tamu',
                    'url'        => '',
                    'aktif'      => 1,
                    'ikon'       => 'fa-book',
                    'urut'       => 180,
                    'level'      => 2,
                    'hidden'     => 0,
                    'ikon_kecil' => 'fa-book',
                    'parent'     => 0,
                ],
            ]
        );
    }

    private function addDataMaster()
    {
        DB::table('analisis_ref_state')->insert([
            0 => ['id' => 1, 'nama' => 'Belum Entri / Pendataan'],
            1 => ['id' => 2, 'nama' => 'Sedang Dalam Pendataan'],
            2 => ['id' => 3, 'nama' => 'Selesai Entri / Pendataan'],
        ]);

        DB::table('analisis_ref_subjek')->insert([
            ['id' => 1, 'subjek' => 'Penduduk'],
            ['id' => 2, 'subjek' => 'Keluarga / KK'],
            ['id' => 3, 'subjek' => 'Rumah Tangga'],
            ['id' => 4, 'subjek' => 'Kelompok'],
            ['id' => 5, 'subjek' => 'Desa'],
            ['id' => 6, 'subjek' => 'Dusun'],
            ['id' => 7, 'subjek' => 'Rukun Warga (RW)'],
            ['id' => 8, 'subjek' => 'Rukun Tetangga (RT)'],
        ]);

        DB::table('analisis_tipe_indikator')->insert([
            ['id' => 1, 'tipe' => 'Pilihan (Tunggal)'],
            ['id' => 2, 'tipe' => 'Pilihan (Multivalue)'],
            ['id' => 3, 'tipe' => 'sian Angka'],
            ['id' => 4, 'tipe' => 'sian Tulisan'],
        ]);

        DB::table('ref_persil_kelas')->insert([
            [
                'id'    => 1,
                'tipe'  => 'BASAH',
                'kode'  => 'S-I',
                'ndesc' => 'Persawahan Dekat dengan Pemukiman',
            ],
            [
                'id'    => 2,
                'tipe'  => 'BASAH',
                'kode'  => 'S-II',
                'ndesc' => 'Persawahan Agak Dekat dengan Pemukiman',
            ],
            [
                'id'    => 3,
                'tipe'  => 'BASAH',
                'kode'  => 'S-III',
                'ndesc' => 'Persawahan Jauh dengan Pemukiman',
            ],
            [
                'id'    => 4,
                'tipe'  => 'BASAH',
                'kode'  => 'S-IV',
                'ndesc' => 'Persawahan Sangat Jauh dengan Pemukiman',
            ],
            [
                'id'    => 5,
                'tipe'  => 'KERING',
                'kode'  => 'D-I',
                'ndesc' => 'Lahan Kering Dekat dengan Pemukiman',
            ],
            [
                'id'    => 6,
                'tipe'  => 'KERING',
                'kode'  => 'D-II',
                'ndesc' => 'Lahan Kering Agak Dekat dengan Pemukiman',
            ],
            [
                'id'    => 7,
                'tipe'  => 'KERING',
                'kode'  => 'D-III',
                'ndesc' => 'Lahan Kering Jauh dengan Pemukiman',
            ],
            [
                'id'    => 8,
                'tipe'  => 'KERING',
                'kode'  => 'D-IV',
                'ndesc' => 'Lahan Kering Sanga Jauh dengan Pemukiman',
            ],
        ]);

        DB::table('ref_persil_mutasi')->insert([
            [
                'id'    => 1,
                'nama'  => 'Jual Beli',
                'ndesc' => 'Didapat dari proses Jual Beli',
            ],
            [
                'id'    => 2,
                'nama'  => 'Hibah',
                'ndesc' => 'Didapat dari proses Hibah',
            ],
            [
                'id'    => 3,
                'nama'  => 'Waris',
                'ndesc' => 'Didapat dari proses Waris',
            ],
        ]);

        DB::table('ref_status_covid')->insert([
            ['id' => 1, 'nama' => 'Kasus Suspek'],
            ['id' => 2, 'nama' => 'Kasus Probable'],
            ['id' => 3, 'nama' => 'Kasus Konfirmasi'],
            ['id' => 4, 'nama' => 'Kontak Erat'],
            ['id' => 5, 'nama' => 'Pelaku Perjalanan'],
            ['id' => 6, 'nama' => 'Discarded'],
            ['id' => 7, 'nama' => 'Selesai Isolasi'],
        ]);

        DB::table('ref_penduduk_bahasa')->insert([
            ['id' => 1, 'nama' => 'Latin', 'inisial' => 'L'],
            ['id' => 2, 'nama' => 'Daerah', 'inisial' => 'D'],
            ['id' => 3, 'nama' => 'Arab', 'inisial' => 'A'],
            ['id' => 4, 'nama' => 'Arab dan Latin', 'inisial' => 'AL'],
            ['id' => 5, 'nama' => 'Arab dan Daerah', 'inisial' => 'AD'],
            ['id' => 6, 'nama' => 'Arab, Latin dan Daerah', 'inisial' => 'ALD'],
        ]);

        DB::table('ref_penduduk_bidang')->insert([
            ['id' => 1, 'nama' => 'Service Komputer'],
            ['id' => 2, 'nama' => 'Operator Buldoser'],
            ['id' => 3, 'nama' => 'Operator Komputer'],
            ['id' => 4, 'nama' => 'Operator Genset'],
            ['id' => 5, 'nama' => 'Service HP'],
            ['id' => 6, 'nama' => 'Rias Pengantin'],
            ['id' => 7, 'nama' => 'Design Grafis'],
            ['id' => 8, 'nama' => 'Menjahit'],
            ['id' => 9, 'nama' => 'Menulis'],
            ['id' => 10, 'nama' => 'Reporter'],
            ['id' => 11, 'nama' => 'Sosial Media Manajer'],
            ['id' => 12, 'nama' => 'Manajemen Trainee'],
            ['id' => 13, 'nama' => 'Kasir'],
            ['id' => 14, 'nama' => 'HRD'],
            ['id' => 15, 'nama' => 'Guru'],
            ['id' => 16, 'nama' => 'Digital Marketing'],
            ['id' => 17, 'nama' => 'Customer Services'],
            ['id' => 18, 'nama' => 'Welder'],
            ['id' => 19, 'nama' => 'Mekanik Alat Berat'],
            ['id' => 20, 'nama' => 'Teknisi Listrik'],
            ['id' => 21, 'nama' => 'Internet Marketing'],
        ]);

        DB::table('ref_penduduk_hamil')->insert([
            ['id' => 1, 'nama' => 'Hamil'],
            ['id' => 2, 'nama' => 'Tidak Hamil'],
        ]);

        DB::table('ref_penduduk_kursus')->insert([
            ['id' => 1, 'nama' => 'Kursus Komputer'],
            ['id' => 2, 'nama' => 'Kursus Menjahit'],
            ['id' => 3, 'nama' => 'Pelatihan Kelistrikan'],
            ['id' => 4, 'nama' => 'Kursus Mekanik Motor'],
            ['id' => 5, 'nama' => 'Pelatihan Security'],
            ['id' => 6, 'nama' => 'Kursus Otomotif'],
            ['id' => 7, 'nama' => 'Kursus Bahasa Inggris'],
            ['id' => 8, 'nama' => 'Kursus Tata Kecantikan Kulit'],
            ['id' => 9, 'nama' => 'Kursus Megemudi'],
            ['id' => 10, 'nama' => 'Kursus Tata Boga'],
            ['id' => 11, 'nama' => 'Kursus Meubeler'],
            ['id' => 12, 'nama' => 'Kursus Las'],
            ['id' => 13, 'nama' => 'Kursus Sablon'],
            ['id' => 14, 'nama' => 'Kursus Penerbangan'],
            ['id' => 15, 'nama' => 'Kursus Desain Interior'],
            ['id' => 16, 'nama' => 'Kursus Teknisi HP'],
            ['id' => 17, 'nama' => 'Kursus Garment'],
            ['id' => 18, 'nama' => 'Kursus Akupuntur'],
            ['id' => 19, 'nama' => 'Kursus Senam'],
            ['id' => 20, 'nama' => 'Kursus Pendidik PAUD'],
            ['id' => 21, 'nama' => 'Kursus Baby Sitter'],
            ['id' => 22, 'nama' => 'Kursus Desain Grafis'],
            ['id' => 23, 'nama' => 'Kursus Bahasa Indonesia'],
            ['id' => 24, 'nama' => 'Kursus Photografi'],
            ['id' => 25, 'nama' => 'Kursus Expor Impor'],
            ['id' => 26, 'nama' => 'Kursus Jurnalistik'],
            ['id' => 27, 'nama' => 'Kursus Bahasa Arab'],
            ['id' => 28, 'nama' => 'Kursus Bahasa Jepang'],
            ['id' => 29, 'nama' => 'Kursus Anak Buah Kapal'],
            ['id' => 30, 'nama' => 'Kursus Refleksi'],
            ['id' => 31, 'nama' => 'Kursus Akupuntur'],
            ['id' => 32, 'nama' => 'Kursus Perhotelan'],
            ['id' => 33, 'nama' => 'Kursus Tata Rias'],
            ['id' => 34, 'nama' => 'Kursus Administrasi Perkantoran'],
            ['id' => 35, 'nama' => 'Kursus Broadcasting'],
            ['id' => 36, 'nama' => 'Kursus Kerajinan Tangan'],
            ['id' => 37, 'nama' => 'Kursus Sosial Media Marketing'],
            ['id' => 38, 'nama' => 'Kursus Internet Marketing'],
            ['id' => 39, 'nama' => 'Kursus Sekretaris'],
            ['id' => 40, 'nama' => 'Kursus Perpajakan'],
            ['id' => 41, 'nama' => 'Kursus Publik Speaking'],
            ['id' => 42, 'nama' => 'Kursus Publik Relation'],
            ['id' => 43, 'nama' => 'Kursus Batik'],
            ['id' => 44, 'nama' => 'Kursus Pengobatan Tradisional'],
        ]);

        DB::table('ref_peristiwa')->insert([
            ['id' => 1, 'nama' => 'Lahir'],
            ['id' => 2, 'nama' => 'Mati'],
            ['id' => 3, 'nama' => 'Pindah Keluar'],
            ['id' => 4, 'nama' => 'Hilang'],
            ['id' => 5, 'nama' => 'Pindah Masuk'],
            ['id' => 6, 'nama' => 'Pergi'],
        ]);

        DB::table('ref_pindah')->insert([
            ['id' => 1, 'nama' => 'Pindah keluar Desa/Kelurahan'],
            ['id' => 2, 'nama' => 'Pindah keluar Kecamatan'],
            ['id' => 3, 'nama' => 'Pindah keluar Kabupaten/Kota'],
            ['id' => 4, 'nama' => 'Pindah keluar Provinsi'],
        ]);

        DB::table('tweb_cacat')->insert([
            ['id' => 1, 'nama' => 'CACAT FISIK'],
            ['id' => 2, 'nama' => 'CACAT NETRA/BUTA'],
            ['id' => 3, 'nama' => 'CACAT RUNGU/WICARA'],
            ['id' => 4, 'nama' => 'CACAT MENTAL/JIWA'],
            ['id' => 5, 'nama' => 'CACAT FISIK DAN MENTAL'],
            ['id' => 6, 'nama' => 'CACAT LAINNYA'],
            ['id' => 7, 'nama' => 'TIDAK CACAT'],
        ]);

        DB::table('tweb_cara_kb')->insert([
            ['id' => 1, 'nama' => 'Pil', 'sex' => 2],
            ['id' => 2, 'nama' => 'IUD', 'sex' => 2],
            ['id' => 3, 'nama' => 'Suntik', 'sex' => 2],
            ['id' => 4, 'nama' => 'Kondom', 'sex' => 1],
            ['id' => 5, 'nama' => 'Susuk KB', 'sex' => 2],
            ['id' => 6, 'nama' => 'Sterilisasi Wanita', 'sex' => 2],
            ['id' => 7, 'nama' => 'Sterilisasi Pria', 'sex' => 1],
            ['id' => 99, 'nama' => 'Lainnya', 'sex' => 3],
        ]);

        DB::table('tweb_golongan_darah')->insert([
            ['id' => 1, 'nama' => 'A'],
            ['id' => 2, 'nama' => 'B'],
            ['id' => 3, 'nama' => 'AB'],
            ['id' => 4, 'nama' => 'O'],
            ['id' => 5, 'nama' => 'A+'],
            ['id' => 6, 'nama' => 'A-'],
            ['id' => 7, 'nama' => 'B+'],
            ['id' => 8, 'nama' => 'B-'],
            ['id' => 9, 'nama' => 'AB+'],
            ['id' => 10, 'nama' => 'AB-'],
            ['id' => 11, 'nama' => 'O+'],
            ['id' => 12, 'nama' => 'O-'],
            ['id' => 13, 'nama' => 'TIDAK TAHU'],
        ]);

        DB::table('tweb_penduduk_agama')->insert([
            ['id' => 1, 'nama' => 'ISLAM'],
            ['id' => 2, 'nama' => 'KRISTEN'],
            ['id' => 3, 'nama' => 'KATHOLIK'],
            ['id' => 4, 'nama' => 'HINDU'],
            ['id' => 5, 'nama' => 'BUDHA'],
            ['id' => 6, 'nama' => 'KHONGHUCU'],
            ['id' => 7, 'nama' => 'Kepercayaan Terhadap Tuhan YME / Lainnya'],
        ]);
        DB::table('tweb_penduduk_asuransi')->insert([
            ['id' => 1, 'nama' => 'Tidak/Belum Punya'],
            ['id' => 2, 'nama' => 'BPJS Penerima Bantuan Iuran'],
            ['id' => 3, 'nama' => 'BPJS Non Penerima Bantuan Iuran'],
            ['id' => 99, 'nama' => 'Asuransi Lainnya'],
        ]);

        DB::table('tweb_penduduk_hubungan')->insert([
            ['id' => 1, 'nama' => 'KEPALA KELUARGA'],
            ['id' => 2, 'nama' => 'SUAMI'],
            ['id' => 3, 'nama' => 'ISTRI'],
            ['id' => 4, 'nama' => 'ANAK'],
            ['id' => 5, 'nama' => 'MENANTU'],
            ['id' => 6, 'nama' => 'CUCU'],
            ['id' => 7, 'nama' => 'ORANGTUA'],
            ['id' => 8, 'nama' => 'MERTUA'],
            ['id' => 9, 'nama' => 'FAMILI LAIN'],
            ['id' => 10, 'nama' => 'PEMBANTU'],
            ['id' => 11, 'nama' => 'LAINNYA'],
        ]);
        DB::table('tweb_penduduk_kawin')->insert([
            ['id' => 1, 'nama' => 'BELUM KAWIN'],
            ['id' => 2, 'nama' => 'KAWIN'],
            ['id' => 3, 'nama' => 'CERAI HIDUP'],
            ['id' => 4, 'nama' => 'CERAI MATI'],
        ]);

        DB::table('tweb_penduduk_pekerjaan')->insert([
            ['id' => 1, 'nama' => 'BELUM/TIDAK BEKERJA'],
            ['id' => 2, 'nama' => 'MENGURUS RUMAH TANGGA'],
            ['id' => 3, 'nama' => 'PELAJAR/MAHASISWA'],
            ['id' => 4, 'nama' => 'PENSIUNAN'],
            ['id' => 5, 'nama' => 'PEGAWAI NEGERI SIPIL (PNS)'],
            ['id' => 6, 'nama' => 'TENTARA NASIONAL INDONESIA (TNI)'],
            ['id' => 7, 'nama' => 'KEPOLISIAN RI (POLRI)'],
            ['id' => 8, 'nama' => 'PERDAGANGAN'],
            ['id' => 9, 'nama' => 'PETANI/PEKEBUN'],
            ['id' => 10, 'nama' => 'PETERNAK'],
            ['id' => 11, 'nama' => 'NELAYAN/PERIKANAN'],
            ['id' => 12, 'nama' => 'INDUSTRI'],
            ['id' => 13, 'nama' => 'KONSTRUKSI'],
            ['id' => 14, 'nama' => 'TRANSPORTASI'],
            ['id' => 15, 'nama' => 'KARYAWAN SWASTA'],
            ['id' => 16, 'nama' => 'KARYAWAN BUMN'],
            ['id' => 17, 'nama' => 'KARYAWAN BUMD'],
            ['id' => 18, 'nama' => 'KARYAWAN HONORER'],
            ['id' => 19, 'nama' => 'BURUH HARIAN LEPAS'],
            ['id' => 20, 'nama' => 'BURUH TANI/PERKEBUNAN'],
            ['id' => 21, 'nama' => 'BURUH NELAYAN/PERIKANAN'],
            ['id' => 22, 'nama' => 'BURUH PETERNAKAN'],
            ['id' => 23, 'nama' => 'PEMBANTU RUMAH TANGGA'],
            ['id' => 24, 'nama' => 'TUKANG CUKUR'],
            ['id' => 25, 'nama' => 'TUKANG LISTRIK'],
            ['id' => 26, 'nama' => 'TUKANG BATU'],
            ['id' => 27, 'nama' => 'TUKANG KAYU'],
            ['id' => 28, 'nama' => 'TUKANG SOL SEPATU'],
            ['id' => 29, 'nama' => 'TUKANG LAS/PANDAI BESI'],
            ['id' => 30, 'nama' => 'TUKANG JAHIT'],
            ['id' => 31, 'nama' => 'TUKANG GIGI'],
            ['id' => 32, 'nama' => 'PENATA RIAS'],
            ['id' => 33, 'nama' => 'PENATA BUSANA'],
            ['id' => 34, 'nama' => 'PENATA RAMBUT'],
            ['id' => 35, 'nama' => 'MEKANIK'],
            ['id' => 36, 'nama' => 'SENIMAN'],
            ['id' => 37, 'nama' => 'TABIB'],
            ['id' => 38, 'nama' => 'PARAJI'],
            ['id' => 39, 'nama' => 'PERANCANG BUSANA'],
            ['id' => 40, 'nama' => 'PENTERJEMAH'],
            ['id' => 41, 'nama' => 'IMAM MASJID'],
            ['id' => 42, 'nama' => 'PENDETA'],
            ['id' => 43, 'nama' => 'PASTOR'],
            ['id' => 44, 'nama' => 'WARTAWAN'],
            ['id' => 45, 'nama' => 'USTADZ/MUBALIGH'],
            ['id' => 46, 'nama' => 'JURU MASAK'],
            ['id' => 47, 'nama' => 'PROMOTOR ACARA'],
            ['id' => 48, 'nama' => 'ANGGOTA DPR-RI'],
            ['id' => 49, 'nama' => 'ANGGOTA DPD'],
            ['id' => 50, 'nama' => 'ANGGOTA BPK'],
            ['id' => 51, 'nama' => 'PRESIDEN'],
            ['id' => 52, 'nama' => 'WAKIL PRESIDEN'],
            ['id' => 53, 'nama' => 'ANGGOTA MAHKAMAH KONSTITUSI'],
            ['id' => 54, 'nama' => 'ANGGOTA KABINET KEMENTERIAN'],
            ['id' => 55, 'nama' => 'DUTA BESAR'],
            ['id' => 56, 'nama' => 'GUBERNUR'],
            ['id' => 57, 'nama' => 'WAKIL GUBERNUR'],
            ['id' => 58, 'nama' => 'BUPATI'],
            ['id' => 59, 'nama' => 'WAKIL BUPATI'],
            ['id' => 60, 'nama' => 'WALIKOTA'],
            ['id' => 61, 'nama' => 'WAKIL WALIKOTA'],
            ['id' => 62, 'nama' => 'ANGGOTA DPRD PROVINSI'],
            ['id' => 63, 'nama' => 'ANGGOTA DPRD KABUPATEN/KOTA'],
            ['id' => 64, 'nama' => 'DOSEN'],
            ['id' => 65, 'nama' => 'GURU'],
            ['id' => 66, 'nama' => 'PILOT'],
            ['id' => 67, 'nama' => 'PENGACARA'],
            ['id' => 68, 'nama' => 'NOTARIS'],
            ['id' => 69, 'nama' => 'ARSITEK'],
            ['id' => 70, 'nama' => 'AKUNTAN'],
            ['id' => 71, 'nama' => 'KONSULTAN'],
            ['id' => 72, 'nama' => 'DOKTER'],
            ['id' => 73, 'nama' => 'BIDAN'],
            ['id' => 74, 'nama' => 'PERAWAT'],
            ['id' => 75, 'nama' => 'APOTEKER'],
            ['id' => 76, 'nama' => 'PSIKIATER/PSIKOLOG'],
            ['id' => 77, 'nama' => 'PENYIAR TELEVISI'],
            ['id' => 78, 'nama' => 'PENYIAR RADIO'],
            ['id' => 79, 'nama' => 'PELAUT'],
            ['id' => 80, 'nama' => 'PENELITI'],
            ['id' => 81, 'nama' => 'SOPIR'],
            ['id' => 82, 'nama' => 'PIALANG'],
            ['id' => 83, 'nama' => 'PARANORMAL'],
            ['id' => 84, 'nama' => 'PEDAGANG'],
            ['id' => 85, 'nama' => 'PERANGKAT DESA'],
            ['id' => 86, 'nama' => 'KEPALA DESA'],
            ['id' => 87, 'nama' => 'BIARAWATI'],
            ['id' => 88, 'nama' => 'WIRASWASTA'],
            ['id' => 89, 'nama' => 'LAINNYA'],
        ]);

        DB::table('tweb_penduduk_pendidikan')->insert([
            ['id' => 1, 'nama' => 'BELUM MASUK TK/KELOMPOK BERMAIN'],
            ['id' => 2, 'nama' => 'SEDANG TK/KELOMPOK BERMAIN'],
            ['id' => 3, 'nama' => 'TIDAK PERNAH SEKOLAH'],
            ['id' => 4, 'nama' => 'SEDANG SD/SEDERAJAT'],
            ['id' => 5, 'nama' => 'TIDAK TAMAT SD/SEDERAJAT'],
            ['id' => 6, 'nama' => 'SEDANG SLTP/SEDERAJAT'],
            ['id' => 7, 'nama' => 'SEDANG SLTA/SEDERAJAT'],
            ['id' => 8, 'nama' => 'SEDANG  D-1/SEDERAJAT'],
            ['id' => 9, 'nama' => 'SEDANG D-2/SEDERAJAT'],
            ['id' => 10, 'nama' => 'SEDANG D-3/SEDERAJAT'],
            ['id' => 11, 'nama' => 'SEDANG  S-1/SEDERAJAT'],
            ['id' => 12, 'nama' => 'SEDANG S-2/SEDERAJAT'],
            ['id' => 13, 'nama' => 'SEDANG S-3/SEDERAJAT'],
            ['id' => 14, 'nama' => 'SEDANG SLB A/SEDERAJAT'],
            ['id' => 15, 'nama' => 'SEDANG SLB B/SEDERAJAT'],
            ['id' => 16, 'nama' => 'SEDANG SLB C/SEDERAJAT'],
            [
                'id'   => 17,
                'nama' => 'TIDAK DAPAT MEMBACA DAN MENULIS HURUF LATIN/ARAB',
            ],
            ['id' => 18, 'nama' => 'TIDAK SEDANG SEKOLAH'],
        ]);

        DB::table('tweb_penduduk_pendidikan_kk')->insert([
            ['id' => 1, 'nama' => 'TIDAK / BELUM SEKOLAH'],
            ['id' => 2, 'nama' => 'BELUM TAMAT SD/SEDERAJAT'],
            ['id' => 3, 'nama' => 'TAMAT SD / SEDERAJAT'],
            ['id' => 4, 'nama' => 'SLTP/SEDERAJAT'],
            ['id' => 5, 'nama' => 'SLTA / SEDERAJAT'],
            ['id' => 6, 'nama' => 'DIPLOMA I / II'],
            ['id' => 7, 'nama' => 'AKADEMI/ DIPLOMA III/S. MUDA'],
            ['id' => 8, 'nama' => 'DIPLOMA IV/ STRATA I'],
            ['id' => 9, 'nama' => 'STRATA II'],
            ['id' => 10, 'nama' => 'STRATA III'],
        ]);

        DB::table('tweb_penduduk_sex')->insert([
            ['id' => 1, 'nama' => 'LAKI-LAKI'],
            ['id' => 2, 'nama' => 'PEREMPUAN'],
        ]);

        DB::table('tweb_penduduk_status')->insert([
            ['id' => 1, 'nama' => 'TETAP'],
            ['id' => 2, 'nama' => 'TIDAK TETAP'],
        ]);

        // DB::table('tweb_penduduk_umur')->insert(); ikut data awal

        DB::table('tweb_penduduk_warganegara')->insert([
            ['id' => 1, 'nama' => 'WNI'],
            ['id' => 2, 'nama' => 'WNA'],
            ['id' => 3, 'nama' => 'DUA KEWARGANEGARAAN'],
        ]);

        DB::table('tweb_rtm_hubungan')->insert([
            ['id' => 1, 'nama' => 'Kepala Rumah Tangga'],
            ['id' => 2, 'nama' => 'Anggota'],
        ]);

        DB::table('tweb_sakit_menahun')->insert([
            ['id' => 1, 'nama' => 'JANTUNG'],
            ['id' => 2, 'nama' => 'LEVER'],
            ['id' => 3, 'nama' => 'PARU-PARU'],
            ['id' => 4, 'nama' => 'KANKER'],
            ['id' => 5, 'nama' => 'STROKE'],
            ['id' => 6, 'nama' => 'DIABETES MELITUS'],
            ['id' => 7, 'nama' => 'GINJAL'],
            ['id' => 8, 'nama' => 'MALARIA'],
            ['id' => 9, 'nama' => 'LEPRA/KUSTA'],
            ['id' => 10, 'nama' => 'HIV/AIDS'],
            ['id' => 11, 'nama' => 'GILA/STRESS'],
            ['id' => 12, 'nama' => 'TBC'],
            ['id' => 13, 'nama' => 'ASTHMA'],
            ['id' => 14, 'nama' => 'TIDAK ADA/TIDAK SAKIT'],
        ]);

        DB::table('tweb_status_dasar')->insert([
            ['id' => 1, 'nama' => 'HIDUP'],
            ['id' => 2, 'nama' => 'MATI'],
            ['id' => 3, 'nama' => 'PINDAH'],
            ['id' => 4, 'nama' => 'HILANG'],
            ['id' => 6, 'nama' => 'PERGI'],
            ['id' => 9, 'nama' => 'TIDAK VALID'],
        ]);

        DB::table('tweb_status_ktp')->insert([
            [
                'id'           => 1,
                'nama'         => 'BELUM REKAM',
                'ktp_el'       => 1,
                'status_rekam' => '2',
            ],
            [
                'id'           => 2,
                'nama'         => 'SUDAH REKAM',
                'ktp_el'       => 2,
                'status_rekam' => '3',
            ],
            [
                'id'           => 3,
                'nama'         => 'CARD PRINTED',
                'ktp_el'       => 2,
                'status_rekam' => '4',
            ],
            [
                'id'           => 4,
                'nama'         => 'PRINT READY RECORD',
                'ktp_el'       => 2,
                'status_rekam' => '5',
            ],
            [
                'id'           => 5,
                'nama'         => 'CARD SHIPPED',
                'ktp_el'       => 2,
                'status_rekam' => '6',
            ],
            [
                'id'           => 6,
                'nama'         => 'SENT FOR CARD PRINTING',
                'ktp_el'       => 2,
                'status_rekam' => '7',
            ],
            [
                'id'           => 7,
                'nama'         => 'CARD ISSUED',
                'ktp_el'       => 2,
                'status_rekam' => '8',
            ],
            [
                'id'           => 8,
                'nama'         => 'BELUM WAJIB',
                'ktp_el'       => 1,
                'status_rekam' => '1',
            ],
        ]);

        DB::table('ref_dokumen')->insert([
            ['id' => 1, 'nama' => 'Informasi Publik'],
            ['id' => 2, 'nama' => 'SK Kades'],
            ['id' => 3, 'nama' => 'Perdes'],
        ]);

        DB::table('ref_asal_tanah_kas')->insert([
            ['id' => 1, 'nama' => 'Jual Beli'],
            ['id' => 2, 'nama' => 'Hibah / Sumbangan'],
            ['id' => 3, 'nama' => 'Lain - lain'],
        ]);

        DB::table('ref_peruntukan_tanah_kas')->insert([
            ['id' => 1, 'nama' => 'Sewa'],
            ['id' => 2, 'nama' => 'Pinjam Pakai'],
            ['id' => 3, 'nama' => 'Kerjasama Pemanfaatan'],
            ['id' => 4, 'nama' => 'Bangun Guna Serah atau Bangun Serah Guna'],
        ]);

        DB::table('keuangan_manual_ref_bidang')->insert([
            [
                'id'          => 1,
                'Kd_Bid'      => '00.0000.01',
                'Nama_Bidang' => 'BIDANG PENYELENGGARAN PEMERINTAHAN DESA',
            ],
            [
                'id'          => 2,
                'Kd_Bid'      => '00.0000.02',
                'Nama_Bidang' => 'BIDANG PELAKSANAAN PEMBANGUNAN DESA',
            ],
            [
                'id'          => 3,
                'Kd_Bid'      => '00.0000.03',
                'Nama_Bidang' => 'BIDANG PEMBINAAN KEMASYARAKATAN DESA',
            ],
            [
                'id'          => 4,
                'Kd_Bid'      => '00.0000.04',
                'Nama_Bidang' => 'BIDANG PEMBERDAYAAN MASYARAKAT DESA',
            ],
            [
                'id'          => 5,
                'Kd_Bid'      => '00.0000.05',
                'Nama_Bidang' => 'BIDANG PENANGGULANGAN BENCANA, DARURAT DAN MENDESAK DESA',
            ],
        ]);

        DB::table('keuangan_manual_ref_rek1')->insert([
            ['id' => 1, 'Akun' => '1.', 'Nama_Akun' => 'ASET'],
            ['id' => 2, 'Akun' => '2.', 'Nama_Akun' => 'KEWAJIBAN'],
            ['id' => 3, 'Akun' => '3.', 'Nama_Akun' => 'EKUITAS'],
            ['id' => 4, 'Akun' => '4.', 'Nama_Akun' => 'PENDAPATAN'],
            ['id' => 5, 'Akun' => '5.', 'Nama_Akun' => 'BELANJA'],
            ['id' => 6, 'Akun' => '6.', 'Nama_Akun' => 'PEMBIAYAAN'],
            ['id' => 7, 'Akun' => '7.', 'Nama_Akun' => 'NON ANGGARAN'],
        ]);

        DB::table('keuangan_manual_ref_rek2')->insert(
            [
                [
                    'id'            => 1,
                    'Akun'          => '1.',
                    'Kelompok'      => '1.1.',
                    'Nama_Kelompok' => 'Aset Lancar',
                ],
                [
                    'id'            => 2,
                    'Akun'          => '1.',
                    'Kelompok'      => '1.2.',
                    'Nama_Kelompok' => 'Investasi',
                ],
                [
                    'id'            => 3,
                    'Akun'          => '1.',
                    'Kelompok'      => '1.3.',
                    'Nama_Kelompok' => 'Aset Tetap',
                ],
                [
                    'id'            => 4,
                    'Akun'          => '1.',
                    'Kelompok'      => '1.4.',
                    'Nama_Kelompok' => 'Dana Cadangan',
                ],
                [
                    'id'            => 5,
                    'Akun'          => '1.',
                    'Kelompok'      => '1.5.',
                    'Nama_Kelompok' => 'Aset Tidak Lancar Lainnya',
                ],
                [
                    'id'            => 6,
                    'Akun'          => '2.',
                    'Kelompok'      => '2.1.',
                    'Nama_Kelompok' => 'Kewajiban Jangka Pendek',
                ],
                [
                    'id'            => 7,
                    'Akun'          => '3.',
                    'Kelompok'      => '3.1.',
                    'Nama_Kelompok' => 'Ekuitas',
                ],
                [
                    'id'            => 8,
                    'Akun'          => '4.',
                    'Kelompok'      => '4.1.',
                    'Nama_Kelompok' => 'Pendapatan Asli Desa',
                ],
                [
                    'id'            => 9,
                    'Akun'          => '4.',
                    'Kelompok'      => '4.2.',
                    'Nama_Kelompok' => 'Pendapatan Transfer',
                ],
                [
                    'id'            => 10,
                    'Akun'          => '4.',
                    'Kelompok'      => '4.3.',
                    'Nama_Kelompok' => 'Pendapatan Lain-lain',
                ],
                [
                    'id'            => 11,
                    'Akun'          => '5.',
                    'Kelompok'      => '5.1.',
                    'Nama_Kelompok' => 'Belanja Pegawai',
                ],
                [
                    'id'            => 12,
                    'Akun'          => '5.',
                    'Kelompok'      => '5.2.',
                    'Nama_Kelompok' => 'Belanja Barang dan Jasa',
                ],
                [
                    'id'            => 13,
                    'Akun'          => '5.',
                    'Kelompok'      => '5.3.',
                    'Nama_Kelompok' => 'Belanja Modal',
                ],
                [
                    'id'            => 14,
                    'Akun'          => '5.',
                    'Kelompok'      => '5.4.',
                    'Nama_Kelompok' => 'Belanja Tidak Terduga',
                ],
                [
                    'id'            => 15,
                    'Akun'          => '6.',
                    'Kelompok'      => '6.1.',
                    'Nama_Kelompok' => 'Penerimaan Pembiayaan',
                ],
                [
                    'id'            => 16,
                    'Akun'          => '6.',
                    'Kelompok'      => '6.2.',
                    'Nama_Kelompok' => 'Pengeluaran Pembiayaan',
                ],
                [
                    'id'            => 17,
                    'Akun'          => '7.',
                    'Kelompok'      => '7.1.',
                    'Nama_Kelompok' => 'Perhitungan Fihak Ketiga',
                ],
            ]
        );

        DB::table('keuangan_manual_ref_rek3')->insert(
            [
                [
                    'id'         => 1,
                    'Kelompok'   => '1.1.',
                    'Jenis'      => '1.1.1.',
                    'Nama_Jenis' => 'Kas dan Bank',
                ],
                [
                    'id'         => 2,
                    'Kelompok'   => '1.1.',
                    'Jenis'      => '1.1.2.',
                    'Nama_Jenis' => 'Piutang',
                ],
                [
                    'id'         => 3,
                    'Kelompok'   => '1.1.',
                    'Jenis'      => '1.1.3.',
                    'Nama_Jenis' => 'Persediaan',
                ],
                [
                    'id'         => 4,
                    'Kelompok'   => '1.2.',
                    'Jenis'      => '1.2.1.',
                    'Nama_Jenis' => 'Penyertaan Modal Pemerintah Desa',
                ],
                [
                    'id'         => 5,
                    'Kelompok'   => '1.3.',
                    'Jenis'      => '1.3.1.',
                    'Nama_Jenis' => 'Tanah',
                ],
                [
                    'id'         => 6,
                    'Kelompok'   => '1.3.',
                    'Jenis'      => '1.3.2.',
                    'Nama_Jenis' => 'Peralatan dan Mesin',
                ],
                [
                    'id'         => 7,
                    'Kelompok'   => '1.3.',
                    'Jenis'      => '1.3.3.',
                    'Nama_Jenis' => 'Gedung dan Bangunan',
                ],
                [
                    'id'         => 8,
                    'Kelompok'   => '1.3.',
                    'Jenis'      => '1.3.4.',
                    'Nama_Jenis' => 'Jalan, Irigasi dan Jaringan',
                ],
                [
                    'id'         => 9,
                    'Kelompok'   => '1.3.',
                    'Jenis'      => '1.3.5.',
                    'Nama_Jenis' => 'Aset Tetap Lainnya',
                ],
                [
                    'id'         => 10,
                    'Kelompok'   => '1.3.',
                    'Jenis'      => '1.3.6.',
                    'Nama_Jenis' => 'Konstruksi Dalam Pengerjaan',
                ],
                [
                    'id'         => 11,
                    'Kelompok'   => '1.3.',
                    'Jenis'      => '1.3.7.',
                    'Nama_Jenis' => 'Aset Tak Berwujud',
                ],
                [
                    'id'         => 12,
                    'Kelompok'   => '1.3.',
                    'Jenis'      => '1.3.8.',
                    'Nama_Jenis' => 'Akumulasi Penyusutan Aktiva Tetap',
                ],
                [
                    'id'         => 13,
                    'Kelompok'   => '1.4.',
                    'Jenis'      => '1.4.1.',
                    'Nama_Jenis' => 'Dana Cadangan',
                ],
                [
                    'id'         => 14,
                    'Kelompok'   => '1.5.',
                    'Jenis'      => '1.5.1.',
                    'Nama_Jenis' => 'Tagihan Piutang Penjualan Angsuran',
                ],
                [
                    'id'         => 15,
                    'Kelompok'   => '1.5.',
                    'Jenis'      => '1.5.2.',
                    'Nama_Jenis' => 'Tagihan Tuntutan Ganti Kerugian Daerah',
                ],
                [
                    'id'         => 16,
                    'Kelompok'   => '1.5.',
                    'Jenis'      => '1.5.3.',
                    'Nama_Jenis' => 'Kemitraan dengan Pihak Ketiga',
                ],
                [
                    'id'         => 17,
                    'Kelompok'   => '1.5.',
                    'Jenis'      => '1.5.4.',
                    'Nama_Jenis' => 'Aktiva Tidak Berwujud',
                ],
                [
                    'id'         => 18,
                    'Kelompok'   => '1.5.',
                    'Jenis'      => '1.5.5.',
                    'Nama_Jenis' => 'Aset Lain-lain',
                ],
                [
                    'id'         => 19,
                    'Kelompok'   => '2.1.',
                    'Jenis'      => '2.1.1.',
                    'Nama_Jenis' => 'Hutang Perhitungan Pihak Ketiga',
                ],
                [
                    'id'         => 20,
                    'Kelompok'   => '2.1.',
                    'Jenis'      => '2.1.2.',
                    'Nama_Jenis' => 'Hutang Bunga',
                ],
                [
                    'id'         => 21,
                    'Kelompok'   => '2.1.',
                    'Jenis'      => '2.1.3.',
                    'Nama_Jenis' => 'Hutang Pajak',
                ],
                [
                    'id'         => 22,
                    'Kelompok'   => '2.1.',
                    'Jenis'      => '2.1.4.',
                    'Nama_Jenis' => 'Pendapatan Diterima Dimuka',
                ],
                [
                    'id'         => 23,
                    'Kelompok'   => '2.1.',
                    'Jenis'      => '2.1.5.',
                    'Nama_Jenis' => 'Bagian Lancar Hutang Jangka Panjang',
                ],
                [
                    'id'         => 24,
                    'Kelompok'   => '2.1.',
                    'Jenis'      => '2.1.6.',
                    'Nama_Jenis' => 'Hutang Jangka Pendek Lainnya',
                ],
                [
                    'id'         => 25,
                    'Kelompok'   => '3.1.',
                    'Jenis'      => '3.1.1.',
                    'Nama_Jenis' => 'Ekuitas',
                ],
                [
                    'id'         => 26,
                    'Kelompok'   => '3.1.',
                    'Jenis'      => '3.1.2.',
                    'Nama_Jenis' => 'Ekuitas SAL',
                ],
                [
                    'id'         => 27,
                    'Kelompok'   => '4.1.',
                    'Jenis'      => '4.1.1.',
                    'Nama_Jenis' => 'Hasil Usaha Desa',
                ],
                [
                    'id'         => 28,
                    'Kelompok'   => '4.1.',
                    'Jenis'      => '4.1.2.',
                    'Nama_Jenis' => 'Hasil Aset Desa',
                ],
                [
                    'id'         => 29,
                    'Kelompok'   => '4.1.',
                    'Jenis'      => '4.1.3.',
                    'Nama_Jenis' => 'Swadaya, Partisipasi dan Gotong Royong',
                ],
                [
                    'id'         => 30,
                    'Kelompok'   => '4.1.',
                    'Jenis'      => '4.1.4.',
                    'Nama_Jenis' => 'Lain-Lain Pendapatan Asli Desa',
                ],
                [
                    'id'         => 31,
                    'Kelompok'   => '4.2.',
                    'Jenis'      => '4.2.1.',
                    'Nama_Jenis' => 'Dana Desa',
                ],
                [
                    'id'         => 32,
                    'Kelompok'   => '4.2.',
                    'Jenis'      => '4.2.2.',
                    'Nama_Jenis' => 'Bagi Hasil Pajak dan Retribusi',
                ],
                [
                    'id'         => 33,
                    'Kelompok'   => '4.2.',
                    'Jenis'      => '4.2.3.',
                    'Nama_Jenis' => 'Alokasi Dana Desa',
                ],
                [
                    'id'         => 34,
                    'Kelompok'   => '4.2.',
                    'Jenis'      => '4.2.4.',
                    'Nama_Jenis' => 'Bantuan Keuangan Provinsi',
                ],
                [
                    'id'         => 35,
                    'Kelompok'   => '4.2.',
                    'Jenis'      => '4.2.5.',
                    'Nama_Jenis' => 'Bantuan Keuangan Kabupaten/Kota',
                ],
                [
                    'id'         => 36,
                    'Kelompok'   => '4.3.',
                    'Jenis'      => '4.3.1.',
                    'Nama_Jenis' => 'Penerimaan dari Hasil Kerjasama Antar Desa',
                ],
                [
                    'id'         => 37,
                    'Kelompok'   => '4.3.',
                    'Jenis'      => '4.3.2.',
                    'Nama_Jenis' => 'Penerimaan dari Hasil Kerjasama dengan Pihak Ketiga',
                ],
                [
                    'id'         => 38,
                    'Kelompok'   => '4.3.',
                    'Jenis'      => '4.3.3.',
                    'Nama_Jenis' => 'Penerimaan Bantuan dari Perusahaan yang Berlokasi di Desa',
                ],
                [
                    'id'         => 39,
                    'Kelompok'   => '4.3.',
                    'Jenis'      => '4.3.4.',
                    'Nama_Jenis' => 'Hibah dan Sumbangan dari Pihak Ketiga',
                ],
                [
                    'id'         => 40,
                    'Kelompok'   => '4.3.',
                    'Jenis'      => '4.3.5.',
                    'Nama_Jenis' => 'Koreksi Kesalahan Belanja Tahun-tahun Sebelumnya',
                ],
                [
                    'id'         => 41,
                    'Kelompok'   => '4.3.',
                    'Jenis'      => '4.3.6.',
                    'Nama_Jenis' => 'Bunga Bank',
                ],
                [
                    'id'         => 42,
                    'Kelompok'   => '4.3.',
                    'Jenis'      => '4.3.9.',
                    'Nama_Jenis' => 'Lain-lain Pendapatan Desa Yang Sah',
                ],
                [
                    'id'         => 43,
                    'Kelompok'   => '5.1.',
                    'Jenis'      => '5.1.1.',
                    'Nama_Jenis' => 'Penghasilan Tetap dan Tunjangan Kepala Desa',
                ],
                [
                    'id'         => 44,
                    'Kelompok'   => '5.1.',
                    'Jenis'      => '5.1.2.',
                    'Nama_Jenis' => 'Penghasilan Tetap dan Tunjangan Perangkat Desa',
                ],
                [
                    'id'         => 45,
                    'Kelompok'   => '5.1.',
                    'Jenis'      => '5.1.3.',
                    'Nama_Jenis' => 'Jaminan Sosial Kepala Desa dan Perangkat Desa',
                ],
                [
                    'id'         => 46,
                    'Kelompok'   => '5.1.',
                    'Jenis'      => '5.1.4.',
                    'Nama_Jenis' => 'Tunjangan BPD',
                ],
                [
                    'id'         => 47,
                    'Kelompok'   => '5.2.',
                    'Jenis'      => '5.2.1.',
                    'Nama_Jenis' => 'Belanja Barang Perlengkapan',
                ],
                [
                    'id'         => 48,
                    'Kelompok'   => '5.2.',
                    'Jenis'      => '5.2.2.',
                    'Nama_Jenis' => 'Belanja Jasa Honorarium',
                ],
                [
                    'id'         => 49,
                    'Kelompok'   => '5.2.',
                    'Jenis'      => '5.2.3.',
                    'Nama_Jenis' => 'Belanja Perjalanan Dinas',
                ],
                [
                    'id'         => 50,
                    'Kelompok'   => '5.2.',
                    'Jenis'      => '5.2.4.',
                    'Nama_Jenis' => 'Belanja Jasa Sewa',
                ],
                [
                    'id'         => 51,
                    'Kelompok'   => '5.2.',
                    'Jenis'      => '5.2.5.',
                    'Nama_Jenis' => 'Belanja Operasional Perkantoran',
                ],
                [
                    'id'         => 52,
                    'Kelompok'   => '5.2.',
                    'Jenis'      => '5.2.6.',
                    'Nama_Jenis' => 'Belanja Pemeliharaan',
                ],
                [
                    'id'         => 53,
                    'Kelompok'   => '5.2.',
                    'Jenis'      => '5.2.7.',
                    'Nama_Jenis' => 'Belanja Barang dan Jasa yang Diserahkan kepada Masyarakat',
                ],
                [
                    'id'         => 54,
                    'Kelompok'   => '5.3.',
                    'Jenis'      => '5.3.1.',
                    'Nama_Jenis' => 'Belanja Modal Pengadaan Tanah',
                ],
                [
                    'id'         => 55,
                    'Kelompok'   => '5.3.',
                    'Jenis'      => '5.3.2.',
                    'Nama_Jenis' => 'Belanja Modal Pengadaan Peralatan, Mesin dan Alat Berat',
                ],
                [
                    'id'         => 56,
                    'Kelompok'   => '5.3.',
                    'Jenis'      => '5.3.3.',
                    'Nama_Jenis' => 'Belanja Modal Kendaraan',
                ],
                [
                    'id'         => 57,
                    'Kelompok'   => '5.3.',
                    'Jenis'      => '5.3.4.',
                    'Nama_Jenis' => 'Belanja Modal Gedung, Bangunan dan Taman',
                ],
                [
                    'id'         => 58,
                    'Kelompok'   => '5.3.',
                    'Jenis'      => '5.3.5.',
                    'Nama_Jenis' => 'Belanja Modal Jalan/Prasarana Jalan',
                ],
                [
                    'id'         => 59,
                    'Kelompok'   => '5.3.',
                    'Jenis'      => '5.3.6.',
                    'Nama_Jenis' => 'Belanja Modal Jembatan',
                ],
                [
                    'id'         => 60,
                    'Kelompok'   => '5.3.',
                    'Jenis'      => '5.3.7.',
                    'Nama_Jenis' => 'Belanja Modal Irigasi/Embung/Drainase/Air Limbah/Persampahan',
                ],
                [
                    'id'         => 61,
                    'Kelompok'   => '5.3.',
                    'Jenis'      => '5.3.8.',
                    'Nama_Jenis' => 'Belanja Modal Jaringan/Instalasi',
                ],
                [
                    'id'         => 62,
                    'Kelompok'   => '5.3.',
                    'Jenis'      => '5.3.9.',
                    'Nama_Jenis' => 'Belanja Modal Lainnya',
                ],
                [
                    'id'         => 63,
                    'Kelompok'   => '5.4.',
                    'Jenis'      => '5.4.1.',
                    'Nama_Jenis' => 'Belanja Tidak Terduga',
                ],
                [
                    'id'         => 64,
                    'Kelompok'   => '6.1.',
                    'Jenis'      => '6.1.1.',
                    'Nama_Jenis' => 'SILPA Tahun Sebelumnya',
                ],
                [
                    'id'         => 65,
                    'Kelompok'   => '6.1.',
                    'Jenis'      => '6.1.2.',
                    'Nama_Jenis' => 'Pencairan Dana Cadangan',
                ],
                [
                    'id'         => 66,
                    'Kelompok'   => '6.1.',
                    'Jenis'      => '6.1.3.',
                    'Nama_Jenis' => 'Hasil Penjualan Kekayaan Desa Yang Dipisahkan',
                ],
                [
                    'id'         => 67,
                    'Kelompok'   => '6.1.',
                    'Jenis'      => '6.1.9.',
                    'Nama_Jenis' => 'Penerimaan Pembiayaan Lainnya',
                ],
                [
                    'id'         => 68,
                    'Kelompok'   => '6.2.',
                    'Jenis'      => '6.2.1.',
                    'Nama_Jenis' => 'Pembentukan Dana Cadangan',
                ],
                [
                    'id'         => 69,
                    'Kelompok'   => '6.2.',
                    'Jenis'      => '6.2.2.',
                    'Nama_Jenis' => 'Penyertaan Modal Desa',
                ],
                [
                    'id'         => 70,
                    'Kelompok'   => '6.2.',
                    'Jenis'      => '6.2.9.',
                    'Nama_Jenis' => 'Pengeluaran Pembiayaan Lainnya',
                ],
                [
                    'id'         => 71,
                    'Kelompok'   => '7.1.',
                    'Jenis'      => '7.1.1.',
                    'Nama_Jenis' => 'Perhitungan PFK - Potongan Pajak',
                ],
                [
                    'id'         => 72,
                    'Kelompok'   => '7.1.',
                    'Jenis'      => '7.1.2.',
                    'Nama_Jenis' => 'Perhitungan PFK - Potongan Pajak Daerah',
                ],
                [
                    'id'         => 73,
                    'Kelompok'   => '7.1.',
                    'Jenis'      => '7.1.3.',
                    'Nama_Jenis' => 'Perhitungan PFK - Uang Muka dan Jaminan',
                ],
            ]
        );

        DB::table('ref_sinkronisasi')->insert([
            [
                'tabel'        => 'tweb_keluarga',
                'server'       => '6',
                'jenis_update' => 1,
                'tabel_hapus'  => 'log_keluarga',
            ],
            [
                'tabel'        => 'tweb_penduduk',
                'server'       => '6',
                'jenis_update' => 1,
                'tabel_hapus'  => 'log_hapus_penduduk',
            ],
        ]);

        // DB::table('notifikasi')->insert(); ikut data awal

        DB::table('tweb_keluarga_sejahtera')->insert([
            ['id' => 1, 'nama' => 'Keluarga Pra Sejahtera'],
            ['id' => 2, 'nama' => 'Keluarga Sejahtera I'],
            ['id' => 3, 'nama' => 'Keluarga Sejahtera II'],
            ['id' => 4, 'nama' => 'Keluarga Sejahtera III'],
            ['id' => 5, 'nama' => 'Keluarga Sejahtera III Plus'],
        ]);

        $this->load->model('seeders/dataAwal/Twebaset', 'twebaset');
        $this->load->model('seeders/dataAwal/KeuanganManualRefKegiatan', 'keuanganRefKegiatan');
        DB::table('tweb_aset')->insert($this->twebaset->getData());
        DB::table('keuangan_manual_ref_kegiatan')->insert($this->keuanganRefKegiatan->getData());
        $this->impor_klasifikasi();
        // DB::table('tweb_format_surat')->insert(); ikut data awal
    }

    public function impor_klasifikasi()
    {
        (new KlasifikasiSuratImports())->import();
    }

    private function defaultConfig()
    {
        Config::create([
            'app_key'           => get_app_key(),
            'nama_desa'         => '',
            'kode_desa'         => '',
            'nama_kecamatan'    => '',
            'kode_kecamatan'    => '',
            'nama_kabupaten'    => '',
            'kode_kabupaten'    => '',
            'nama_propinsi'     => '',
            'kode_propinsi'     => '',
            'nama_kepala_camat' => '',
            'nip_kepala_camat'  => '',
        ]);
    }
}
