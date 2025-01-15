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
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

defined('BASEPATH') || exit('No direct script access allowed');

class Migrasi_2025011551
{
    use Migrator;

    public function up()
    {
        $this->buatUlangForeignKeyKeuangan();
        $this->updateDataKeuanganManualRefRek2();
    }

    public function buatUlangForeignKeyKeuangan()
    {
        Schema::table('keuangan', static function (Blueprint $table) {
            $table->dropForeign(['config_id']);
            $table->foreign('config_id')->references('id')->on('config')->onUpdate('CASCADE')->onDelete('CASCADE');
        });        
    }

    public function updateDataKeuanganManualRefRek2()
    {
        $rek2 = DB::table('keuangan_manual_ref_rek2')
            ->where('Kelompok', '5.4.')
            ->where('Nama_Kelompok', 'Belanja Tidak Terduga')
            ->exists();

        $rek3 = DB::table('keuangan_manual_ref_rek3')
            ->where('Jenis', '5.4.1.')
            ->where('Nama_Jenis', 'Belanja Tidak Terduga')
            ->exists();

        if ($rek2) {
            // Update the existing record
            DB::table('keuangan_manual_ref_rek2')
                ->where('Kelompok', '5.4.')
                ->where('Nama_Kelompok', 'Belanja Tidak Terduga')
                ->update(['Nama_Kelompok' => 'Belanja Pemberdayaan Masyarakat']);

            // Insert a new record
            DB::table('keuangan_manual_ref_rek2')->insert([
                'Akun' => '5.',
                'Kelompok' => '5.5.',
                'Nama_Kelompok' => 'Belanja Tidak Terduga',
            ]);
        }

        if ($rek3) {
            // Update the existing record
            DB::table('keuangan_manual_ref_rek3')
                ->where('Jenis', '5.4.1.')
                ->update(['Nama_Jenis' => 'Belanja Pemberdayaan Masyarakat']);

            // Insert a new record
            DB::table('keuangan_manual_ref_rek3')->insert([
                'Kelompok' => '5.5.',
                'Jenis' => '5.5.1.',
                'Nama_Jenis' => 'Belanja Tidak Terduga',
            ]);
        }
    }
}

