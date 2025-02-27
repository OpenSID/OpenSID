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

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

defined('BASEPATH') || exit('No direct script access allowed');

class Migrasi_2024082151 extends MY_model
{
    public function up()
    {
        $hasil = true;

        $hasil = $this->migrasi_2024081651($hasil);
        $hasil = $this->migrasi_2024082151($hasil);


        return $hasil && true;
    }

    protected function migrasi_2024081651($hasil)
    {
        $tables = [
            'keuangan_ta_spp',
            'keuangan_ta_sppbukti',
            'keuangan_ta_spp',
            'keuangan_ta_jurnal_umum',
            'keuangan_ta_mutasi',
            'keuangan_ta_pajak',
            'keuangan_ta_pencairan',
            'keuangan_ta_spj',
            'keuangan_ta_spj_bukti',
            'keuangan_ta_spp',
        ];

        foreach ($tables as $table) {
            Schema::table($table, static function (Blueprint $table) {
                $table->text('Keterangan')->nullable()->change();
            });
        }

        return $hasil;
    }

    protected function migrasi_2024082151($hasil)
    {
        if (! Schema::hasColumn('log_surat', 'isi_surat_temp')) {
            Schema::table('log_surat', static function (Blueprint $table) {
                $table->longText('isi_surat_temp')->nullable()->after('isi_surat');
            });
        }

        return $hasil;
    }
}
