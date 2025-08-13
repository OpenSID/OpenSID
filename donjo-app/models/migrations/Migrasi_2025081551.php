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

use App\Enums\SasaranEnum;
use App\Traits\Migrator;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

defined('BASEPATH') || exit('No direct script access allowed');

class Migrasi_2025081551
{
    use Migrator;

    public function up()
    {
        $this->tabelLogNotifikasiMandiri();
        $this->updatePinPendudukMandiri();
        $this->updateSuplemenTerdata();
    }

    protected function tabelLogNotifikasiMandiri()
    {
        if (! Schema::hasIndex('log_notifikasi_mandiri', 'log_notifikasi_mandiri_device_unique')) {
            return;
        }

        Schema::table('log_notifikasi_mandiri', static function (Blueprint $table) {
            $table->dropUnique('log_notifikasi_mandiri_device_unique');
        });
    }

    public function updatePinPendudukMandiri()
    {
        Schema::table('tweb_penduduk_mandiri', static function (Blueprint $table) {
            $table->string('pin')->change();
        });
    }

    protected function updateSuplemenTerdata()
    {
        if (! Schema::hasColumn('suplemen_terdata', 'penduduk_id') || ! Schema::hasColumn('suplemen_terdata', 'keluarga_id')) {
            Log::info('Migrasi 2024082651 tidak dijalankan, kolom penduduk_id atau keluarga_id tidak ditemukan.');

            return;
        }

        DB::beginTransaction();

        try {
            $config_id = identitas('id');

            // Isi penduduk_id jika sasaran = 1
            DB::table('suplemen_terdata AS st')
                ->join('tweb_penduduk AS p', static function ($join) {
                    $join->on('p.id', '=', 'st.id_terdata')
                        ->on('p.config_id', '=', 'st.config_id');
                })
                ->where('st.config_id', $config_id)
                ->where('st.sasaran', SasaranEnum::PENDUDUK)
                ->whereNull('st.penduduk_id')
                ->update([
                    'st.penduduk_id' => DB::raw('p.id'),
                ]);

            // Isi keluarga_id jika sasaran = 2
            DB::table('suplemen_terdata AS st')
                ->join('tweb_keluarga AS k', static function ($join) {
                    $join->on('k.id', '=', 'st.id_terdata')
                        ->on('k.config_id', '=', 'st.config_id');
                })
                ->where('st.config_id', $config_id)
                ->where('st.sasaran', SasaranEnum::KELUARGA)
                ->whereNull('st.keluarga_id')
                ->update([
                    'st.keluarga_id' => DB::raw('k.id'),
                ]);

            DB::commit(); // semua berhasil
            Log::info('Migrasi 2024082651 selesai sukses');

        } catch (Exception $e) {
            DB::rollBack(); // batalkan semua
            Log::error('Migrasi 2024082651 gagal: ' . $e->getMessage());
        }
    }
}
