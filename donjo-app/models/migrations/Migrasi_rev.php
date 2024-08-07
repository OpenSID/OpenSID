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

use Illuminate\Support\Facades\DB;

defined('BASEPATH') || exit('No direct script access allowed');

class Migrasi_rev extends MY_model
{
    public function up()
    {
        return $this->migrasi_2024080751(true);
        // Migrasi berdasarkan config_id
        // $config_id = DB::table('config')->pluck('id')->toArray();

        // foreach ($config_id as $id) {
        // }
    }

    protected function migrasi_2024080751($hasil)
    {
        // mutasi_inventaris_peralatan
        $hasil = $hasil && $this->hapus_foreign_key('inventaris_peralatan', 'FK_mutasi_inventaris_peralatan', 'mutasi_inventaris_peralatan');
        $hasil = $hasil && $this->tambahForeignKey('FK_mutasi_inventaris_peralatan', 'mutasi_inventaris_peralatan', 'id_inventaris_peralatan', 'inventaris_peralatan', 'id', true);
        // mutasi_inventaris_jalan
        $hasil = $hasil && $this->hapus_foreign_key('inventaris_jalan', 'FK_mutasi_inventaris_jalan', 'mutasi_inventaris_jalan');
        $hasil = $hasil && $this->tambahForeignKey('FK_mutasi_inventaris_jalan', 'mutasi_inventaris_jalan', 'id_inventaris_jalan', 'inventaris_jalan', 'id', true);
        // mutasi_inventaris_gedung
        $hasil = $hasil && $this->hapus_foreign_key('inventaris_gedung', 'FK_mutasi_inventaris_gedung', 'mutasi_inventaris_gedung');
        $hasil = $hasil && $this->tambahForeignKey('FK_mutasi_inventaris_gedung', 'mutasi_inventaris_gedung', 'id_inventaris_gedung', 'inventaris_gedung', 'id', true);
        // mutasi_inventaris_asset
        $hasil = $hasil && $this->hapus_foreign_key('inventaris_asset', 'FK_mutasi_inventaris_asset', 'mutasi_inventaris_asset');

        return $hasil && $this->tambahForeignKey('FK_mutasi_inventaris_asset', 'mutasi_inventaris_asset', 'id_inventaris_asset', 'inventaris_asset', 'id', true);
    }
}
