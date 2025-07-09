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

use App\Models\Config;
use App\Models\GrupAkses;
use App\Models\Keuangan;
use App\Models\KeuanganManualRinci;
use App\Models\KeuanganTemplate;
use App\Models\Migrasi;
use App\Models\Modul;
use App\Models\Setting;
use App\Models\User;
use App\Models\UserGrup;
use App\Services\Install\CreateGrupAksesService;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

defined('BASEPATH') || exit('No direct script access allowed');

class Migrasi_2025010171 extends MY_Model
{
    public function up()
    {
        $this->migrasi_2024112671();
        $this->migrasi_2024121752();
        $this->migrasi_2024071251();
        $this->migrasi_2024102351();
        $this->migrasi_2024110151();
        $this->migrasi_2024112672();
        $this->migrasi_2024102551();
        $this->migrasi_2024122451();
        $this->migrasi_2024120171();
        $this->migrasi_2024123171();
        $this->migrasi_2024121971();
        $this->migrasi_2024123151();
    }

    public function migrasi_2024121752()
    {
        DB::statement("update tweb_surat_format set syarat_surat = NULL where syarat_surat = 'null'");
    }

    protected function migrasi_2024110151()
    {
        GrupAkses::whereIn('id_modul', static function ($q) {
            $q->select('id_modul')->from('setting_modul')->whereIn('slug', ['laporan-manual', 'impor-data']);
        })->delete();

        Setting::whereIn('slug', ['laporan-manual', 'impor-data'])->delete();
    }

    public function migrasi_2024071251()
    {
        if (! Schema::hasTable('keuangan_template')) {
            Schema::create('keuangan_template', static function (Blueprint $table) {
                $table->uuid()->primary();
                $table->char('parent_uuid', 36)->index()->nullable();
                $table->string('uraian', 255);

                $table->integer('created_by')->nullable();
                $table->integer('updated_by')->nullable();
                $table->timestamps();
            });

            $template = [
                // Pendapatan
                ['uuid' => '4', 'parent_uuid' => null, 'uraian' => 'Pendapatan', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '4.1', 'parent_uuid' => '4', 'uraian' => 'Pendapatan Asli Desa', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '4.1.1', 'parent_uuid' => '4.1', 'uraian' => 'Hasil Usaha', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '4.1.1.01', 'parent_uuid' => '4.1.1', 'uraian' => 'Bagi Hasil BUMDes', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '4.1.1.90-99', 'parent_uuid' => '4.1.1', 'uraian' => 'Lain-lain', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '4.1.2', 'parent_uuid' => '4.1', 'uraian' => 'Hasil Aset', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '4.1.2.01', 'parent_uuid' => '4.1.2', 'uraian' => 'Pengelolaan Tanah Kas Desa', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '4.1.2.02', 'parent_uuid' => '4.1.2', 'uraian' => 'Tambatan Perahu', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '4.1.2.03', 'parent_uuid' => '4.1.2', 'uraian' => 'Pasar Desa', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '4.1.2.04', 'parent_uuid' => '4.1.2', 'uraian' => 'Tempat Pemandian Umum', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '4.1.2.05', 'parent_uuid' => '4.1.2', 'uraian' => 'Jaringan Irigasi Desa', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '4.1.2.06', 'parent_uuid' => '4.1.2', 'uraian' => 'Pelelangan Ikan Milik Desa', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '4.1.2.07', 'parent_uuid' => '4.1.2', 'uraian' => 'Kios Milik Desa', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '4.1.2.08', 'parent_uuid' => '4.1.2', 'uraian' => 'Pemanfaatan Lapangan/Prasarana Olahraga Milik Desa', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '4.1.2.90-99', 'parent_uuid' => '4.1.2', 'uraian' => 'Lain-lain', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '4.1.3', 'parent_uuid' => '4.1', 'uraian' => 'Swadaya, Partisipasi dan Gotong Royong', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '4.1.3.01', 'parent_uuid' => '4.1.3', 'uraian' => 'Swadaya, partisipasi dan gotong royong', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '4.1.3.90-99', 'parent_uuid' => '4.1.3', 'uraian' => 'Lain-lain Swadaya, Partisipasi dan Gotong Royong', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '4.1.4', 'parent_uuid' => '4.1', 'uraian' => 'Lain-lain Pendapatan Asli Desa', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '4.1.4.01', 'parent_uuid' => '4.1.4', 'uraian' => 'Hasil Pungutan Desa', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '4.1.4.90-99', 'parent_uuid' => '4.1.4', 'uraian' => 'Lain-lain', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '4.2', 'parent_uuid' => '4', 'uraian' => 'Transfer', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '4.2.1', 'parent_uuid' => '4.2', 'uraian' => 'Dana Desa', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '4.2.1.01', 'parent_uuid' => '4.2.1', 'uraian' => 'Dana Desa', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '4.2.2', 'parent_uuid' => '4.2', 'uraian' => 'Bagian dari Hasil Pajak dan Retribusi Daerah Kabupaten/Kota', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '4.2.2.01', 'parent_uuid' => '4.2.2', 'uraian' => 'Bagian dari Hasil Pajak dan Retribusi Daerah Kabupaten/Kota', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '4.2.3', 'parent_uuid' => '4.2', 'uraian' => 'Alokasi Dana Desa', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '4.2.3.01', 'parent_uuid' => '4.2.3', 'uraian' => 'Alokasi Dana Desa', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '4.2.4', 'parent_uuid' => '4.2', 'uraian' => 'Bantuan Keuangan Provinsi', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '4.2.4.01', 'parent_uuid' => '4.2.4', 'uraian' => 'Bantuan Keuangan dari APBD Provinsi', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '4.2.4.90-99', 'parent_uuid' => '4.2.4', 'uraian' => 'Lain-lain Bantuan Keuangan dari APBD Provinsi', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '4.2.5', 'parent_uuid' => '4.2', 'uraian' => 'Bantuan Keuangan APBD Kabupaten/Kota', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '4.2.5.01', 'parent_uuid' => '4.2.5', 'uraian' => 'Bantuan Keuangan APBD Kabupaten/Kota', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '4.2.5.90-99', 'parent_uuid' => '4.2.5', 'uraian' => 'Lain-lain Bantuan Keuangan dari APBD Kabupaten/Kota', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '4.3', 'parent_uuid' => '4', 'uraian' => 'Pendapatan Lain-lain', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '4.3.1', 'parent_uuid' => '4.3', 'uraian' => 'Penerimaan dari Hasil Kerjasama antar Desa', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '4.3.1.01', 'parent_uuid' => '4.3.1', 'uraian' => 'Penerimaan dari Hasil Kerjasama antar Desa', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '4.3.2', 'parent_uuid' => '4.3', 'uraian' => 'Penerimaan dari Hasil Kerjasama Desa dengan Pihak Ketiga', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '4.3.2.01', 'parent_uuid' => '4.3.2', 'uraian' => 'Penerimaan dari Hasil Kerjasama Desa dengan Pihak Ketiga', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '4.3.3', 'parent_uuid' => '4.3', 'uraian' => 'Penerimaan dari Bantuan Perusahaan yang Berlokasi di Desa', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '4.3.3.01', 'parent_uuid' => '4.3.3', 'uraian' => 'Penerimaan dari Bantuan Perusahaan yang Berlokasi di Desa', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '4.3.4', 'parent_uuid' => '4.3', 'uraian' => 'Hibah dan Sumbangan dari Pihak Ketiga', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '4.3.4.01', 'parent_uuid' => '4.3.4', 'uraian' => 'Hibah dan Sumbangan dari Pihak Ketiga', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '4.3.5', 'parent_uuid' => '4.3', 'uraian' => 'Koreksi Kesalahan Belanja Tahun-Tahun Anggaran Sebelumnya yang Mengakibatkan Penerimaan di Kas Desa pada Tahun Anggaran Berjalan', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '4.3.5.01', 'parent_uuid' => '4.3.5', 'uraian' => 'Koreksi Kesalahan Belanja Tahun-Tahun Anggaran Sebelumnya yang Mengakibatkan Penerimaan di Kas Desa pada Tahun Anggaran Berjalan', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '4.3.6', 'parent_uuid' => '4.3', 'uraian' => 'Bunga Bank', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '4.3.6.01', 'parent_uuid' => '4.3.6', 'uraian' => 'Bunga Bank', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '4.3.9', 'parent_uuid' => '4.3', 'uraian' => 'Lain-lain Pendapatan Desa yang Sah', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '4.3.9.01', 'parent_uuid' => '4.3.9', 'uraian' => 'Lain-lain Pendapatan Desa yang Sah', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '4.3.9.90-99', 'parent_uuid' => '4.3.9', 'uraian' => 'Lain-lain Pendapatan Desa yang Sah', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],

                // Belanja
                ['uuid' => '5', 'parent_uuid' => null, 'uraian' => 'Belanja', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '5.1', 'parent_uuid' => '5', 'uraian' => 'Belanja Pegawai', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '5.1.1', 'parent_uuid' => '5.1', 'uraian' => 'Penghasilan Tetap dan Tunjangan Kepala Desa', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '5.1.1.01', 'parent_uuid' => '5.1.1', 'uraian' => 'Penghasilan Tetap Kepala Desa', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '5.1.1.02', 'parent_uuid' => '5.1.1', 'uraian' => 'Tunjangan Kepala Desa', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '5.1.1.90-99', 'parent_uuid' => '5.1.1', 'uraian' => 'Penerimaan Lain Kepala Desa yang Sah', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '5.1.2', 'parent_uuid' => '5.1', 'uraian' => 'Penghasilan Tetap dan Tunjangan Perangkat Desa', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '5.1.2.01', 'parent_uuid' => '5.1.2', 'uraian' => 'Penghasilan Tetap Perangkat Desa', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '5.1.2.02', 'parent_uuid' => '5.1.2', 'uraian' => 'Tunjangan Perangkat Desa', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '5.1.2.90-99', 'parent_uuid' => '5.1.2', 'uraian' => 'Penerimaan Lain Perangkat Desa yang Sah', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '5.1.3', 'parent_uuid' => '5.1', 'uraian' => 'Jaminan Sosial Kepala Desa dan Perangkat Desa', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '5.1.3.01', 'parent_uuid' => '5.1.3', 'uraian' => 'Jaminan Kesehatan Kepala Desa', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '5.1.3.02', 'parent_uuid' => '5.1.3', 'uraian' => 'Jaminan Kesehatan Perangkat Desa', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '5.1.3.03', 'parent_uuid' => '5.1.3', 'uraian' => 'Jaminan Ketenagakerjaan Kepala Desa', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '5.1.3.04', 'parent_uuid' => '5.1.3', 'uraian' => 'Jaminan Ketenagakerjaan Perangkat Desa', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '5.1.4', 'parent_uuid' => '5.1', 'uraian' => 'Tunjangan BPD', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '5.1.4.01', 'parent_uuid' => '5.1.4', 'uraian' => 'Tunjangan Kedudukan BPD', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '5.1.4.02', 'parent_uuid' => '5.1.4', 'uraian' => 'Tunjangan Kinerja BPD', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '5.2', 'parent_uuid' => '5', 'uraian' => 'Belanja Barang dan Jasa', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '5.2.1', 'parent_uuid' => '5.2', 'uraian' => 'Belanja Barang Perlengkapan', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '5.2.1.01', 'parent_uuid' => '5.2.1', 'uraian' => 'Belanja Perlengkapan Alat Tulis Kantor dan Benda Pos', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '5.2.1.02', 'parent_uuid' => '5.2.1', 'uraian' => 'Belanja Perlengkapan Alat-alat Listrik', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '5.2.1.03', 'parent_uuid' => '5.2.1', 'uraian' => 'Belanja Perlengkapan Alat-alat Rumah Tangga/Peralatan dan Bahan Kebersihan', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '5.2.1.04', 'parent_uuid' => '5.2.1', 'uraian' => 'Belanja Bahan Bakar Minyak/Gas/Isi Ulang Tabung Pemadam Kebakaran', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '5.2.1.05', 'parent_uuid' => '5.2.1', 'uraian' => 'Belanja Perlengkapan Cetak/Penggandaan - Belanja Barang Cetak dan Penggandaan', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '5.2.1.06', 'parent_uuid' => '5.2.1', 'uraian' => 'Belanja Perlengkapan Barang Konsumsi (Makan/minum) - Belanja Barang Konsumsi', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '5.2.1.07', 'parent_uuid' => '5.2.1', 'uraian' => 'Belanja Bahan/Material', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '5.2.1.08', 'parent_uuid' => '5.2.1', 'uraian' => 'Belanja Bendera/Umbul-umbul/Spanduk', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '5.2.1.09', 'parent_uuid' => '5.2.1', 'uraian' => 'Belanja Pakaian Dinas/Seragam/Atribut', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '5.2.1.10', 'parent_uuid' => '5.2.1', 'uraian' => 'Belanja Obat-obatan', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '5.2.1.11', 'parent_uuid' => '5.2.1', 'uraian' => 'Belanja Pakan Hewan/Ikan, Obat-obatan Hewan', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '5.2.1.12', 'parent_uuid' => '5.2.1', 'uraian' => 'Belanja Pupuk/Obat-obatan Pertanian', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '5.2.1.90-99', 'parent_uuid' => '5.2.1', 'uraian' => 'Belanja Barang Perlengkapan Lainnya', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '5.2.2', 'parent_uuid' => '5.2', 'uraian' => 'Belanja Jasa Honorarium', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '5.2.2.01', 'parent_uuid' => '5.2.2', 'uraian' => 'Belanja Jasa Honorarium Tim yang Melaksanakan Kegiatan', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '5.2.2.02', 'parent_uuid' => '5.2.2', 'uraian' => 'Belanja Jasa Honorarium Pembantu Tugas Umum Desa/Operator', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '5.2.2.03', 'parent_uuid' => '5.2.2', 'uraian' => 'Belanja Jasa Honorarium/Insentif Pelayanan Desa', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '5.2.2.04', 'parent_uuid' => '5.2.2', 'uraian' => 'Belanja Jasa Honorarium Ahli/Profesi/Konsultan/Narasumber', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '5.2.2.05', 'parent_uuid' => '5.2.2', 'uraian' => 'Belanja Jasa Honorarium Petugas', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '5.2.2.90-99', 'parent_uuid' => '5.2.2', 'uraian' => 'Belanja Jasa Honorarium Lainnya', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '5.2.3', 'parent_uuid' => '5.2', 'uraian' => 'Belanja Perjalanan Dinas', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '5.2.3.01', 'parent_uuid' => '5.2.3', 'uraian' => 'Belanja Perjalanan Dinas Dalam Kabupaten/Kota', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '5.2.3.02', 'parent_uuid' => '5.2.3', 'uraian' => 'Belanja Perjalanan Dinas Luar Kabupaten/Kota', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '5.2.3.03', 'parent_uuid' => '5.2.3', 'uraian' => 'Belanja Kursus/Pelatihan', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '5.2.4', 'parent_uuid' => '5.2', 'uraian' => 'Belanja Jasa Sewa', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '5.2.4.01', 'parent_uuid' => '5.2.4', 'uraian' => 'Belanja Jasa Sewa Bangunan/Gedung/Ruang', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '5.2.4.02', 'parent_uuid' => '5.2.4', 'uraian' => 'Belanja Jasa Sewa Peralatan/Perlengkapan', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '5.2.4.03', 'parent_uuid' => '5.2.4', 'uraian' => 'Belanja Jasa Sewa Sarana Mobilitas', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '5.2.4.04', 'parent_uuid' => '5.2.4', 'uraian' => 'Belanja Jasa Sewa Lahan/Parkir', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '5.2.4.05', 'parent_uuid' => '5.2.4', 'uraian' => 'Belanja Jasa Sewa Bunga/Pajangan', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '5.2.4.90-99', 'parent_uuid' => '5.2.4', 'uraian' => 'Belanja Jasa Sewa Lainnya', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '5.2.5', 'parent_uuid' => '5.2', 'uraian' => 'Belanja Jasa Pelayanan Kebersihan Kantor', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '5.2.5.01', 'parent_uuid' => '5.2.5', 'uraian' => 'Belanja Jasa Pelayanan Kebersihan dan Kerumahtanggaan Kantor', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '5.2.5.02', 'parent_uuid' => '5.2.5', 'uraian' => 'Belanja Jasa Pelayanan Pengamanan Kantor', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '5.2.6', 'parent_uuid' => '5.2', 'uraian' => 'Belanja Jasa Konsultansi', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '5.2.6.01', 'parent_uuid' => '5.2.6', 'uraian' => 'Belanja Jasa Konsultansi Perencanaan/Pengawasan', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '5.2.6.02', 'parent_uuid' => '5.2.6', 'uraian' => 'Belanja Jasa Konsultansi Pelaksanaan', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '5.2.6.90-99', 'parent_uuid' => '5.2.6', 'uraian' => 'Belanja Jasa Konsultansi Lainnya', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '5.2.7', 'parent_uuid' => '5.2', 'uraian' => 'Belanja Jasa Publikasi/Promosi/Pencetakan', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '5.2.7.01', 'parent_uuid' => '5.2.7', 'uraian' => 'Belanja Jasa Publikasi/Promosi', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '5.2.7.02', 'parent_uuid' => '5.2.7', 'uraian' => 'Belanja Jasa Pencetakan', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '5.2.7.90-99', 'parent_uuid' => '5.2.7', 'uraian' => 'Belanja Jasa Publikasi/Promosi/Pencetakan Lainnya', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '5.2.8', 'parent_uuid' => '5.2', 'uraian' => 'Belanja Jasa Lainnya', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '5.2.8.01', 'parent_uuid' => '5.2.8', 'uraian' => 'Belanja Pengganti Kacamata/Alat Bantu', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '5.2.8.02', 'parent_uuid' => '5.2.8', 'uraian' => 'Belanja Kebersihan/Peliharaan Halaman Kantor', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '5.2.8.03', 'parent_uuid' => '5.2.8', 'uraian' => 'Belanja Penerimaan Tamu/Pelayanan', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '5.2.8.04', 'parent_uuid' => '5.2.8', 'uraian' => 'Belanja Imbalan Jasa', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '5.2.8.05', 'parent_uuid' => '5.2.8', 'uraian' => 'Belanja Jasa Lainnya yang Sah', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '5.3', 'parent_uuid' => '5', 'uraian' => 'Belanja Modal', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '5.3.1', 'parent_uuid' => '5.3', 'uraian' => 'Belanja Modal Tanah', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '5.3.1.01', 'parent_uuid' => '5.3.1', 'uraian' => 'Belanja Modal Tanah Untuk Pembangunan Gedung Kantor', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '5.3.1.02', 'parent_uuid' => '5.3.1', 'uraian' => 'Belanja Modal Tanah Untuk Pembangunan Fasilitas Pemerintahan', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '5.3.1.03', 'parent_uuid' => '5.3.1', 'uraian' => 'Belanja Modal Tanah Untuk Pembangunan Gedung Pendidikan', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '5.3.1.04', 'parent_uuid' => '5.3.1', 'uraian' => 'Belanja Modal Tanah Untuk Pembangunan Fasilitas Pendidikan', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '5.3.1.05', 'parent_uuid' => '5.3.1', 'uraian' => 'Belanja Modal Tanah Untuk Pembangunan Fasilitas Kesehatan', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '5.3.1.06', 'parent_uuid' => '5.3.1', 'uraian' => 'Belanja Modal Tanah Untuk Pembangunan Fasilitas Umum', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '5.3.1.07', 'parent_uuid' => '5.3.1', 'uraian' => 'Belanja Modal Tanah Untuk Pembangunan Infrastruktur', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '5.3.1.08', 'parent_uuid' => '5.3.1', 'uraian' => 'Belanja Modal Tanah Untuk Pembangunan Irigasi', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '5.3.1.09', 'parent_uuid' => '5.3.1', 'uraian' => 'Belanja Modal Tanah Untuk Pembangunan Sarana Perhubungan', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '5.3.1.10', 'parent_uuid' => '5.3.1', 'uraian' => 'Belanja Modal Tanah Untuk Pembangunan Energi', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '5.3.1.11', 'parent_uuid' => '5.3.1', 'uraian' => 'Belanja Modal Tanah Untuk Pembangunan Sarana Air Bersih', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '5.3.1.12', 'parent_uuid' => '5.3.1', 'uraian' => 'Belanja Modal Tanah Untuk Pembangunan Prasarana Sanitasi', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '5.3.1.13', 'parent_uuid' => '5.3.1', 'uraian' => 'Belanja Modal Tanah Untuk Pembangunan Sarana Wisata', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '5.3.1.14', 'parent_uuid' => '5.3.1', 'uraian' => 'Belanja Modal Tanah Untuk Pembangunan Sosial Budaya', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '5.3.1.90-99', 'parent_uuid' => '5.3.1', 'uraian' => 'Belanja Modal Tanah Untuk Pembangunan Lainnya', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '5.3.2', 'parent_uuid' => '5.3', 'uraian' => 'Belanja Modal Gedung dan Bangunan', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '5.3.2.01', 'parent_uuid' => '5.3.2', 'uraian' => 'Belanja Modal Gedung Kantor', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '5.3.2.02', 'parent_uuid' => '5.3.2', 'uraian' => 'Belanja Modal Gedung Fasilitas Pemerintahan', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '5.3.2.03', 'parent_uuid' => '5.3.2', 'uraian' => 'Belanja Modal Gedung Pendidikan', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '5.3.2.04', 'parent_uuid' => '5.3.2', 'uraian' => 'Belanja Modal Gedung Fasilitas Pendidikan', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '5.3.2.05', 'parent_uuid' => '5.3.2', 'uraian' => 'Belanja Modal Gedung Fasilitas Kesehatan', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '5.3.2.06', 'parent_uuid' => '5.3.2', 'uraian' => 'Belanja Modal Gedung Fasilitas Umum', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '5.3.2.07', 'parent_uuid' => '5.3.2', 'uraian' => 'Belanja Modal Gedung Infrastruktur', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '5.3.2.08', 'parent_uuid' => '5.3.2', 'uraian' => 'Belanja Modal Gedung Irigasi', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '5.3.2.09', 'parent_uuid' => '5.3.2', 'uraian' => 'Belanja Modal Gedung Sarana Perhubungan', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '5.3.2.10', 'parent_uuid' => '5.3.2', 'uraian' => 'Belanja Modal Gedung Energi', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '5.3.2.11', 'parent_uuid' => '5.3.2', 'uraian' => 'Belanja Modal Gedung Sarana Air Bersih', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '5.3.2.12', 'parent_uuid' => '5.3.2', 'uraian' => 'Belanja Modal Gedung Prasarana Sanitasi', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '5.3.2.13', 'parent_uuid' => '5.3.2', 'uraian' => 'Belanja Modal Gedung Sarana Wisata', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '5.3.2.14', 'parent_uuid' => '5.3.2', 'uraian' => 'Belanja Modal Gedung Sosial Budaya', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '5.3.2.90-99', 'parent_uuid' => '5.3.2', 'uraian' => 'Belanja Modal Gedung dan Bangunan Lainnya', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '5.3.3', 'parent_uuid' => '5.3', 'uraian' => 'Belanja Modal Peralatan, Mesin dan Barang Bergerak', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '5.3.3.01', 'parent_uuid' => '5.3.3', 'uraian' => 'Belanja Modal Peralatan, Mesin dan Barang Bergerak Gedung Kantor', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '5.3.3.02', 'parent_uuid' => '5.3.3', 'uraian' => 'Belanja Modal Peralatan, Mesin dan Barang Bergerak Fasilitas Pemerintahan', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '5.3.3.03', 'parent_uuid' => '5.3.3', 'uraian' => 'Belanja Modal Peralatan, Mesin dan Barang Bergerak Gedung Pendidikan', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '5.3.3.04', 'parent_uuid' => '5.3.3', 'uraian' => 'Belanja Modal Peralatan, Mesin dan Barang Bergerak Fasilitas Pendidikan', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '5.3.3.05', 'parent_uuid' => '5.3.3', 'uraian' => 'Belanja Modal Peralatan, Mesin dan Barang Bergerak Fasilitas Kesehatan', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '5.3.3.06', 'parent_uuid' => '5.3.3', 'uraian' => 'Belanja Modal Peralatan, Mesin dan Barang Bergerak Fasilitas Umum', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '5.3.3.07', 'parent_uuid' => '5.3.3', 'uraian' => 'Belanja Modal Peralatan, Mesin dan Barang Bergerak Infrastruktur', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '5.3.3.08', 'parent_uuid' => '5.3.3', 'uraian' => 'Belanja Modal Peralatan, Mesin dan Barang Bergerak Irigasi', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '5.3.3.09', 'parent_uuid' => '5.3.3', 'uraian' => 'Belanja Modal Peralatan, Mesin dan Barang Bergerak Sarana Perhubungan', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '5.3.3.10', 'parent_uuid' => '5.3.3', 'uraian' => 'Belanja Modal Peralatan, Mesin dan Barang Bergerak Energi', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '5.3.3.11', 'parent_uuid' => '5.3.3', 'uraian' => 'Belanja Modal Peralatan, Mesin dan Barang Bergerak Sarana Air Bersih', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '5.3.3.12', 'parent_uuid' => '5.3.3', 'uraian' => 'Belanja Modal Peralatan, Mesin dan Barang Bergerak Prasarana Sanitasi', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '5.3.3.13', 'parent_uuid' => '5.3.3', 'uraian' => 'Belanja Modal Peralatan, Mesin dan Barang Bergerak Sarana Wisata', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '5.3.3.14', 'parent_uuid' => '5.3.3', 'uraian' => 'Belanja Modal Peralatan, Mesin dan Barang Bergerak Sosial Budaya', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '5.3.3.90-99', 'parent_uuid' => '5.3.3', 'uraian' => 'Belanja Modal Peralatan, Mesin dan Barang Bergerak Lainnya', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '5.3.4', 'parent_uuid' => '5.3', 'uraian' => 'Belanja Modal Fisik Lainnya', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '5.3.4.01', 'parent_uuid' => '5.3.4', 'uraian' => 'Belanja Modal Fisik Lainnya Untuk Gedung Kantor', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '5.3.4.02', 'parent_uuid' => '5.3.4', 'uraian' => 'Belanja Modal Fisik Lainnya Untuk Fasilitas Pemerintahan', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '5.3.4.03', 'parent_uuid' => '5.3.4', 'uraian' => 'Belanja Modal Fisik Lainnya Untuk Gedung Pendidikan', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '5.3.4.04', 'parent_uuid' => '5.3.4', 'uraian' => 'Belanja Modal Fisik Lainnya Untuk Fasilitas Pendidikan', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '5.3.4.05', 'parent_uuid' => '5.3.4', 'uraian' => 'Belanja Modal Fisik Lainnya Untuk Fasilitas Kesehatan', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '5.3.4.06', 'parent_uuid' => '5.3.4', 'uraian' => 'Belanja Modal Fisik Lainnya Untuk Fasilitas Umum', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '5.3.4.07', 'parent_uuid' => '5.3.4', 'uraian' => 'Belanja Modal Fisik Lainnya Untuk Infrastruktur', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '5.3.4.08', 'parent_uuid' => '5.3.4', 'uraian' => 'Belanja Modal Fisik Lainnya Untuk Irigasi', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '5.3.4.09', 'parent_uuid' => '5.3.4', 'uraian' => 'Belanja Modal Fisik Lainnya Untuk Sarana Perhubungan', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '5.3.4.10', 'parent_uuid' => '5.3.4', 'uraian' => 'Belanja Modal Fisik Lainnya Untuk Energi', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '5.3.4.11', 'parent_uuid' => '5.3.4', 'uraian' => 'Belanja Modal Fisik Lainnya Untuk Sarana Air Bersih', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '5.3.4.12', 'parent_uuid' => '5.3.4', 'uraian' => 'Belanja Modal Fisik Lainnya Untuk Prasarana Sanitasi', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '5.3.4.13', 'parent_uuid' => '5.3.4', 'uraian' => 'Belanja Modal Fisik Lainnya Untuk Sarana Wisata', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '5.3.4.14', 'parent_uuid' => '5.3.4', 'uraian' => 'Belanja Modal Fisik Lainnya Untuk Sosial Budaya', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '5.3.4.90-99', 'parent_uuid' => '5.3.4', 'uraian' => 'Belanja Modal Fisik Lainnya Untuk Kegiatan Lainnya', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '5.4', 'parent_uuid' => '5', 'uraian' => 'Belanja Pemberdayaan Masyarakat', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '5.4.1', 'parent_uuid' => '5.4', 'uraian' => 'Belanja Pemberdayaan Masyarakat', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '5.4.1.01', 'parent_uuid' => '5.4.1', 'uraian' => 'Belanja Pemberdayaan Masyarakat', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '5.5', 'parent_uuid' => '5', 'uraian' => 'Belanja Tak Terduga', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '5.5.1', 'parent_uuid' => '5.5', 'uraian' => 'Belanja Tak Terduga', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '5.5.1.01', 'parent_uuid' => '5.5.1', 'uraian' => 'Belanja Tak Terduga', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],

                // Pembiayaan
                ['uuid' => '6', 'parent_uuid' => null, 'uraian' => 'Pembiayaan', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '6.1', 'parent_uuid' => '6', 'uraian' => 'Penerimaan Pembiayaan', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '6.1.1', 'parent_uuid' => '6.1', 'uraian' => 'SILPA Tahun Sebelumnya', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '6.1.1.01', 'parent_uuid' => '6.1.1', 'uraian' => 'SILPA Tahun Sebelumnya', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '6.1.2', 'parent_uuid' => '6.1', 'uraian' => 'Pencairan Dana Cadangan', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '6.1.2.01', 'parent_uuid' => '6.1.2', 'uraian' => 'Pencairan Dana Cadangan', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '6.1.3', 'parent_uuid' => '6.1', 'uraian' => 'Hasil Penjualan Kekayaan Desa yang Dipisahkan', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '6.1.3.01', 'parent_uuid' => '6.1.3', 'uraian' => 'Hasil Penjualan Kekayaan Desa yang Dipisahkan', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '6.1.9', 'parent_uuid' => '6.1', 'uraian' => 'Penerimaan Pembiayaan Lainnya', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '6.1.9.01', 'parent_uuid' => '6.1', 'uraian' => 'Penerimaan Pembiayaan Lainnya', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '6.1.9.90-99', 'parent_uuid' => '6.1.9', 'uraian' => 'Penerimaan Pembiayaan Lainnya', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '6.2', 'parent_uuid' => '6', 'uraian' => 'Pengeluaran Pembiayaan', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '6.2.1', 'parent_uuid' => '6.2', 'uraian' => 'Pembentukan Dana Cadangan', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '6.2.1.01', 'parent_uuid' => '6.2.1', 'uraian' => 'Pembentukan Dana Cadangan', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '6.2.2', 'parent_uuid' => '6.2', 'uraian' => 'Penyertaan Modal Desa', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '6.2.2.01', 'parent_uuid' => '6.2.2', 'uraian' => 'Penyertaan Modal Desa', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '6.2.9', 'parent_uuid' => '6.2', 'uraian' => 'Pengeluaran Pembiayaan Lainnya', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '6.2.9.01', 'parent_uuid' => '6.2.9', 'uraian' => 'Pengeluaran Pembiayaan Lainnya', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['uuid' => '6.2.9.90-99', 'parent_uuid' => '6.2.9', 'uraian' => 'Pengeluaran Pembiayaan Lainnya', 'created_by' => 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ];

            collect($template)
                ->chunk(100)
                ->each(static function ($chunk) {
                    KeuanganTemplate::insert($chunk->all());
                });
        }

        if (! Schema::hasTable('keuangan')) {
            Schema::create('keuangan', static function (Blueprint $table) {
                $table->id();
                $table->integer('config_id');
                $table->char('template_uuid', 36);
                $table->integer('tahun');
                $table->decimal('anggaran', 65, 2)->default(0);
                $table->decimal('realisasi', 65, 2)->default(0);

                $table->integer('created_by')->nullable();
                $table->integer('updated_by')->nullable();
                $table->timestamps();

                $table->foreign('config_id')->references('id')->on('config');
                $table->foreign('template_uuid')->references('uuid')->on('keuangan_template');
            });
        }

        Schema::table('keuangan', static function (Blueprint $table) {
            $table->dropForeign(['config_id']);
            $table->foreign('config_id')->references('id')->on('config')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    protected function migrasi_2024102351()
    {
        Modul::where('slug', 'input-data')->update(['url' => 'keuangan_manual']);
    }

    protected function migrasi_2024102551()
    {
        $configId = identitas('id');
        $userId   = auth()->id ?? User::first()?->id;
        // migrasikan keuangan_manual_rinci ke keuangan
        $manual = KeuanganManualRinci::distinct()->select('tahun')->get();

        foreach ($manual as $item) {
            if (! $item->tahun) continue;

            $keuanganBaru = Keuangan::where('tahun', $item->tahun)->exists();
            if (! $keuanganBaru) {
                $tahun = $item->tahun;
                $sql   = <<<SQL
                                    insert into keuangan (config_id, template_uuid, tahun, anggaran, realisasi, created_by, updated_by, created_at, updated_at)
                                    select {$configId}, uuid, {$tahun}, coalesce(z.anggaran,0), coalesce(z.realisasi,0), {$userId}, {$userId}, now(), now() from keuangan_template
                                    left join (
                                        -- hitung template_uuid parent selain awalan 5
                                        select distinct left(l.Kd_Rincian,3) as template_uuid, coalesce((select sum(k.Nilai_Anggaran) from keuangan_manual_rinci k where k.tahun = '{$tahun}' and k.config_id = '{$configId}' and left(k.Kd_Rincian,3) = left(l.Kd_Rincian,3)), 0) as anggaran, coalesce((select sum(k.Nilai_realisasi) from keuangan_manual_rinci k where k.tahun = '{$tahun}' and k.config_id = '{$configId}' and left(k.Kd_Rincian,3) = left(l.Kd_Rincian,3)), 0) as realisasi
                                        from keuangan_manual_rinci_tpl l where l.Kd_Rincian != '5.0.0'
                                        union all
                                        -- hitung template_uuid parent awalan 5
                                        select distinct concat(left(Kd_Rincian,2), substring(Kd_Keg,10,1)) as template_uuid, coalesce((select sum(k.Nilai_Anggaran) from keuangan_manual_rinci k where k.tahun = '{$tahun}' and k.config_id = '{$configId}' and left(k.Kd_Rincian,2) = left(l.Kd_Rincian,2) and k.Kd_Keg = l.Kd_Keg), 0) as anggaran, coalesce((select sum(k.Nilai_Realisasi) from keuangan_manual_rinci k where k.tahun = '{$tahun}' and k.config_id = '{$configId}' and left(k.Kd_Rincian,2) = left(l.Kd_Rincian,2) and k.Kd_Keg = l.Kd_Keg), 0) as realisasi
                                        from keuangan_manual_rinci_tpl l where l.Kd_Rincian = '5.0.0'
                                        union all
                                        -- hitung template_uuid parent 5 karakter selain awalan 5
                                        select left(l.Kd_Rincian,5) as template_uuid, coalesce((select sum(k.Nilai_Anggaran) from keuangan_manual_rinci k where k.tahun = '{$tahun}' and k.config_id = '{$configId}' and k.Kd_Rincian = l.Kd_Rincian), 0) as anggaran, coalesce((select sum(k.Nilai_Realisasi) from keuangan_manual_rinci k where k.tahun = '{$tahun}' and k.config_id = '{$configId}' and k.Kd_Rincian = l.Kd_Rincian), 0) as realisasi
                                        from keuangan_manual_rinci_tpl l where l.Kd_Rincian != '5.0.0'
                                        union all
                                        -- hitung template_uuid parent 5 karakter awalan 5
                                        select concat(left(Kd_Rincian,2), substring(Kd_Keg,10,1), '.1') as template_uuid, coalesce((select sum(k.Nilai_Anggaran) from keuangan_manual_rinci k where k.tahun = '{$tahun}' and k.config_id = '{$configId}' and k.Kd_Rincian = l.Kd_Rincian and k.Kd_Keg = l.Kd_Keg), 0) as anggaran, coalesce((select sum(k.Nilai_Realisasi) from keuangan_manual_rinci k where k.tahun = '{$tahun}' and k.config_id = '{$configId}' and k.Kd_Rincian = l.Kd_Rincian and k.Kd_Keg = l.Kd_Keg), 0) as realisasi
                                        from keuangan_manual_rinci_tpl l where l.Kd_Rincian = '5.0.0'
                                        union all
                                        -- hitung template_uuid parent 8 karakter lebih selain awalan 5, sebagai detail
                                        select concat(left(l.Kd_Rincian,5),'.01') as template_uuid, coalesce((select sum(k.Nilai_Anggaran) from keuangan_manual_rinci k where k.tahun = '{$tahun}' and k.config_id = '{$configId}' and k.Kd_Rincian = l.Kd_Rincian), 0) as anggaran, coalesce((select sum(k.Nilai_Realisasi) from keuangan_manual_rinci k where k.tahun = '{$tahun}' and k.config_id = '{$configId}' and k.Kd_Rincian = l.Kd_Rincian), 0) as realisasi
                                        from keuangan_manual_rinci_tpl l where l.Kd_Rincian != '5.0.0'
                                        union all
                                        -- hitung template_uuid parent 8 karakter lebih awalan 5, sebagai detail
                                        select concat(left(Kd_Rincian,2), substring(Kd_Keg,10,1), '.1', '.01') as template_uuid, coalesce((select sum(k.Nilai_Anggaran) from keuangan_manual_rinci k where k.tahun = '{$tahun}' and k.config_id = '{$configId}' and k.Kd_Rincian = l.Kd_Rincian and k.Kd_Keg = l.Kd_Keg), 0) as anggaran, coalesce((select sum(k.Nilai_Realisasi) from keuangan_manual_rinci k where k.tahun = '{$tahun}' and k.config_id = '{$configId}' and k.Kd_Rincian = l.Kd_Rincian and k.Kd_Keg = l.Kd_Keg), 0) as realisasi
                                        from keuangan_manual_rinci_tpl l where l.Kd_Rincian = '5.0.0'
                                    )z on z.template_uuid = keuangan_template.uuid
                    SQL;
            DB::statement($sql);
            }
        }
        // migrasikan keuangan_ta_rab_rinci dan keuangan_ta_jurnal_umum_rinci hasil impor siskeudes ke keuangan
        $siskeudes = DB::select('select distinct Tahun from keuangan_ta_rab_rinci');
        if ($siskeudes) {
            foreach ($siskeudes as $item) {
                $tahun = $item->Tahun;
                if (! $tahun) continue;

                $keuanganBaru = Keuangan::where('tahun', $tahun)->exists();
                if (! $keuanganBaru) {
                $sql = <<<SQL
                                        insert into keuangan (config_id, template_uuid, tahun, anggaran, realisasi, created_by, updated_by, created_at, updated_at)
                                        select {$configId}, uuid, {$tahun}, coalesce(z.anggaran,0), coalesce(z.realisasi,0), {$userId}, {$userId}, now(), now() from keuangan_template
                                        left join (
                                        -- hitung template_uuid parent selain awalan 5
                                        select distinct left(l.Kd_Rincian,3) as template_uuid, coalesce((select sum(k.AnggaranStlhPAK) from keuangan_ta_rab_rinci k where k.Tahun = '{$tahun}' and k.config_id = '{$configId}' and left(k.Kd_Rincian,3) = left(l.Kd_Rincian,3)), 0) as anggaran, coalesce((select sum(k.Debet + k.Kredit) from keuangan_ta_jurnal_umum_rinci k where k.Tahun = '{$tahun}' and k.config_id = '{$configId}' and left(k.Kd_Rincian,3) = left(l.Kd_Rincian,3)), 0) as realisasi
                                        from keuangan_manual_rinci_tpl l where l.Kd_Rincian != '5.0.0'
                                        union all
                                        -- hitung template_uuid parent awalan 5
                                        select distinct concat(left(Kd_Rincian,2), substring(Kd_Keg,10,1)) as template_uuid, coalesce((select sum(k.AnggaranStlhPAK) from keuangan_ta_rab_rinci k where k.Tahun = '{$tahun}' and k.config_id = '{$configId}' and left(k.Kd_Rincian,2) = left(l.Kd_Rincian,2) and k.Kd_Keg = l.Kd_Keg), 0) as anggaran, coalesce((select sum(k.Debet + k.Kredit) from keuangan_ta_jurnal_umum_rinci k where k.Tahun = '{$tahun}' and k.config_id = '{$configId}' and left(k.Kd_Rincian,2) = left(l.Kd_Rincian,2) and k.Kd_Keg = l.Kd_Keg), 0) as realisasi
                                        from keuangan_manual_rinci_tpl l where l.Kd_Rincian = '5.0.0'
                                        union all
                                        -- hitung template_uuid parent 5 karakter selain awalan 5
                                        select left(l.Kd_Rincian,5) as template_uuid, coalesce((select sum(k.AnggaranStlhPAK) from keuangan_ta_rab_rinci k where k.Tahun = '{$tahun}' and k.config_id = '{$configId}' and k.Kd_Rincian = l.Kd_Rincian), 0) as anggaran, coalesce((select sum(k.Debet + k.Kredit) from keuangan_ta_jurnal_umum_rinci k where k.Tahun = '{$tahun}' and k.config_id = '{$configId}' and k.Kd_Rincian = l.Kd_Rincian), 0) as realisasi
                                        from keuangan_manual_rinci_tpl l where l.Kd_Rincian != '5.0.0'
                                        union all
                                        -- hitung template_uuid parent 5 karakter awalan 5
                                        select concat(left(Kd_Rincian,2), substring(Kd_Keg,10,1), '.1') as template_uuid, coalesce((select sum(k.AnggaranStlhPAK) from keuangan_ta_rab_rinci k where k.Tahun = '{$tahun}' and k.config_id = '{$configId}' and k.Kd_Rincian = l.Kd_Rincian and k.Kd_Keg = l.Kd_Keg), 0) as anggaran, coalesce((select sum(k.Debet + k.Kredit) from keuangan_ta_jurnal_umum_rinci k where k.Tahun = '{$tahun}' and k.config_id = '{$configId}' and k.Kd_Rincian = l.Kd_Rincian and k.Kd_Keg = l.Kd_Keg), 0) as realisasi
                                        from keuangan_manual_rinci_tpl l where l.Kd_Rincian = '5.0.0'
                                        union all
                                        -- hitung template_uuid parent 8 karakter lebih selain awalan 5, sebagai detail
                                        select concat(left(l.Kd_Rincian,5),'.01') as template_uuid, coalesce((select sum(k.AnggaranStlhPAK) from keuangan_ta_rab_rinci k where k.Tahun = '{$tahun}' and k.config_id = '{$configId}' and k.Kd_Rincian = l.Kd_Rincian), 0) as anggaran, coalesce((select sum(k.Debet + k.Kredit) from keuangan_ta_jurnal_umum_rinci k where k.Tahun = '{$tahun}' and k.config_id = '{$configId}' and k.Kd_Rincian = l.Kd_Rincian), 0) as realisasi
                                        from keuangan_manual_rinci_tpl l where l.Kd_Rincian != '5.0.0'
                                        union all
                                        -- hitung template_uuid parent 8 karakter lebih awalan 5, sebagai detail
                                        select concat(left(Kd_Rincian,2), substring(Kd_Keg,10,1), '.1', '.01') as template_uuid, coalesce((select sum(k.AnggaranStlhPAK) from keuangan_ta_rab_rinci k where k.Tahun = '{$tahun}' and k.config_id = '{$configId}' and k.Kd_Rincian = l.Kd_Rincian and k.Kd_Keg = l.Kd_Keg), 0) as anggaran, coalesce((select sum(k.Debet + k.Kredit) from keuangan_ta_jurnal_umum_rinci k where k.Tahun = '{$tahun}' and k.config_id = '{$configId}' and k.Kd_Rincian = l.Kd_Rincian and k.Kd_Keg = l.Kd_Keg), 0) as realisasi
                                        from keuangan_manual_rinci_tpl l where l.Kd_Rincian = '5.0.0'
                                        )z on z.template_uuid = keuangan_template.uuid
                    SQL;
                DB::statement($sql);
                }
            }
        }
    }

    protected function migrasi_2024112671()
    {
        $this->tambah_setting([
            'judul'      => 'Tampilkan C-desa di Peta Website',
            'key'        => 'tampilkan_cdesa_petaweb',
            'value'      => '1',
            'keterangan' => 'Aktif / Non-aktif C-desa Halaman Peta Website',
            'jenis'      => 'boolean',
            'option'     => null,
            'attribute'  => null,
            'kategori'   => 'Peta',
        ]);
    }

    protected function migrasi_2024112672()
    {
        if (! Schema::hasColumn('persil', 'is_publik')) {
            Schema::table('persil', static function (Blueprint $table) {
                $table->tinyInteger('is_publik')->default(1)->comment('1 = tampilkan di web publik, 0 = tidak ditampilkan di web publik');
            });
        }
    }

    public function migrasi_2024122451()
    {
        if (! Schema::hasColumn('migrasi', 'config_id')) {
            Schema::table('migrasi', static function ($table) {
                $table->configId();
                $table->unique(['config_id', 'versi_database'], 'versi_database_config');
            });
            // ini hanya dijalankan jika tabel migrasi belum memiliki config_id
            DB::statement('create table if not exists migrasi_temp as select * from migrasi');
            DB::statement('truncate migrasi');
            $sql = 'INSERT INTO migrasi (config_id, versi_database, premium) select config.id, versi_database, premium from migrasi_temp cross join config';
            DB::statement($sql);
            DB::statement('drop table if exists migrasi_temp');
        }
    }

    protected function migrasi_2024120171()
    {
        if (! Schema::hasColumn('cdesa', 'nik_pemilik_luar')) {
            Schema::table('cdesa', static function (Blueprint $table) {
                $table->string('nik_pemilik_luar', 16)->nullable()->after('jenis_pemilik');
            });
        }
    }

    protected function migrasi_2024123171()
    {
        if (! Schema::hasColumn('cdesa', 'nik_pemilik_luar')) {
            Schema::table('cdesa', static function (Blueprint $table) {
                $table->string('nik_pemilik_luar', 16)->nullable()->after('jenis_pemilik');
            });
        }
    }

    protected function migrasi_2024121971()
    {
        if (! Schema::hasColumn('tweb_penduduk', 'status_asuransi')) {
            Schema::table('tweb_penduduk', static function (Blueprint $table) {
                $table->tinyInteger('status_asuransi')->nullable()->default(null)->after('no_asuransi');
            });
        }
    }

    public function migrasi_2024123151()
    {
        if (GrupAkses::where('id_grup', UserGrup::getGrupId(UserGrup::ADMINISTRATOR))->count() === 0) {
            (new CreateGrupAksesService())->handle();
        }
    }
}
