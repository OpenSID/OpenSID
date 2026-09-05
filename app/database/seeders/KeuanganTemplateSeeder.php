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

use App\Models\KeuanganTemplate;
use Illuminate\Database\Seeder;

class KeuanganTemplateSeeder extends Seeder
{
    public function run(): void
    {
        if (KeuanganTemplate::count() === 0) {
            $keuanganTemplate = [
                [
                    'uuid'        => '4',
                    'parent_uuid' => null,
                    'uraian'      => 'Pendapatan',
                ],
                [
                    'uuid'        => '4.1',
                    'parent_uuid' => '4',
                    'uraian'      => 'Pendapatan Asli Desa',
                ],
                [
                    'uuid'        => '4.1.1',
                    'parent_uuid' => '4.1',
                    'uraian'      => 'Hasil Usaha',
                ],
                [
                    'uuid'        => '4.1.1.01',
                    'parent_uuid' => '4.1.1',
                    'uraian'      => 'Bagi Hasil BUMDes',
                ],
                [
                    'uuid'        => '4.1.1.90-99',
                    'parent_uuid' => '4.1.1',
                    'uraian'      => 'Lain-lain',
                ],
                [
                    'uuid'        => '4.1.2',
                    'parent_uuid' => '4.1',
                    'uraian'      => 'Hasil Aset',
                ],
                [
                    'uuid'        => '4.1.2.01',
                    'parent_uuid' => '4.1.2',
                    'uraian'      => 'Pengelolaan Tanah Kas Desa',
                ],
                [
                    'uuid'        => '4.1.2.02',
                    'parent_uuid' => '4.1.2',
                    'uraian'      => 'Tambatan Perahu',
                ],
                [
                    'uuid'        => '4.1.2.03',
                    'parent_uuid' => '4.1.2',
                    'uraian'      => 'Pasar Desa',
                ],
                [
                    'uuid'        => '4.1.2.04',
                    'parent_uuid' => '4.1.2',
                    'uraian'      => 'Tempat Pemandian Umum',
                ],
                [
                    'uuid'        => '4.1.2.05',
                    'parent_uuid' => '4.1.2',
                    'uraian'      => 'Jaringan Irigasi Desa',
                ],
                [
                    'uuid'        => '4.1.2.06',
                    'parent_uuid' => '4.1.2',
                    'uraian'      => 'Pelelangan Ikan Milik Desa',
                ],
                [
                    'uuid'        => '4.1.2.07',
                    'parent_uuid' => '4.1.2',
                    'uraian'      => 'Kios Milik Desa',
                ],
                [
                    'uuid'        => '4.1.2.08',
                    'parent_uuid' => '4.1.2',
                    'uraian'      => 'Pemanfaatan Lapangan/Prasarana Olahraga Milik Desa',
                ],
                [
                    'uuid'        => '4.1.2.90-99',
                    'parent_uuid' => '4.1.2',
                    'uraian'      => 'Lain-lain',
                ],
                [
                    'uuid'        => '4.1.3',
                    'parent_uuid' => '4.1',
                    'uraian'      => 'Swadaya, Partisipasi dan Gotong Royong',
                ],
                [
                    'uuid'        => '4.1.3.01',
                    'parent_uuid' => '4.1.3',
                    'uraian'      => 'Swadaya, partisipasi dan gotong royong',
                ],
                [
                    'uuid'        => '4.1.3.90-99',
                    'parent_uuid' => '4.1.3',
                    'uraian'      => 'Lain-lain Swadaya, Partisipasi dan Gotong Royong',
                ],
                [
                    'uuid'        => '4.1.4',
                    'parent_uuid' => '4.1',
                    'uraian'      => 'Lain-lain Pendapatan Asli Desa',
                ],
                [
                    'uuid'        => '4.1.4.01',
                    'parent_uuid' => '4.1.4',
                    'uraian'      => 'Hasil Pungutan Desa',
                ],
                [
                    'uuid'        => '4.1.4.90-99',
                    'parent_uuid' => '4.1.4',
                    'uraian'      => 'Lain-lain',
                ],
                [
                    'uuid'        => '4.2',
                    'parent_uuid' => '4',
                    'uraian'      => 'Transfer',
                ],
                [
                    'uuid'        => '4.2.1',
                    'parent_uuid' => '4.2',
                    'uraian'      => 'Dana Desa',
                ],
                [
                    'uuid'        => '4.2.1.01',
                    'parent_uuid' => '4.2.1',
                    'uraian'      => 'Dana Desa',
                ],
                [
                    'uuid'        => '4.2.2',
                    'parent_uuid' => '4.2',
                    'uraian'      => 'Bagian dari Hasil Pajak dan Retribusi Daerah Kabupaten/Kota',
                ],
                [
                    'uuid'        => '4.2.2.01',
                    'parent_uuid' => '4.2.2',
                    'uraian'      => 'Bagian dari Hasil Pajak dan Retribusi Daerah Kabupaten/Kota',
                ],
                [
                    'uuid'        => '4.2.3',
                    'parent_uuid' => '4.2',
                    'uraian'      => 'Alokasi Dana Desa',
                ],
                [
                    'uuid'        => '4.2.3.01',
                    'parent_uuid' => '4.2.3',
                    'uraian'      => 'Alokasi Dana Desa',
                ],
                [
                    'uuid'        => '4.2.4',
                    'parent_uuid' => '4.2',
                    'uraian'      => 'Bantuan Keuangan Provinsi',
                ],
                [
                    'uuid'        => '4.2.4.01',
                    'parent_uuid' => '4.2.4',
                    'uraian'      => 'Bantuan Keuangan dari APBD Provinsi',
                ],
                [
                    'uuid'        => '4.2.4.90-99',
                    'parent_uuid' => '4.2.4',
                    'uraian'      => 'Lain-lain Bantuan Keuangan dari APBD Provinsi',
                ],
                [
                    'uuid'        => '4.2.5',
                    'parent_uuid' => '4.2',
                    'uraian'      => 'Bantuan Keuangan APBD Kabupaten/Kota',
                ],
                [
                    'uuid'        => '4.2.5.01',
                    'parent_uuid' => '4.2.5',
                    'uraian'      => 'Bantuan Keuangan APBD Kabupaten/Kota',
                ],
                [
                    'uuid'        => '4.2.5.90-99',
                    'parent_uuid' => '4.2.5',
                    'uraian'      => 'Lain-lain Bantuan Keuangan dari APBD Kabupaten/Kota',
                ],
                [
                    'uuid'        => '4.3',
                    'parent_uuid' => '4',
                    'uraian'      => 'Pendapatan Lain-lain',
                ],
                [
                    'uuid'        => '4.3.1',
                    'parent_uuid' => '4.3',
                    'uraian'      => 'Penerimaan dari Hasil Kerjasama antar Desa',
                ],
                [
                    'uuid'        => '4.3.1.01',
                    'parent_uuid' => '4.3.1',
                    'uraian'      => 'Penerimaan dari Hasil Kerjasama antar Desa',
                ],
                [
                    'uuid'        => '4.3.2',
                    'parent_uuid' => '4.3',
                    'uraian'      => 'Penerimaan dari Hasil Kerjasama Desa dengan Pihak Ketiga',
                ],
                [
                    'uuid'        => '4.3.2.01',
                    'parent_uuid' => '4.3.2',
                    'uraian'      => 'Penerimaan dari Hasil Kerjasama Desa dengan Pihak Ketiga',
                ],
                [
                    'uuid'        => '4.3.3',
                    'parent_uuid' => '4.3',
                    'uraian'      => 'Penerimaan dari Bantuan Perusahaan yang Berlokasi di Desa',
                ],
                [
                    'uuid'        => '4.3.3.01',
                    'parent_uuid' => '4.3.3',
                    'uraian'      => 'Penerimaan dari Bantuan Perusahaan yang Berlokasi di Desa',
                ],
                [
                    'uuid'        => '4.3.4',
                    'parent_uuid' => '4.3',
                    'uraian'      => 'Hibah dan Sumbangan dari Pihak Ketiga',
                ],
                [
                    'uuid'        => '4.3.4.01',
                    'parent_uuid' => '4.3.4',
                    'uraian'      => 'Hibah dan Sumbangan dari Pihak Ketiga',
                ],
                [
                    'uuid'        => '4.3.5',
                    'parent_uuid' => '4.3',
                    'uraian'      => 'Koreksi Kesalahan Belanja Tahun-Tahun Anggaran Sebelumnya yang Mengakibatkan Penerimaan di Kas Desa pada Tahun Anggaran Berjalan',
                ],
                [
                    'uuid'        => '4.3.5.01',
                    'parent_uuid' => '4.3.5',
                    'uraian'      => 'Koreksi Kesalahan Belanja Tahun-Tahun Anggaran Sebelumnya yang Mengakibatkan Penerimaan di Kas Desa pada Tahun Anggaran Berjalan',
                ],
                [
                    'uuid'        => '4.3.6',
                    'parent_uuid' => '4.3',
                    'uraian'      => 'Bunga Bank',
                ],
                [
                    'uuid'        => '4.3.6.01',
                    'parent_uuid' => '4.3.6',
                    'uraian'      => 'Bunga Bank',
                ],
                [
                    'uuid'        => '4.3.9',
                    'parent_uuid' => '4.3',
                    'uraian'      => 'Lain-lain Pendapatan Desa yang Sah',
                ],
                [
                    'uuid'        => '4.3.9.01',
                    'parent_uuid' => '4.3.9',
                    'uraian'      => 'Lain-lain Pendapatan Desa yang Sah',
                ],
                [
                    'uuid'        => '4.3.9.90-99',
                    'parent_uuid' => '4.3.9',
                    'uraian'      => 'Lain-lain Pendapatan Desa yang Sah',
                ],
                [
                    'uuid'        => '5',
                    'parent_uuid' => null,
                    'uraian'      => 'Belanja',
                ],
                [
                    'uuid'        => '5.1',
                    'parent_uuid' => '5',
                    'uraian'      => 'BIDANG PENYELENGGARAN PEMERINTAHAN DESA',
                ],
                [
                    'uuid'        => '5.1.1',
                    'parent_uuid' => '5.1',
                    'uraian'      => 'Penyelenggaran Belanja Siltap, Tunjangan dan Operasional Pemerintah Desa',
                ],
                [
                    'uuid'        => '5.1.1.01',
                    'parent_uuid' => '5.1.1',
                    'uraian'      => 'Penyelenggaran Belanja Siltap, Tunjangan dan Operasional Pemerintah Desa',
                ],
                [
                    'uuid'        => '5.1.1.02',
                    'parent_uuid' => '5.1.1',
                    'uraian'      => 'Tunjangan Kepala Desa',
                ],
                [
                    'uuid'        => '5.1.1.90-99',
                    'parent_uuid' => '5.1.1',
                    'uraian'      => 'Penerimaan Lain Kepala Desa yang Sah',
                ],
                [
                    'uuid'        => '5.1.2',
                    'parent_uuid' => '5.1',
                    'uraian'      => 'Sarana dan Prasaran Pemerintah Desa',
                ],
                [
                    'uuid'        => '5.1.2.01',
                    'parent_uuid' => '5.1.2',
                    'uraian'      => 'Sarana dan Prasaran Pemerintah Desa',
                ],
                [
                    'uuid'        => '5.1.2.02',
                    'parent_uuid' => '5.1.2',
                    'uraian'      => 'Tunjangan Perangkat Desa',
                ],
                [
                    'uuid'        => '5.1.2.90-99',
                    'parent_uuid' => '5.1.2',
                    'uraian'      => 'Penerimaan Lain Perangkat Desa yang Sah',
                ],
                [
                    'uuid'        => '5.1.3',
                    'parent_uuid' => '5.1',
                    'uraian'      => 'Administrasi Kependudukan, Pencatatan Sipil, Statistik dan Kearsipan',
                ],
                [
                    'uuid'        => '5.1.3.01',
                    'parent_uuid' => '5.1.3',
                    'uraian'      => 'Administrasi Kependudukan, Pencatatan Sipil, Statistik dan Kearsipan',
                ],
                [
                    'uuid'        => '5.1.3.02',
                    'parent_uuid' => '5.1.3',
                    'uraian'      => 'Jaminan Kesehatan Perangkat Desa',
                ],
                [
                    'uuid'        => '5.1.3.03',
                    'parent_uuid' => '5.1.3',
                    'uraian'      => 'Jaminan Ketenagakerjaan Kepala Desa',
                ],
                [
                    'uuid'        => '5.1.3.04',
                    'parent_uuid' => '5.1.3',
                    'uraian'      => 'Jaminan Ketenagakerjaan Perangkat Desa',
                ],
                [
                    'uuid'        => '5.1.4',
                    'parent_uuid' => '5.1',
                    'uraian'      => 'Tata Praja Pemerintahan, Perencanaan, Keuangan',
                ],
                [
                    'uuid'        => '5.1.4.01',
                    'parent_uuid' => '5.1.4',
                    'uraian'      => 'Tata Praja Pemerintahan, Perencanaan, Keuangan',
                ],
                [
                    'uuid'        => '5.1.4.02',
                    'parent_uuid' => '5.1.4',
                    'uraian'      => 'Tunjangan Kinerja BPD',
                ],
                [
                    'uuid'        => '5.1.5',
                    'parent_uuid' => '5.1',
                    'uraian'      => 'Sub Bidang Pertanahan',
                ],
                [
                    'uuid'        => '5.1.5.01',
                    'parent_uuid' => '5.1.5',
                    'uraian'      => 'Sub Bidang Pertanahan',
                ],
                [
                    'uuid'        => '5.2',
                    'parent_uuid' => '5',
                    'uraian'      => 'BIDANG PELAKSANAAN PEMBANGUNAN DESA',
                ],
                [
                    'uuid'        => '5.2.1',
                    'parent_uuid' => '5.2',
                    'uraian'      => 'Sub Bidang Pendidikan',
                ],
                [
                    'uuid'        => '5.2.1.01',
                    'parent_uuid' => '5.2.1',
                    'uraian'      => 'Sub Bidang Pendidikan',
                ],
                [
                    'uuid'        => '5.2.1.02',
                    'parent_uuid' => '5.2.1',
                    'uraian'      => 'Belanja Perlengkapan Alat-alat Listrik',
                ],
                [
                    'uuid'        => '5.2.1.03',
                    'parent_uuid' => '5.2.1',
                    'uraian'      => 'Belanja Perlengkapan Alat-alat Rumah Tangga/Peralatan dan Bahan Kebersihan',
                ],
                [
                    'uuid'        => '5.2.1.04',
                    'parent_uuid' => '5.2.1',
                    'uraian'      => 'Belanja Bahan Bakar Minyak/Gas/Isi Ulang Tabung Pemadam Kebakaran',
                ],
                [
                    'uuid'        => '5.2.1.05',
                    'parent_uuid' => '5.2.1',
                    'uraian'      => 'Belanja Perlengkapan Cetak/Penggandaan - Belanja Barang Cetak dan Penggandaan',
                ],
                [
                    'uuid'        => '5.2.1.06',
                    'parent_uuid' => '5.2.1',
                    'uraian'      => 'Belanja Perlengkapan Barang Konsumsi (Makan/minum) - Belanja Barang Konsumsi',
                ],
                [
                    'uuid'        => '5.2.1.07',
                    'parent_uuid' => '5.2.1',
                    'uraian'      => 'Belanja Bahan/Material',
                ],
                [
                    'uuid'        => '5.2.1.08',
                    'parent_uuid' => '5.2.1',
                    'uraian'      => 'Belanja Bendera/Umbul-umbul/Spanduk',
                ],
                [
                    'uuid'        => '5.2.1.09',
                    'parent_uuid' => '5.2.1',
                    'uraian'      => 'Belanja Pakaian Dinas/Seragam/Atribut',
                ],
                [
                    'uuid'        => '5.2.1.10',
                    'parent_uuid' => '5.2.1',
                    'uraian'      => 'Belanja Obat-obatan',
                ],
                [
                    'uuid'        => '5.2.1.11',
                    'parent_uuid' => '5.2.1',
                    'uraian'      => 'Belanja Pakan Hewan/Ikan, Obat-obatan Hewan',
                ],
                [
                    'uuid'        => '5.2.1.12',
                    'parent_uuid' => '5.2.1',
                    'uraian'      => 'Belanja Pupuk/Obat-obatan Pertanian',
                ],
                [
                    'uuid'        => '5.2.1.90-99',
                    'parent_uuid' => '5.2.1',
                    'uraian'      => 'Belanja Barang Perlengkapan Lainnya',
                ],
                [
                    'uuid'        => '5.2.2',
                    'parent_uuid' => '5.2',
                    'uraian'      => 'Sub Bidang Kesehatan',
                ],
                [
                    'uuid'        => '5.2.2.01',
                    'parent_uuid' => '5.2.2',
                    'uraian'      => 'Sub Bidang Kesehatan',
                ],
                [
                    'uuid'        => '5.2.2.02',
                    'parent_uuid' => '5.2.2',
                    'uraian'      => 'Belanja Jasa Honorarium Pembantu Tugas Umum Desa/Operator',
                ],
                [
                    'uuid'        => '5.2.2.03',
                    'parent_uuid' => '5.2.2',
                    'uraian'      => 'Belanja Jasa Honorarium/Insentif Pelayanan Desa',
                ],
                [
                    'uuid'        => '5.2.2.04',
                    'parent_uuid' => '5.2.2',
                    'uraian'      => 'Belanja Jasa Honorarium Ahli/Profesi/Konsultan/Narasumber',
                ],
                [
                    'uuid'        => '5.2.2.05',
                    'parent_uuid' => '5.2.2',
                    'uraian'      => 'Belanja Jasa Honorarium Petugas',
                ],
                [
                    'uuid'        => '5.2.2.90-99',
                    'parent_uuid' => '5.2.2',
                    'uraian'      => 'Belanja Jasa Honorarium Lainnya',
                ],
                [
                    'uuid'        => '5.2.3',
                    'parent_uuid' => '5.2',
                    'uraian'      => 'Sub Bidang Pekerjaan Umum dan Penataan Ruang',
                ],
                [
                    'uuid'        => '5.2.3.01',
                    'parent_uuid' => '5.2.3',
                    'uraian'      => 'Sub Bidang Pekerjaan Umum dan Penataan Ruang',
                ],
                [
                    'uuid'        => '5.2.3.02',
                    'parent_uuid' => '5.2.3',
                    'uraian'      => 'Belanja Perjalanan Dinas Luar Kabupaten/Kota',
                ],
                [
                    'uuid'        => '5.2.3.03',
                    'parent_uuid' => '5.2.3',
                    'uraian'      => 'Belanja Kursus/Pelatihan',
                ],
                [
                    'uuid'        => '5.2.4',
                    'parent_uuid' => '5.2',
                    'uraian'      => 'Sub Bidang Kawasan Pemukiman',
                ],
                [
                    'uuid'        => '5.2.4.01',
                    'parent_uuid' => '5.2.4',
                    'uraian'      => 'Sub Bidang Kawasan Pemukiman',
                ],
                [
                    'uuid'        => '5.2.4.02',
                    'parent_uuid' => '5.2.4',
                    'uraian'      => 'Belanja Jasa Sewa Peralatan/Perlengkapan',
                ],
                [
                    'uuid'        => '5.2.4.03',
                    'parent_uuid' => '5.2.4',
                    'uraian'      => 'Belanja Jasa Sewa Sarana Mobilitas',
                ],
                [
                    'uuid'        => '5.2.4.04',
                    'parent_uuid' => '5.2.4',
                    'uraian'      => 'Belanja Jasa Sewa Lahan/Parkir',
                ],
                [
                    'uuid'        => '5.2.4.05',
                    'parent_uuid' => '5.2.4',
                    'uraian'      => 'Belanja Jasa Sewa Bunga/Pajangan',
                ],
                [
                    'uuid'        => '5.2.4.90-99',
                    'parent_uuid' => '5.2.4',
                    'uraian'      => 'Belanja Jasa Sewa Lainnya',
                ],
                [
                    'uuid'        => '5.2.5',
                    'parent_uuid' => '5.2',
                    'uraian'      => 'Sub Bidang Kehutanan dan Lingkungan Hidup',
                ],
                [
                    'uuid'        => '5.2.5.01',
                    'parent_uuid' => '5.2.5',
                    'uraian'      => 'Sub Bidang Kehutanan dan Lingkungan Hidup',
                ],
                [
                    'uuid'        => '5.2.5.02',
                    'parent_uuid' => '5.2.5',
                    'uraian'      => 'Belanja Jasa Pelayanan Pengamanan Kantor',
                ],
                [
                    'uuid'        => '5.2.6',
                    'parent_uuid' => '5.2',
                    'uraian'      => 'Sub Bidang Perhubungan, Komunikasi dan Informatika',
                ],
                [
                    'uuid'        => '5.2.6.01',
                    'parent_uuid' => '5.2.6',
                    'uraian'      => 'Sub Bidang Perhubungan, Komunikasi dan Informatika',
                ],
                [
                    'uuid'        => '5.2.6.02',
                    'parent_uuid' => '5.2.6',
                    'uraian'      => 'Belanja Jasa Konsultansi Pelaksanaan',
                ],
                [
                    'uuid'        => '5.2.6.90-99',
                    'parent_uuid' => '5.2.6',
                    'uraian'      => 'Belanja Jasa Konsultansi Lainnya',
                ],
                [
                    'uuid'        => '5.2.7',
                    'parent_uuid' => '5.2',
                    'uraian'      => 'Sub Bidang Energi dan Sumber Daya Mineral',
                ],
                [
                    'uuid'        => '5.2.7.01',
                    'parent_uuid' => '5.2.7',
                    'uraian'      => 'Sub Bidang Energi dan Sumber Daya Mineral',
                ],
                [
                    'uuid'        => '5.2.7.02',
                    'parent_uuid' => '5.2.7',
                    'uraian'      => 'Belanja Jasa Pencetakan',
                ],
                [
                    'uuid'        => '5.2.7.90-99',
                    'parent_uuid' => '5.2.7',
                    'uraian'      => 'Belanja Jasa Publikasi/Promosi/Pencetakan Lainnya',
                ],
                [
                    'uuid'        => '5.2.8',
                    'parent_uuid' => '5.2',
                    'uraian'      => 'Sub Bidang Pariwisata',
                ],
                [
                    'uuid'        => '5.2.8.01',
                    'parent_uuid' => '5.2.8',
                    'uraian'      => 'Sub Bidang Pariwisata',
                ],
                [
                    'uuid'        => '5.2.8.02',
                    'parent_uuid' => '5.2.8',
                    'uraian'      => 'Belanja Kebersihan/Peliharaan Halaman Kantor',
                ],
                [
                    'uuid'        => '5.2.8.03',
                    'parent_uuid' => '5.2.8',
                    'uraian'      => 'Belanja Penerimaan Tamu/Pelayanan',
                ],
                [
                    'uuid'        => '5.2.8.04',
                    'parent_uuid' => '5.2.8',
                    'uraian'      => 'Belanja Imbalan Jasa',
                ],
                [
                    'uuid'        => '5.2.8.05',
                    'parent_uuid' => '5.2.8',
                    'uraian'      => 'Belanja Jasa Lainnya yang Sah',
                ],
                [
                    'uuid'        => '5.3',
                    'parent_uuid' => '5',
                    'uraian'      => 'BIDANG PEMBINAAN KEMASYARAKATAN',
                ],
                [
                    'uuid'        => '5.3.1',
                    'parent_uuid' => '5.3',
                    'uraian'      => 'Ketenteraman, Ketertiban Umum, dan Perlindungan Masyarakat',
                ],
                [
                    'uuid'        => '5.3.1.01',
                    'parent_uuid' => '5.3.1',
                    'uraian'      => 'Ketenteraman, Ketertiban Umum, dan Perlindungan Masyarakat',
                ],
                [
                    'uuid'        => '5.3.1.02',
                    'parent_uuid' => '5.3.1',
                    'uraian'      => 'Belanja Modal Tanah Untuk Pembangunan Fasilitas Pemerintahan',
                ],
                [
                    'uuid'        => '5.3.1.03',
                    'parent_uuid' => '5.3.1',
                    'uraian'      => 'Belanja Modal Tanah Untuk Pembangunan Gedung Pendidikan',
                ],
                [
                    'uuid'        => '5.3.1.04',
                    'parent_uuid' => '5.3.1',
                    'uraian'      => 'Belanja Modal Tanah Untuk Pembangunan Fasilitas Pendidikan',
                ],
                [
                    'uuid'        => '5.3.1.05',
                    'parent_uuid' => '5.3.1',
                    'uraian'      => 'Belanja Modal Tanah Untuk Pembangunan Fasilitas Kesehatan',
                ],
                [
                    'uuid'        => '5.3.1.06',
                    'parent_uuid' => '5.3.1',
                    'uraian'      => 'Belanja Modal Tanah Untuk Pembangunan Fasilitas Umum',
                ],
                [
                    'uuid'        => '5.3.1.07',
                    'parent_uuid' => '5.3.1',
                    'uraian'      => 'Belanja Modal Tanah Untuk Pembangunan Infrastruktur',
                ],
                [
                    'uuid'        => '5.3.1.08',
                    'parent_uuid' => '5.3.1',
                    'uraian'      => 'Belanja Modal Tanah Untuk Pembangunan Irigasi',
                ],
                [
                    'uuid'        => '5.3.1.09',
                    'parent_uuid' => '5.3.1',
                    'uraian'      => 'Belanja Modal Tanah Untuk Pembangunan Sarana Perhubungan',
                ],
                [
                    'uuid'        => '5.3.1.10',
                    'parent_uuid' => '5.3.1',
                    'uraian'      => 'Belanja Modal Tanah Untuk Pembangunan Energi',
                ],
                [
                    'uuid'        => '5.3.1.11',
                    'parent_uuid' => '5.3.1',
                    'uraian'      => 'Belanja Modal Tanah Untuk Pembangunan Sarana Air Bersih',
                ],
                [
                    'uuid'        => '5.3.1.12',
                    'parent_uuid' => '5.3.1',
                    'uraian'      => 'Belanja Modal Tanah Untuk Pembangunan Prasarana Sanitasi',
                ],
                [
                    'uuid'        => '5.3.1.13',
                    'parent_uuid' => '5.3.1',
                    'uraian'      => 'Belanja Modal Tanah Untuk Pembangunan Sarana Wisata',
                ],
                [
                    'uuid'        => '5.3.1.14',
                    'parent_uuid' => '5.3.1',
                    'uraian'      => 'Belanja Modal Tanah Untuk Pembangunan Sosial Budaya',
                ],
                [
                    'uuid'        => '5.3.1.90-99',
                    'parent_uuid' => '5.3.1',
                    'uraian'      => 'Belanja Modal Tanah Untuk Pembangunan Lainnya',
                ],
                [
                    'uuid'        => '5.3.2',
                    'parent_uuid' => '5.3',
                    'uraian'      => 'Kebudayaan dan Keagamaan',
                ],
                [
                    'uuid'        => '5.3.2.01',
                    'parent_uuid' => '5.3.2',
                    'uraian'      => 'Kebudayaan dan Keagamaan',
                ],
                [
                    'uuid'        => '5.3.2.02',
                    'parent_uuid' => '5.3.2',
                    'uraian'      => 'Belanja Modal Gedung Fasilitas Pemerintahan',
                ],
                [
                    'uuid'        => '5.3.2.03',
                    'parent_uuid' => '5.3.2',
                    'uraian'      => 'Belanja Modal Gedung Pendidikan',
                ],
                [
                    'uuid'        => '5.3.2.04',
                    'parent_uuid' => '5.3.2',
                    'uraian'      => 'Belanja Modal Gedung Fasilitas Pendidikan',
                ],
                [
                    'uuid'        => '5.3.2.05',
                    'parent_uuid' => '5.3.2',
                    'uraian'      => 'Belanja Modal Gedung Fasilitas Kesehatan',
                ],
                [
                    'uuid'        => '5.3.2.06',
                    'parent_uuid' => '5.3.2',
                    'uraian'      => 'Belanja Modal Gedung Fasilitas Umum',
                ],
                [
                    'uuid'        => '5.3.2.07',
                    'parent_uuid' => '5.3.2',
                    'uraian'      => 'Belanja Modal Gedung Infrastruktur',
                ],
                [
                    'uuid'        => '5.3.2.08',
                    'parent_uuid' => '5.3.2',
                    'uraian'      => 'Belanja Modal Gedung Irigasi',
                ],
                [
                    'uuid'        => '5.3.2.09',
                    'parent_uuid' => '5.3.2',
                    'uraian'      => 'Belanja Modal Gedung Sarana Perhubungan',
                ],
                [
                    'uuid'        => '5.3.2.10',
                    'parent_uuid' => '5.3.2',
                    'uraian'      => 'Belanja Modal Gedung Energi',
                ],
                [
                    'uuid'        => '5.3.2.11',
                    'parent_uuid' => '5.3.2',
                    'uraian'      => 'Belanja Modal Gedung Sarana Air Bersih',
                ],
                [
                    'uuid'        => '5.3.2.12',
                    'parent_uuid' => '5.3.2',
                    'uraian'      => 'Belanja Modal Gedung Prasarana Sanitasi',
                ],
                [
                    'uuid'        => '5.3.2.13',
                    'parent_uuid' => '5.3.2',
                    'uraian'      => 'Belanja Modal Gedung Sarana Wisata',
                ],
                [
                    'uuid'        => '5.3.2.14',
                    'parent_uuid' => '5.3.2',
                    'uraian'      => 'Belanja Modal Gedung Sosial Budaya',
                ],
                [
                    'uuid'        => '5.3.2.90-99',
                    'parent_uuid' => '5.3.2',
                    'uraian'      => 'Belanja Modal Gedung dan Bangunan Lainnya',
                ],
                [
                    'uuid'        => '5.3.3',
                    'parent_uuid' => '5.3',
                    'uraian'      => 'Kepemudaan dan Olah Raga',
                ],
                [
                    'uuid'        => '5.3.3.01',
                    'parent_uuid' => '5.3.3',
                    'uraian'      => 'Kepemudaan dan Olah Raga',
                ],
                [
                    'uuid'        => '5.3.3.02',
                    'parent_uuid' => '5.3.3',
                    'uraian'      => 'Belanja Modal Peralatan, Mesin dan Barang Bergerak Fasilitas Pemerintahan',
                ],
                [
                    'uuid'        => '5.3.3.03',
                    'parent_uuid' => '5.3.3',
                    'uraian'      => 'Belanja Modal Peralatan, Mesin dan Barang Bergerak Gedung Pendidikan',
                ],
                [
                    'uuid'        => '5.3.3.04',
                    'parent_uuid' => '5.3.3',
                    'uraian'      => 'Belanja Modal Peralatan, Mesin dan Barang Bergerak Fasilitas Pendidikan',
                ],
                [
                    'uuid'        => '5.3.3.05',
                    'parent_uuid' => '5.3.3',
                    'uraian'      => 'Belanja Modal Peralatan, Mesin dan Barang Bergerak Fasilitas Kesehatan',
                ],
                [
                    'uuid'        => '5.3.3.06',
                    'parent_uuid' => '5.3.3',
                    'uraian'      => 'Belanja Modal Peralatan, Mesin dan Barang Bergerak Fasilitas Umum',
                ],
                [
                    'uuid'        => '5.3.3.07',
                    'parent_uuid' => '5.3.3',
                    'uraian'      => 'Belanja Modal Peralatan, Mesin dan Barang Bergerak Infrastruktur',
                ],
                [
                    'uuid'        => '5.3.3.08',
                    'parent_uuid' => '5.3.3',
                    'uraian'      => 'Belanja Modal Peralatan, Mesin dan Barang Bergerak Irigasi',
                ],
                [
                    'uuid'        => '5.3.3.09',
                    'parent_uuid' => '5.3.3',
                    'uraian'      => 'Belanja Modal Peralatan, Mesin dan Barang Bergerak Sarana Perhubungan',
                ],
                [
                    'uuid'        => '5.3.3.10',
                    'parent_uuid' => '5.3.3',
                    'uraian'      => 'Belanja Modal Peralatan, Mesin dan Barang Bergerak Energi',
                ],
                [
                    'uuid'        => '5.3.3.11',
                    'parent_uuid' => '5.3.3',
                    'uraian'      => 'Belanja Modal Peralatan, Mesin dan Barang Bergerak Sarana Air Bersih',
                ],
                [
                    'uuid'        => '5.3.3.12',
                    'parent_uuid' => '5.3.3',
                    'uraian'      => 'Belanja Modal Peralatan, Mesin dan Barang Bergerak Prasarana Sanitasi',
                ],
                [
                    'uuid'        => '5.3.3.13',
                    'parent_uuid' => '5.3.3',
                    'uraian'      => 'Belanja Modal Peralatan, Mesin dan Barang Bergerak Sarana Wisata',
                ],
                [
                    'uuid'        => '5.3.3.14',
                    'parent_uuid' => '5.3.3',
                    'uraian'      => 'Belanja Modal Peralatan, Mesin dan Barang Bergerak Sosial Budaya',
                ],
                [
                    'uuid'        => '5.3.3.90-99',
                    'parent_uuid' => '5.3.3',
                    'uraian'      => 'Belanja Modal Peralatan, Mesin dan Barang Bergerak Lainnya',
                ],
                [
                    'uuid'        => '5.3.4',
                    'parent_uuid' => '5.3',
                    'uraian'      => 'Kelembagaan Masyarakat',
                ],
                [
                    'uuid'        => '5.3.4.01',
                    'parent_uuid' => '5.3.4',
                    'uraian'      => 'Kelembagaan Masyarakat',
                ],
                [
                    'uuid'        => '5.3.4.02',
                    'parent_uuid' => '5.3.4',
                    'uraian'      => 'Belanja Modal Fisik Lainnya Untuk Fasilitas Pemerintahan',
                ],
                [
                    'uuid'        => '5.3.4.03',
                    'parent_uuid' => '5.3.4',
                    'uraian'      => 'Belanja Modal Fisik Lainnya Untuk Gedung Pendidikan',
                ],
                [
                    'uuid'        => '5.3.4.04',
                    'parent_uuid' => '5.3.4',
                    'uraian'      => 'Belanja Modal Fisik Lainnya Untuk Fasilitas Pendidikan',
                ],
                [
                    'uuid'        => '5.3.4.05',
                    'parent_uuid' => '5.3.4',
                    'uraian'      => 'Belanja Modal Fisik Lainnya Untuk Fasilitas Kesehatan',
                ],
                [
                    'uuid'        => '5.3.4.06',
                    'parent_uuid' => '5.3.4',
                    'uraian'      => 'Belanja Modal Fisik Lainnya Untuk Fasilitas Umum',
                ],
                [
                    'uuid'        => '5.3.4.07',
                    'parent_uuid' => '5.3.4',
                    'uraian'      => 'Belanja Modal Fisik Lainnya Untuk Infrastruktur',
                ],
                [
                    'uuid'        => '5.3.4.08',
                    'parent_uuid' => '5.3.4',
                    'uraian'      => 'Belanja Modal Fisik Lainnya Untuk Irigasi',
                ],
                [
                    'uuid'        => '5.3.4.09',
                    'parent_uuid' => '5.3.4',
                    'uraian'      => 'Belanja Modal Fisik Lainnya Untuk Sarana Perhubungan',
                ],
                [
                    'uuid'        => '5.3.4.10',
                    'parent_uuid' => '5.3.4',
                    'uraian'      => 'Belanja Modal Fisik Lainnya Untuk Energi',
                ],
                [
                    'uuid'        => '5.3.4.11',
                    'parent_uuid' => '5.3.4',
                    'uraian'      => 'Belanja Modal Fisik Lainnya Untuk Sarana Air Bersih',
                ],
                [
                    'uuid'        => '5.3.4.12',
                    'parent_uuid' => '5.3.4',
                    'uraian'      => 'Belanja Modal Fisik Lainnya Untuk Prasarana Sanitasi',
                ],
                [
                    'uuid'        => '5.3.4.13',
                    'parent_uuid' => '5.3.4',
                    'uraian'      => 'Belanja Modal Fisik Lainnya Untuk Sarana Wisata',
                ],
                [
                    'uuid'        => '5.3.4.14',
                    'parent_uuid' => '5.3.4',
                    'uraian'      => 'Belanja Modal Fisik Lainnya Untuk Sosial Budaya',
                ],
                [
                    'uuid'        => '5.3.4.90-99',
                    'parent_uuid' => '5.3.4',
                    'uraian'      => 'Belanja Modal Fisik Lainnya Untuk Kegiatan Lainnya',
                ],
                [
                    'uuid'        => '5.4',
                    'parent_uuid' => '5',
                    'uraian'      => 'BIDANG PEMBERDAYAAN MASYARAKAT',
                ],
                [
                    'uuid'        => '5.4.1',
                    'parent_uuid' => '5.4',
                    'uraian'      => 'Sub Bidang Kelautan dan Perikanan',
                ],
                [
                    'uuid'        => '5.4.1.01',
                    'parent_uuid' => '5.4.1',
                    'uraian'      => 'Sub Bidang Kelautan dan Perikanan',
                ],
                [
                    'uuid'        => '5.4.2',
                    'parent_uuid' => '5.4',
                    'uraian'      => 'Sub Bidang Pertanian dan Peternakan',
                ],
                [
                    'uuid'        => '5.4.2.01',
                    'parent_uuid' => '5.4.2',
                    'uraian'      => 'Sub Bidang Pertanian dan Peternakan',
                ],
                [
                    'uuid'        => '5.4.3',
                    'parent_uuid' => '5.4',
                    'uraian'      => 'Sub Bidang Peningkatan Kapasita Aparatur Desa',
                ],
                [
                    'uuid'        => '5.4.3.01',
                    'parent_uuid' => '5.4.3',
                    'uraian'      => 'Sub Bidang Peningkatan Kapasita Aparatur Desa',
                ],
                [
                    'uuid'        => '5.4.4',
                    'parent_uuid' => '5.4',
                    'uraian'      => 'Pemberdayaan Perempuan, Perlindungan Anak dan Keluarga',
                ],
                [
                    'uuid'        => '5.4.4.01',
                    'parent_uuid' => '5.4.4',
                    'uraian'      => 'Pemberdayaan Perempuan, Perlindungan Anak dan Keluarga',
                ],
                [
                    'uuid'        => '5.4.5',
                    'parent_uuid' => '5.4',
                    'uraian'      => 'Koperasi, Usaha Mikro Kecil dan Menegah (UMKM)',
                ],
                [
                    'uuid'        => '5.4.5.01',
                    'parent_uuid' => '5.4.5',
                    'uraian'      => 'Koperasi, Usaha Mikro Kecil dan Menegah (UMKM)',
                ],
                [
                    'uuid'        => '5.4.6',
                    'parent_uuid' => '5.4',
                    'uraian'      => 'Dukungan Penanaman Modal',
                ],
                [
                    'uuid'        => '5.4.6.01',
                    'parent_uuid' => '5.4.6',
                    'uraian'      => 'Dukungan Penanaman Modal',
                ],
                [
                    'uuid'        => '5.4.7',
                    'parent_uuid' => '5.4',
                    'uraian'      => 'Perdagangan dan Perindustrian',
                ],
                [
                    'uuid'        => '5.4.7.01',
                    'parent_uuid' => '5.4.7',
                    'uraian'      => 'Perdagangan dan Perindustrian',
                ],
                [
                    'uuid'        => '5.5',
                    'parent_uuid' => '5',
                    'uraian'      => 'PENAGGULANGAN BENCANA, KEADAAN DARURAT DAN MENDESAK',
                ],
                [
                    'uuid'        => '5.5.1',
                    'parent_uuid' => '5.5',
                    'uraian'      => 'Penanggulangan Bencana',
                ],
                [
                    'uuid'        => '5.5.1.01',
                    'parent_uuid' => '5.5.1',
                    'uraian'      => 'Belanja Tak Terduga',
                ],
                [
                    'uuid'        => '5.5.2',
                    'parent_uuid' => '5.5',
                    'uraian'      => 'Keadaan Darurat',
                ],
                [
                    'uuid'        => '5.5.2.01',
                    'parent_uuid' => '5.5.2',
                    'uraian'      => 'Keadaan Darurat',
                ],
                [
                    'uuid'        => '5.5.3',
                    'parent_uuid' => '5.5',
                    'uraian'      => 'Mendesak',
                ],
                [
                    'uuid'        => '5.5.3.01',
                    'parent_uuid' => '5.5.3',
                    'uraian'      => 'Mendesak',
                ],
                [
                    'uuid'        => '6',
                    'parent_uuid' => null,
                    'uraian'      => 'Pembiayaan',
                ],
                [
                    'uuid'        => '6.1',
                    'parent_uuid' => '6',
                    'uraian'      => 'Penerimaan Pembiayaan',
                ],
                [
                    'uuid'        => '6.1.1',
                    'parent_uuid' => '6.1',
                    'uraian'      => 'SILPA Tahun Sebelumnya',
                ],
                [
                    'uuid'        => '6.1.1.01',
                    'parent_uuid' => '6.1.1',
                    'uraian'      => 'SILPA Tahun Sebelumnya',
                ],
                [
                    'uuid'        => '6.1.2',
                    'parent_uuid' => '6.1',
                    'uraian'      => 'Pencairan Dana Cadangan',
                ],
                [
                    'uuid'        => '6.1.2.01',
                    'parent_uuid' => '6.1.2',
                    'uraian'      => 'Pencairan Dana Cadangan',
                ],
                [
                    'uuid'        => '6.1.3',
                    'parent_uuid' => '6.1',
                    'uraian'      => 'Hasil Penjualan Kekayaan Desa yang Dipisahkan',
                ],
                [
                    'uuid'        => '6.1.3.01',
                    'parent_uuid' => '6.1.3',
                    'uraian'      => 'Hasil Penjualan Kekayaan Desa yang Dipisahkan',
                ],
                [
                    'uuid'        => '6.1.9',
                    'parent_uuid' => '6.1',
                    'uraian'      => 'Penerimaan Pembiayaan Lainnya',
                ],
                [
                    'uuid'        => '6.1.9.01',
                    'parent_uuid' => '6.1',
                    'uraian'      => 'Penerimaan Pembiayaan Lainnya',
                ],
                [
                    'uuid'        => '6.1.9.90-99',
                    'parent_uuid' => '6.1.9',
                    'uraian'      => 'Penerimaan Pembiayaan Lainnya',
                ],
                [
                    'uuid'        => '6.2',
                    'parent_uuid' => '6',
                    'uraian'      => 'Pengeluaran Pembiayaan',
                ],
                [
                    'uuid'        => '6.2.1',
                    'parent_uuid' => '6.2',
                    'uraian'      => 'Pembentukan Dana Cadangan',
                ],
                [
                    'uuid'        => '6.2.1.01',
                    'parent_uuid' => '6.2.1',
                    'uraian'      => 'Pembentukan Dana Cadangan',
                ],
                [
                    'uuid'        => '6.2.2',
                    'parent_uuid' => '6.2',
                    'uraian'      => 'Penyertaan Modal Desa',
                ],
                [
                    'uuid'        => '6.2.2.01',
                    'parent_uuid' => '6.2.2',
                    'uraian'      => 'Penyertaan Modal Desa',
                ],
                [
                    'uuid'        => '6.2.9',
                    'parent_uuid' => '6.2',
                    'uraian'      => 'Pengeluaran Pembiayaan Lainnya',
                ],
                [
                    'uuid'        => '6.2.9.01',
                    'parent_uuid' => '6.2.9',
                    'uraian'      => 'Pengeluaran Pembiayaan Lainnya',
                ],
                [
                    'uuid'        => '6.2.9.90-99',
                    'parent_uuid' => '6.2.9',
                    'uraian'      => 'Pengeluaran Pembiayaan Lainnya',
                ],
            ];

            foreach ($keuanganTemplate as $template) {
                KeuanganTemplate::updateOrCreate(
                    ['uuid' => $template['uuid']],
                    [
                        'parent_uuid' => $template['parent_uuid'] ?? null,
                        'uraian'      => $template['uraian'] ?? '',
                        'created_by'  => 1,
                        'updated_by'  => 1,
                        'created_at'  => now(),
                        'updated_at'  => now(),
                    ]
                );
            }
        }
    }
}
