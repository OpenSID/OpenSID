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

namespace Database\Seeders;

use App\Enums\AgamaEnum;
use App\Enums\AsalTanahKasEnum;
use App\Enums\AsuransiEnum;
use App\Enums\CacatEnum;
use App\Enums\CaraKBEnum;
use App\Enums\GolonganDarahEnum;
use App\Enums\HamilEnum;
use App\Enums\HubunganRTMEnum;
use App\Enums\JenisKelaminEnum;
use App\Enums\KeluargaSejahteraEnum;
use App\Enums\PekerjaanEnum;
use App\Enums\PendidikanKKEnum;
use App\Enums\PendidikanSedangEnum;
use App\Enums\PendudukBidangEnum;
use App\Enums\PendudukKursusEnum;
use App\Enums\PeristiwaPendudukEnum;
use App\Enums\PeruntukanTanahKasEnum;
use App\Enums\PindahEnum;
use App\Enums\SHDKEnum;
use App\Enums\StatusDasarEnum;
use App\Enums\StatusKawinEnum;
use App\Enums\StatusPendudukEnum;
use App\Enums\WargaNegaraEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Modules\Analisis\Enums\AnalisisRefStateEnum;
use Modules\Analisis\Enums\AnalisisRefSubjekEnum;
use Modules\Analisis\Enums\AnalisisTipeIndikatorEnum;

class DataStatisSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->tambahData();
    }

    public function insertEnumToTable(string $tableName, string $enumClass, array $columns = ['id', 'nama']): void
    {
        if (! Schema::hasTable($tableName)) {
            return;
        }

        $data = [];

        // Cek apakah enum menggunakan method all() (untuk legacy code)
        if (method_exists($enumClass, 'all')) {
            foreach ($enumClass::all() as $key => $value) {
                $row = [];
                if (count($columns) === 2) {
                    // Default behavior: kolom pertama untuk key, kolom kedua untuk value
                    $row[$columns[0]] = $key;
                    $row[$columns[1]] = $value;
                } else {
                    // Custom behavior: jika value adalah array, map sesuai columns
                    if (is_array($value)) {
                        foreach ($columns as $index => $column) {
                            $row[$column] = $value[$index] ?? null;
                        }
                    } else {
                        // Fallback: gunakan key sebagai id dan value sebagai kolom pertama
                        $row[$columns[0]] = $key;
                        if (isset($columns[1])) {
                            $row[$columns[1]] = $value;
                        }
                    }
                }
                $data[] = $row;
            }
        }
        // Cek apakah enum menggunakan method labels() (untuk enum PHP 8.1+)
        elseif (method_exists($enumClass, 'labels')) {
            foreach ($enumClass::labels() as $key => $value) {
                $row = [];
                if (count($columns) === 2) {
                    // Default behavior: kolom pertama untuk key, kolom kedua untuk value
                    $row[$columns[0]] = $key;
                    $row[$columns[1]] = $value;
                } else {
                    // Custom behavior: jika value adalah array, map sesuai columns
                    if (is_array($value)) {
                        foreach ($columns as $index => $column) {
                            $row[$column] = $value[$index] ?? null;
                        }
                    } else {
                        // Fallback: gunakan key sebagai id dan value sebagai kolom pertama
                        $row[$columns[0]] = $key;
                        if (isset($columns[1])) {
                            $row[$columns[1]] = $value;
                        }
                    }
                }
                $data[] = $row;
            }
        } else {
            return;
        }

        // Nonaktifkan constraint foreign key sementara
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        DB::table($tableName)->truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        DB::table($tableName)->insert($data);
    }

    public function tambahData()
    {
        $this->insertEnumToTable('analisis_ref_state', AnalisisRefStateEnum::class);
        $this->insertEnumToTable('analisis_ref_subjek', AnalisisRefSubjekEnum::class, ['id', 'subjek']);
        $this->insertEnumToTable('analisis_tipe_indikator', AnalisisTipeIndikatorEnum::class, ['id', 'tipe']);

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

        $this->insertEnumToTable('ref_penduduk_bidang', PendudukBidangEnum::class);
        $this->insertEnumToTable('ref_penduduk_hamil', HamilEnum::class);
        $this->insertEnumToTable('ref_penduduk_kursus', PendudukKursusEnum::class);
        $this->insertEnumToTable('ref_peristiwa', PeristiwaPendudukEnum::class);
        $this->insertEnumToTable('ref_pindah', PindahEnum::class);
        $this->insertEnumToTable('tweb_cacat', CacatEnum::class);
        $this->insertEnumToTable('tweb_cara_kb', CaraKBEnum::class);
        $this->insertEnumToTable('tweb_golongan_darah', GolonganDarahEnum::class);
        $this->insertEnumToTable('tweb_penduduk_agama', AgamaEnum::class);
        $this->insertEnumToTable('tweb_penduduk_asuransi', AsuransiEnum::class);
        $this->insertEnumToTable('tweb_penduduk_hubungan', SHDKEnum::class);
        $this->insertEnumToTable('tweb_penduduk_kawin', StatusKawinEnum::class);
        $this->insertEnumToTable('tweb_penduduk_pekerjaan', PekerjaanEnum::class);
        $this->insertEnumToTable('tweb_penduduk_pendidikan', PendidikanSedangEnum::class);
        $this->insertEnumToTable('tweb_penduduk_pendidikan_kk', PendidikanKKEnum::class);
        $this->insertEnumToTable('tweb_penduduk_sex', JenisKelaminEnum::class);
        $this->insertEnumToTable('tweb_penduduk_status', StatusPendudukEnum::class);
        $this->insertEnumToTable('tweb_penduduk_warganegara', WargaNegaraEnum::class);
        $this->insertEnumToTable('tweb_rtm_hubungan', HubunganRTMEnum::class);
        $this->insertEnumToTable('tweb_status_dasar', StatusDasarEnum::class);
        $this->insertEnumToTable('ref_asal_tanah_kas', AsalTanahKasEnum::class);
        $this->insertEnumToTable('ref_peruntukan_tanah_kas', PeruntukanTanahKasEnum::class);

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

        $this->insertEnumToTable('tweb_keluarga_sejahtera', KeluargaSejahteraEnum::class);

        $this->call(Twebaset::class);
    }
}
