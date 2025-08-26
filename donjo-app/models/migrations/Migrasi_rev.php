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

use App\Traits\Migrator;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

defined('BASEPATH') || exit('No direct script access allowed');

class Migrasi_rev
{
    use Migrator;

    public function up()
    {

        $this->perbaikiMigrasiModulKeuangan();
        $this->tambahKolomCatatanLogPenduduk();
        $this->perbaikiMigrasiDokumen();
        $this->updateKolomWajibPendudukTidakBolehNull();

    }

    public function perbaikiMigrasiModulKeuangan()
    {
        require_once APPPATH . 'models/migrations/Migrasi_2025010171.php';

        (new Migrasi_2025010171())->up();
    }

    public function tambahKolomCatatanLogPenduduk()
    {
        if (! Schema::hasColumn('log_penduduk', 'catatan')) {
            Schema::table('log_penduduk', static function ($table) {
                $table->mediumText('catatan')->nullable()->after('tgl_peristiwa');
            });
        }
    }

    public function perbaikiMigrasiDokumen()
    {
        require_once APPPATH . 'models/migrations/Migrasi_2025040171.php';

        (new Migrasi_2025040171())->sesuaikanDokumenInformasiPublik();
    }

    /**
     * Set kolom-kolom wajib menjadi NOT NULL, dan set default jika diperlukan
     */
    protected function updateKolomWajibPendudukTidakBolehNull()
    {
        try {
            // isi data NULL dengan default
            DB::table('tweb_penduduk')->whereNull('dokumen_kitas')->where('config_id', identitas('id'))->update(['dokumen_kitas' => '-']);
            DB::table('tweb_penduduk')->whereNull('dokumen_pasport')->where('config_id', identitas('id'))->update(['dokumen_pasport' => '-']);

            Schema::table('tweb_penduduk', static function (Blueprint $table) {
                $table->string('nama')->nullable(false)->change();
                $table->string('nik')->nullable(false)->change();
                $table->unsignedTinyInteger('sex')->nullable(false)->change();
                $table->tinyInteger('kk_level')->nullable(false)->change();
                $table->string('tempatlahir')->nullable(false)->change();
                $table->date('tanggallahir')->nullable(false)->change();
                $table->integer('agama_id')->nullable(false)->change();
                $table->integer('pendidikan_kk_id')->nullable(false)->change();
                $table->integer('pekerjaan_id')->nullable(false)->change();
                $table->string('golongan_darah_id')->nullable(false)->change();
                $table->tinyInteger('status_kawin')->nullable(false)->change();
                $table->integer('warganegara_id')->nullable(false)->change();
                $table->string('nama_ayah')->nullable(false)->change();
                $table->string('nama_ibu')->nullable(false)->change();
                $table->string('dokumen_pasport')->default('-')->nullable(false)->change();
                $table->string('dokumen_kitas')->default('-')->nullable(false)->change();
            });
        } catch (Exception $e) {
            log_message('error', 'Gagal memperbarui kolom wajib penduduk: ' . $e->getMessage());
            set_session('warning', 'Gagal memperbarui kolom isian yang wajib pada tabel tweb_penduduk. Silakan cek dan perbaiki data pendudukan di halaman <a href="/periksa">periksa</a> sebelum jalankan migrasi lagi.');
        }
    }
}
